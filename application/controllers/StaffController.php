<?php

class StaffController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_authService;
    protected $_staffModel;

    protected $_addMalfunctionForm;
    protected $_editMalfunctionForm;

    public function init()
    {
        $this->_helper->layout->setLayout('staff');
        $this->_logger = Zend_Registry::get('log');
        $this->_staffModel = new Application_Model_Staff();
        $this->_authService = new Application_Service_Auth();

        $this->_addMalfunctionForm = $this->getAddMalfunctionForm();
        $this->_editMalfunctionForm = $this->getEditMalfunctionForm();
    }

    public function indexAction()
    {

    }

    //carica la view per l'inserimento di un malfunzionamento
    public function addMalfunctionAction()
    {
    }

    //popola la form per la modifica
    public function updateMalfunctionAction()
    {
        //recupero l'id del componente da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei componenti da modificare
        if($id == null){
            $this->_helper->redirector('modificamalfunzionamento', 'staff');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editComponentForm->setAction($urlHelper->url(array(
            'controller' => 'staff',
            'action' => 'modificamalfunzionamento',
            'id' => $id
        ),
            'default'
        ));

        //recupero il prodotto
        $row = $this->_staffModel->getMalfunctionById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        $this->view->assign('vector',$vector);

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);

        $this->_editMalfunctionForm->populate($vector);
    }

    public function aggiungimalfunzionamentoAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'staff');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addMalfunctionForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('addmalfunction'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_staffModel->insertMalf($values);   //Definita in Model/Amministratore.php

    }

    public function cancellamalfunzionamentoAction()
    {
        //recupero l'id del componente da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_staffModel->deleteMalf($id);
        }

    }

    public function modificamalfunzionamentoAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'staff');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_editMalfunctionForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)

            $urlHelper = $this->_helper->getHelper('url');
            $this->_editMalfunctionForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificamalfunzionamento',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updatemalfunction'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->updateMalf($values,$id);

    }

    private function getAddMalfunctionForm(){
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addMalfunctionForm = new Application_Form_Staff_Malfunction_Add();
        $this->_addMalfunctionForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungimalfunzionamento'
        ),
            'default'
        ));
        return $this->_addMalfunctionForm;
    }

    private function getEditMalfunctionForm()
    {
        $this->_editMalfunctionForm = new Application_Form_Staff_Malfunction_Edit();
        return $this->_editMalfunctionForm;
    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}