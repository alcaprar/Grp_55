<?php

class StaffController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_authService;
    protected $_staffModel;
    protected $_addMalfunctionForm;
    protected $_editMalfunctionForm;
    protected $_categorie;

    public function init()
    {
        $this->_helper->layout->setLayout('staff');
        $this->_logger = Zend_Registry::get('log');
        $this->_staffModel = new Application_Model_Staff();

        $this->view->addMalfunctionForm = $this->getAddMalfunctionForm();
        $this->view->editMalfunctionForm = $this->getEditMalfunctionForm();

        $this->_categorie = array();
        $sessionCat = $_SESSION['staff']['categorie']->toArray();
        for($i=0;$i<sizeof($sessionCat);$i++){
            $this->_categorie[] = $sessionCat[$i]['idCategoria'];
        }

        $this->_authService = new Application_Service_Auth();
    }

    public function indexAction()
    {
        $this->_logger->log($this->_categorie,Zend_Log::DEBUG);
    }

    //carica la view per l'inserimento di un malfunzionamento
    public function addmalfunctionAction()
    {
    }

    //carica la view di scelta del prodotto con aggiornamento lato client dei malfunzionamenti.
    public function associatemalfunctionAction()
    {
        $where = (sizeof($this->_categorie)==0)? null : $this->_categorie;
        $prodotti = $this->_staffModel->getProducts($where);
        $malfunzionamenti = $this->_staffModel->selectMalfunction($paged=null, $order=null);

        $this->_logger->log($prodotti, Zend_Log::DEBUG);

        $this->view->assign(array(
            'Malfunzionamenti'=> $malfunzionamenti,
            'Prodotti' => $prodotti));
    }

    public function populatemalfAction()
    {

        if($this->getRequest()->isXmlHttpRequest()) {

            $this->_helper->getHelper('layout')->disableLayout();
            $this->_helper->viewRenderer->setNoRender();

            $param = $this->getRequest()->getParam('id');
            $this->_logger->log($param, Zend_Log::DEBUG);
            $malfs = $this->_staffModel->getMalfunctionsByIdProd($param);
            $idmalfs = array();
            foreach ($malfs as $malf) {
                $idmalfs[] = $malf->idMalfunzionamento;
            }
            $this->_logger->log($malfs, Zend_Log::DEBUG);
            $this->_helper->json($idmalfs, $sendNow = true, $keepLayouts = false, $encodeData = true);
        }else{
            $this->_helper->redirector('staff');
        }
    }

    //popola la form per la modifica
    public function updatemalfunctionAction()
    {
        //recupero l'id del malfunzionamento da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellamalfunzionamento', 'staff');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editMalfunctionForm->setAction($urlHelper->url(array(
            'controller' => 'staff',
            'action' => 'modificamalfunzionamento',
            'id' => $id
        ),
            'default'
        ));

        //recupero la faq
        $row = $this->_staffModel->getMalfunctionByIdFind($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }
        $this->view->assign('vector',$vector);

        $this->_logger->log($vector,Zend_Log::DEBUG);

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);

        $this->_editMalfunctionForm->populate($vector);
    }

    //scarica dal db la lista dei malfunzionamenti
    public function modificacancellamalfunzionamentoAction()
    {
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $malfunzionamenti = $this->_staffModel->selectMalfunction($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Malfunzionamenti',$malfunzionamenti);
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
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_staffModel->insertMalfunction($values);   //Definita in Model/Amministratore.php
    }

    public function modificamalfunzionamentoAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
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
                'controller' => 'staff',
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
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_staffModel->updateMalfunction($values,$id);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('modificacancellaprodotto','admin');

    }

    public function cancellamalfunzionamentoAction()
    {
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_staffModel->deleteMalfunction($id);
        }

    }

    public function associamalfunzionamentoAction()
    {
        //questa azione deve essere richiamata solo da richieste post.
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'staff');
        }

        //recupero i valori da inserire nel db
        $prodotto = $this->getRequest()->getPost('selectProdotti');
        $malf = $this->getRequest()->getPost('malf');

        $this->_staffModel->associateMalfunction($prodotto,$malf);

    }

    private function getAddMalfunctionForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addMalfunctionForm = new Application_Form_Staff_Malfunzionamento_Add();
        $this->_addMalfunctionForm->setAction($urlHelper->url(array(
            'controller' => 'staff',
            'action' => 'aggiungimalfunzionamento'
        ),
            'default'
        ));
        return $this->_addMalfunctionForm;
    }

    private function getEditMalfunctionForm()
    {
        $this->_editMalfunctionForm = new Application_Form_Staff_Malfunzionamento_Edit();
        return $this->_editMalfunctionForm;
    }


    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}