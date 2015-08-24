<?php

class AdminController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_form;

    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        $this->_logger = Zend_Registry::get('log');
        //$this->_publicModel = new Application_Model_Public(); //sostituire con il model per Admin da creare

        $this->view->productForm = $this->getProductForm();
    }

    public function indexAction()
    {

        $this->_logger->log('Admin - Index!', Zend_Log::DEBUG);

        //pagine statiche
        /*if(!is_null($viewStatic))
        {
            $this->render($viewStatic);
        }*/
    }

    public function addnewproductAction()
    {

    }

    public function addproductAction()
    {
        $this->_logger->log('addProduct',Zend_Log::DEBUG);
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            //$this->_helper->redirector('logout', 'amministratore');        //Specificando solo il controller (index) prende come azione di default indexAction
            $this->_logger->log('!isPost',Zend_Log::DEBUG);
        }

        $this->_logger->log('isPost',Zend_Log::DEBUG);
        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_form;



        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('insertnewfaq'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        //$this->_adminModel->saveFaq($values);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('faq');

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }

    private function getProductForm(){
        $urlHelper = $this->_helper->getHelper('url');

        $this->_form = new Application_Form_Admin_Product_Add();
        $this->_form->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'addproduct'
            ),
            'default'
        ));
        return $this->_form;
    }
}

