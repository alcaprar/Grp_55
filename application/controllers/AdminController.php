<?php

class AdminController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_addProductForm;
    protected $_editProductForm;

    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        $this->_logger = Zend_Registry::get('log');
        $this->_adminModel = new Application_Model_Admin();

        $this->view->addProductForm = $this->getProductForm();
        $this->view->editProductForm = $this->getEditProductForm();
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

    //carica la view per l'inserimento di un prodotto
    public function addproductAction()
    {

    }

    //popola la form per la modifica
    public function updateproductAction()
    {
        //recupero l'id del prodotto da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellaprodotti', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editProductForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificaprodotto',
            'id' => $id
        ),
            'default'
        ));

        //recupero il prodotto
        $row = $this->_adminModel->getProdById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        $this->view->assign('vector',$vector);

        $this->_logger->log($vector,Zend_Log::DEBUG);

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);
        unset($vector['Foto']);
        unset($vector['idCategoria']);

        $this->_editProductForm->populate($vector);
    }

    //scarica dal db la lista dei prodotti
    public function modificacancellaprodottoAction()
    {
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $prodotti = $this->_adminModel->selectProduct($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Prodotti',$prodotti);

    }

    public function aggiungiprodottotAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
            $this->_logger->log('!isPost',Zend_Log::DEBUG);
        }

        $this->_logger->log('isPost',Zend_Log::DEBUG);
        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addProductForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('addproduct'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_adminModel->insertProduct($values);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('faq');

    }

    public function cancellaprodottoAction()
    {
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteProduct($id);
        }
        $this->_helper->redirector('modificacancellaprodotto', 'admin'); //(azione, controller)

    }

    public function modificaprodottoAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
            $this->_logger->log('!isPost',Zend_Log::DEBUG);
        }

        $this->_logger->log('isPost',Zend_Log::DEBUG);
        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_editProductForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)

            //recupero l'id del prodotto da modificare
            $id = intval($this->_request->getParam('id'));

            $urlHelper = $this->_helper->getHelper('url');
            $this->_editProductForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificaprodotto',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updateproduct'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_adminModel->updateProduct($values);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('faq');

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }

    private function getProductForm(){
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addProductForm = new Application_Form_Admin_Product_Add();
        $this->_addProductForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungiprodotto'
            ),
            'default'
        ));
        return $this->_addProductForm;
    }

    private function getEditProductForm()
    {
        $this->_editProductForm = new Application_Form_Admin_Product_Edit();
        return $this->_editProductForm;
    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        //$this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}

