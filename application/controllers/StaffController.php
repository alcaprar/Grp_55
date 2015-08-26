<?php

class StaffController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_authService;
    protected $_staffModel;

    public function init()
    {
        $this->_helper->layout->setLayout('staff');
        $this->_logger = Zend_Registry::get('log');
        $this->_staffModel = new Application_Model_Staff();
        $this->_authService = new Application_Service_Auth();
    }

    public function indexAction()
    {

    }

    public function aggiungimalfunzionamentoAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addMalfunctionForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('addcomponent'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->insertComponent($values);   //Definita in Model/Amministratore.php

    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}