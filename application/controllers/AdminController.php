<?php

class AdminController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_addProductForm;
    protected $_editProductForm;
    protected $_addFaqForm;
    protected $_editFaqForm;
    protected $_addCentroForm;
    protected $_editCentroForm;
    protected $_addComponentForm;
    protected $_editComponentForm;
    protected $_addUserForm;
    protected $_editUserForm;

    protected $_authService;

    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        $this->_logger = Zend_Registry::get('log');
        $this->_adminModel = new Application_Model_Admin();

        $this->view->addProductForm = $this->getProductForm();
        $this->view->editProductForm = $this->getEditProductForm();
        $this->view->addFaqForm = $this->getAddFaqForm();
        $this->view->editFaqForm = $this->getEditFaqForm();
        $this->view->addCentroForm = $this->getAddCentroForm();
        $this->view->editCentroForm = $this->getEditCentroForm();
        $this->view->addComponentForm = $this->getAddComponentForm();
        $this->view->editComponentForm = $this->getEditComponentForm();
        $this->view->addUserForm = $this->getAddUserForm();
        $this->view->editUserForm = $this->getEditUserForm();

        $this->_authService = new Application_Service_Auth();
    }

    public function indexAction()
    {

    }

    //carica la view per l'inserimento di un prodotto
    public function addproductAction()
    {
        $select = $this->_addProductForm->getElement('idCategoria');

        $rows = $this->_adminModel->getCategorie();
        $categorie = [];

        foreach($rows->toArray() as $row)
        {
            $categorie[$row['id']] = $row['Nome'];
        }

        $select->setMultiOptions($categorie);

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

        //se la foto non è stata inserita aggiungo l'elemento alla form
        if($vector['Foto']==''){
            $this->_editProductForm->addElement('file', 'Foto', array(
                'label' => 'Immagine',
                'destination' => APPLICATION_PATH . '/../public/images/products',
                'validators' => array(
                    array('Count', false, 1),
                    array('Size', false, 102400),
                    array('Extension', false, array('jpg', 'gif'))),
            ));
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

    //carica la view per l'inserimento di una faq
    public function addfaqAction()
    {

    }

    //popola la form per la modifica
    public function updatefaqAction()
    {
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellafaq', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editFaqForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificafaq',
            'id' => $id
        ),
            'default'
        ));

        //recupero la faq
        $row = $this->_adminModel->getFaqById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        $this->view->assign('vector',$vector);

        $this->_logger->log($vector,Zend_Log::DEBUG);

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);

        $this->_editFaqForm->populate($vector);
    }

    //scarica dal db la lista delle faq
    public function modificacancellafaqAction()
    {
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $faq = $this->_adminModel->selectFaq($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Faq',$faq);
    }

    //carica la view per l'inserimento di un centro
    public function addcentroAction()
    {

    }

    //popola la form per la modifica
    public function updatecentroAction()
    {
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellacentro', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editCentroForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificacentro',
            'id' => $id
        ),
            'default'
        ));

        //recupero il centro
        $row = $this->_adminModel->getCentroById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        $this->view->assign('vector',$vector);

        $this->_logger->log($vector,Zend_Log::DEBUG);

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);

        $this->_editCentroForm->populate($vector);
    }

    //scarica dal db la lista dei centri assistenza
    public function modificacancellacentroAction()
    {
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $centro = $this->_adminModel->selectCentro($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Centro',$centro);
    }

    //carica la view per l'inserimento di un componente
    public function addcomponentAction()
    {
    }

    //popola la form per la modifica
    public function updatecomponentAction()
    {
        //recupero l'id del componente da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei componenti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellacomponenti', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editComponentForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificacomponente',
            'id' => $id
        ),
            'default'
        ));

        //recupero il prodotto
        $row = $this->_adminModel->getComponentById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        //se la foto non è stata inserita aggiungo l'elemento alla form
        if($vector['Foto']==''){
            $this->_editComponentForm->addElement('file', 'Foto', array(
                'label' => 'Immagine',
                'destination' => APPLICATION_PATH . '/../public/images/component',
                'validators' => array(
                    array('Count', false, 1),
                    array('Size', false, 102400),
                    array('Extension', false, array('jpg', 'gif'))),
            ));
        }

        $this->view->assign('vector',$vector);


        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);
        unset($vector['Foto']);

        $this->_editComponentForm->populate($vector);
    }


    //scarica dal db la lista dei componenti
    public function modificacancellacomponenteAction()
    {
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $componenti = $this->_adminModel->selectComponent($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Componenti',$componenti);

    }

    //carica la view per l'inserimento di un utente
    public function adduserAction()
    {

    }

    //popola la form per la modifica
    public function updateuserAction()
    {
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellautente', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editUserForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificautente',
            'id' => $id
        ),
            'default'
        ));

        //recupero l'utente
        $row = $this->_adminModel->getUserById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        $this->view->assign('vector',$vector);


        //rimuovo i campi che non ci sono nella form
        unset($vector['idUtenti']);

        $this->_editUserForm->populate($vector);
    }

    //scarica dal db la lista delle faq
    public function modificacancellautenteAction()
    {
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $utenti = $this->_adminModel->selectUser($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Utenti',$utenti);
    }

    public function aggiungiprodottoAction()
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

        //carico le categorie
        $select = $this->_addProductForm->getElement('idCategoria');

        $rows = $this->_adminModel->getCategorie();
        $categorie = [];

        foreach($rows->toArray() as $row)
        {
            $categorie[$row['id']] = $row['Nome'];
        }

        $select->setMultiOptions($categorie);


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

    }

    public function cancellaprodottoAction()
    {
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteProduct($id);
        }
        //$this->_helper->redirector('modificacancellaprodotto', 'admin'); //(azione, controller)

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

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

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

        $this->_adminModel->updateProduct($values,$id);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('modificacancellaprodotto','admin');

    }

    public function aggiungicomponenteAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addComponentForm;


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

    public function cancellacomponenteAction()
    {
        //recupero l'id del componente da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteComponent($id);
        }

    }

    public function modificacomponenteAction()
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
        $form = $this->_editComponentForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)

            $urlHelper = $this->_helper->getHelper('url');
            $this->_editComponentForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificacomponente',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updatecomponent'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->updateComponent($values,$id);   //Definita in Model/Amministratore.php

    }

    public function aggiungifaqAction()
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
        $form = $this->_addFaqForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('addfaq'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_adminModel->insertFaq($values);   //Definita in Model/Amministratore.php
    }

    public function modificafaqAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
            $this->_logger->log('!isPost',Zend_Log::DEBUG);
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $this->_logger->log('isPost',Zend_Log::DEBUG);
        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_editFaqForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)


            $urlHelper = $this->_helper->getHelper('url');
            $this->_editFaqForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificafaq',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updatefaq'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_adminModel->updateFaq($values,$id);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('modificacancellaprodotto','admin');

    }

    public function cancellafaqAction()
    {
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteFaq($id);
        }
        //$this->_helper->redirector('modificacancellaprodotto', 'admin'); //(azione, controller)

    }

    public function aggiungiutenteAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addUserForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('adduser'); //Esco poi dal controller con return
        }
        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->insertUser($values);   //Definita in Model/Amministratore.php
    }

    public function modificautenteAction()
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
        $form = $this->_editUserForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)


            $urlHelper = $this->_helper->getHelper('url');
            $this->_editUserForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificauser',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updateuser'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->updateUser($values,$id);   //Definita in Model/Amministratore.php
        //$this->_helper->redirector('modificacancellaprodotto','admin');

    }

    public function cancellauserAction()
    {
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteUser($id);
        }

    }

    public function aggiungicentroAction()
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
        $form = $this->_addCentroForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)
            return $this->render('addcentro'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_adminModel->insertCentro($values);   //Definita in Model/Amministratore.php
    }

    public function modificaCentroAction()
    {
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
            $this->_logger->log('!isPost',Zend_Log::DEBUG);
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $this->_logger->log('isPost',Zend_Log::DEBUG);
        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_editCentroForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            $this->_logger->log('!isValid',Zend_Log::DEBUG);
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)


            $urlHelper = $this->_helper->getHelper('url');
            $this->_editCentroForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificacentro',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updatecentro'); //Esco poi dal controller con return
        }

        $this->_logger->log('isValid',Zend_Log::DEBUG);

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();
        $this->_logger->log($values,Zend_Log::DEBUG);

        $this->_adminModel->updateCentro($values,$id);   //Definita in Model/Amministratore.php

    }

    public function cancellacentroAction()
    {
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteCentro($id);
        }

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

    private function getAddComponentForm(){
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addComponentForm = new Application_Form_Admin_Component_Add();
        $this->_addComponentForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungicomponente'
        ),
            'default'
        ));
        return $this->_addComponentForm;
    }

    private function getEditComponentForm()
    {
        $this->_editComponentForm = new Application_Form_Admin_Component_Edit();
        return $this->_editComponentForm;
    }

    private function getAddFaqForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addFaqForm = new Application_Form_Admin_Faq_Add();
        $this->_addFaqForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungifaq'
        ),
            'default'
        ));
        return $this->_addFaqForm;
    }

    private function getEditFaqForm()
    {
        $this->_editFaqForm = new Application_Form_Admin_Faq_Edit();
        return $this->_editFaqForm;
    }

    private function getAddCentroForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addCentroForm = new Application_Form_Admin_Centri_Add();
        $this->_addCentroForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungicentro'
        ),
            'default'
        ));
        return $this->_addCentroForm;
    }

    private function getEditCentroForm()
    {
        $this->_editCentroForm = new Application_Form_Admin_Centri_Edit();
        return $this->_editCentroForm;
    }

    private function getAddUserForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addUserForm = new Application_Form_Admin_User_Add();
        $this->_addUserForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungiutente'
        ),
            'default'
        ));
        return $this->_addUserForm;
    }

    private function getEditUserForm()
    {
        $this->_editUserForm = new Application_Form_Admin_User_Edit();
        return $this->_editUserForm;
    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}

