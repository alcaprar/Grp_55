<?php

class AdminController extends Zend_Controller_Action
{
    protected $_logger;

    protected $_addCategoryForm;
    protected $_editCategoryForm;
    protected $_addTopCategoryForm;
    protected $_editTopCategoryForm;
    protected $_addProductForm;
    protected $_editProductForm;
    protected $_addFaqForm;
    protected $_editFaqForm;
    protected $_addCentroForm;
    protected $_editCentroForm;
    protected $_addComponentForm;
    protected $_editComponentForm;
    protected $_addNtbuForm;
    protected $_editNtbuForm;
    protected $_addUserForm;
    protected $_editUserForm;
    protected $_associateProductForm;

    protected $_authService;

    public function init()
    {
        $this->_helper->layout->setLayout('admin');
        $this->_logger = Zend_Registry::get('log');
        $this->_adminModel = new Application_Model_Admin();


        //assegna alla view le form
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
        $this->view->addCategoryForm = $this->getAddCategoryForm();
        $this->view->editCategoryForm = $this->getEditCategoryForm();
        $this->view->addTopCategoryForm = $this->getAddTopCategoryForm();
        $this->view->editTopCategoryForm = $this->getEditTopCategoryForm();
        $this->view->addNtbuForm = $this->getAddNtbuForm();
        $this->view->editNtbuForm = $this->getEditNtbuForm();


        //serve per l'acl
        $this->_authService = new Application_Service_Auth();
    }

    //azione vuota perchè deve mostrare sola il viewscript associato
    public function indexAction()
    {
        $this->view->headTitle('Home admin');
    }

    //in tutti i controller è stata seguita questa logica:
    //-l'azione con il nome inglese carica la view con la form
    //-l'azione con il nome italiano riceve la post request, valida la form
    //e in caso positivo inserisce i valori nel db altrimenti
    //viene richiamata l'azione inglese che ricarica la form ma con alcuni dati
    //già inseriti e le indicazioni sugli errori

    //<----!!INIZIO GESTIONE PRODOTTI!!---->


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

    public function validateaddproductAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addProductForm = new Application_Form_Admin_Product_Add();
        $response = $addProductForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdateproductAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editProductForm = new Application_Form_Admin_Product_Edit();
        $response = $editProductForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function addproductAction()
    {
        $this->view->headTitle('Aggiungi prodotto');
        //popolo la select con la lista delle categorie
        $select = $this->_addProductForm->getElement('idCategoria');
        $rows = $this->_adminModel->getCategorie();
        $categorie = array();
        foreach($rows->toArray() as $row)
        {
            $categorie[$row['id']] = $row['Nome'];
        }
        $select->setMultiOptions($categorie);


        //creo le checkbox di tutti i componenti
        $multicheckbox = $this->_addProductForm->getElement('Componenti');
        $rows = $this->_adminModel->selectComponent($paged=null,$order=null);
        $componenti = array();
        foreach($rows->toArray() as $row)
        {
            $componenti[$row['id']] = $row['Nome'];
        }
        $multicheckbox->setMultiOptions($componenti);

        //creo le checkbox di tutte le ntbu
        $multicheckbox2 = $this->_addProductForm->getElement('Ntbu');
        $rows = $this->_adminModel->selectNtbu($paged=null,$order=null);
        $ntbu = array();
        foreach($rows->toArray() as $row)
        {
            $ntbu[$row['id']] = $row['Nome'];
        }
        $multicheckbox2->setMultiOptions($ntbu);
    }

    public function updateproductAction()
    {
        $this->view->headTitle('Aggiorna prodotto');
        //recupero l'id del prodotto da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellaprodotto', 'admin');
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

        //recupero i componenti del prodotto
        $components=array();
        $temp = $this->_adminModel->getComponentsByProd($id);
        foreach($temp as $t)
        {
            $component = $this->_adminModel->getComponentById($t->idComponente);
            $components[] = $component['id'];
        }
        $this->_logger->log($components,Zend_Log::DEBUG);

        //recupero le ntbu del prodotto
        $ntbus=array();
        $temp = $this->_adminModel->getNtbuByProd($id);
        foreach($temp as $t)
        {
            $ntbu = $this->_adminModel->getNtbuById($t->idNTBU);
            $ntbus[] = $ntbu['id'];
        }
        $this->_logger->log($ntbus,Zend_Log::DEBUG);


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

        //creo le checkbox dei componenti
        $multicheckbox = $this->_editProductForm->getElement('Componenti');
        $rows = $this->_adminModel->selectComponent($paged=null,$order=null);
        $componenti = array();
        foreach($rows->toArray() as $row)
        {
            $componenti[$row['id']] = $row['Nome'];
        }
        $multicheckbox->setMultiOptions($componenti);


        //creo le checkbox di tutte le ntbu
        $multicheckbox2 = $this->_editProductForm->getElement('Ntbu');
        $rows = $this->_adminModel->selectNtbu($paged=null,$order=null);
        $ntbu = array();
        foreach($rows->toArray() as $row)
        {
            $ntbu[$row['id']] = $row['Nome'];
        }
        $multicheckbox2->setMultiOptions($ntbu);

        //richiamo la pagina dell'inserimento dei prodotti.
        //con return esco dal controller

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);
        unset($vector['Foto']);
        unset($vector['idCategoria']);

        //popolo la form con i campi recuperati dal db
        $this->_editProductForm->populate($vector);
        $multicheckbox->setValue($components);
        $multicheckbox2->setValue($ntbus);
    }

    //mostra la lista dei prodotti presenti nel database
    //è possibile cancellare e/o modificare ogni prodotto
    public function modificacancellaprodottoAction()
    {
        $this->view->headTitle('Gestisci prodotti');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $prodotti = $this->_adminModel->selectProduct($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Prodotti',$prodotti);

    }

        //azione richiamata al click di inserisci prodotto
    public function aggiungiprodottoAction()
    {
        $this->view->headTitle('Aggiungi prodotto');
        //questa azione deve essere richiamata solo da richieste post.
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        $form = $this->_addProductForm;

        //valida la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //popolo la select con la lista delle categorie
            $select = $this->_addProductForm->getElement('idCategoria');
            $rows = $this->_adminModel->getCategorie();
            $categorie = array();
            foreach($rows->toArray() as $row)
            {
                $categorie[$row['id']] = $row['Nome'];
            }
            $select->setMultiOptions($categorie);

            //creo le checkbox dei componenti
            $multicheckbox = $this->_addProductForm->getElement('Componenti');
            $rows = $this->_adminModel->selectComponent($paged=null,$order=null);
            $componenti = array();
            foreach($rows->toArray() as $row)
            {
                $componenti[$row['id']] = $row['Nome'];
            }
            $multicheckbox->setMultiOptions($componenti);

            //creo le checkbox di tutte le ntbu
            $multicheckbox2 = $this->_addProductForm->getElement('Ntbu');
            $rows = $this->_adminModel->selectNtbu($paged=null,$order=null);
            $ntbu = array();
            foreach($rows->toArray() as $row)
            {
                $ntbu[$row['id']] = $row['Nome'];
            }
            $multicheckbox2->setMultiOptions($ntbu);

            //richiamo la pagina dell'inserimento dei prodotti.
            //con return esco dal controller
            return $this->render('addproduct');
        }

        //recupero i valori da inserire nel db
        $values = $form->getValues();
        $componenti = $values['Componenti'];
        $ntbus = $values['Ntbu'];
        unset($values['Componenti']);
        unset($values['Ntbu']);

        //inserisco nel db il prodotto e recupero il suo id per poi inserire i componenti
        $idProduct = $this->_adminModel->insertProduct($values);
        foreach($componenti as $componente)
        {
            $this->_adminModel->associateComponent($idProduct,$componente);
        }
        foreach ($ntbus as $ntbu)
        {
            $this->_adminModel->associateNtbu($idProduct,$ntbu);
        }
    }

    public function cancellaprodottoAction()
    {
        $this->view->headTitle('Cancella prodotto');
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteProduct($id);
        }
    }

    public function modificaprodottoAction()
    {
        $this->view->headTitle('Aggiorna prodotto');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $form = $this->_editProductForm;

        //valida la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //riassocio l'azione al controller
            $urlHelper = $this->_helper->getHelper('url');
            $this->_editProductForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificaprodotto',
                'id' => $id
            ),
                'default'
            ));

            //creo le checkbox dei componenti
            $multicheckbox = $this->_editProductForm->getElement('Componenti');
            $rows = $this->_adminModel->selectComponent($paged=null,$order=null);
            $componenti = array();
            foreach($rows->toArray() as $row)
            {
                $componenti[$row['id']] = $row['Nome'];
            }
            $multicheckbox->setMultiOptions($componenti);

            //creo le checkbox di tutte le ntbu
            $multicheckbox2 = $this->_editProductForm->getElement('Ntbu');
            $rows = $this->_adminModel->selectNtbu($paged=null,$order=null);
            $ntbu = array();
            foreach($rows->toArray() as $row)
            {
                $ntbu[$row['id']] = $row['Nome'];
            }
            $multicheckbox2->setMultiOptions($ntbu);

            //richiamo la pagina dell'inserimento dei prodotti.
            //con return esco dal controller

            //richiamo la pagina della modifica del prodotto
            //con return esco dal controller
            return $this->render('updateproduct');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();
        $componenti = $values['Componenti'];
        $ntbu = $values['Ntbu'];
        $this->_logger->log($values,Zend_Log::DEBUG);
        unset($values['Componenti']);
        unset($values['Ntbu']);

        $this->_logger->log($componenti,Zend_Log::DEBUG);
        $this->_logger->log($ntbu,Zend_Log::DEBUG);

        $this->_adminModel->updateProduct($values,$id);

        $this->_adminModel->updateComposition($componenti,$id);
        $this->_adminModel->updateNtbuprodotti($ntbu,$id);
    }


    //<----!!FINE GESTIONE PRODOTTI!!---->



    //<----!!INIZIO GESTIONE FAQ!!---->

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

    public function validateaddfaqAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addFaqForm = new Application_Form_Admin_Faq_Add();
        $response = $addFaqForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdatefaqAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editFaqForm = new Application_Form_Admin_Faq_Edit();
        $response = $editFaqForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function addfaqAction()
    {
        $this->view->headTitle('Aggiungi FAQ');
    }

    public function updatefaqAction()
    {
        $this->view->headTitle('Aggiorna FAQ');
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista delle faq da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellafaq', 'admin');
        }

        //associo l'azione alla form
        $urlHelper = $this->_helper->getHelper('url');
        $this->_editFaqForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificafaq',
            'id' => $id
        ),
            'default'
        ));

        //recupero la faq e la associo alla view
        $row = $this->_adminModel->getFaqById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        //rimuovo i campi che non ci sono nella form e popolo la form
        unset($vector['id']);
        $this->_editFaqForm->populate($vector);
    }


    public function modificacancellafaqAction()
    {
        $this->view->headTitle('Gestisci FAQ\'s');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $faq = $this->_adminModel->selectFaq($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Faq',$faq);
    }

    public function aggiungifaqAction()
    {
        $this->view->headTitle('Aggiungi FAQ');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        $form = $this->_addFaqForm;

        //valida la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //richiamo la pagina dell'inserimento della faq
            //con return esco dal controller
            return $this->render('addfaq');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();
        $this->_adminModel->insertFaq($values);
    }

    public function modificafaqAction()
    {
        $this->view->headTitle('Aggiorna FAQ');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('logout', 'admin');
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $form = $this->_editFaqForm;

        //valida la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //riassocio l'azione alla form
            $urlHelper = $this->_helper->getHelper('url');
            $this->_editFaqForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificafaq',
                'id' => $id
            ),
                'default'
            ));

            //richiamo la pagina dell'inserimento della faq
            //con return esco dal controller
            return $this->render('updatefaq');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();
        $this->_adminModel->updateFaq($values,$id);
    }

    public function cancellafaqAction()
    {
        $this->view->headTitle('Cancella FAQ');
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteFaq($id);
        }
    }

    //<----!!FINE GESTIONE FAQ!!---->



    //<----!!INIZIO GESTIONE CENTRI ASSISTENZA!!-->

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

    public function validateaddcentroAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addCentroForm = new Application_Form_Admin_Centri_Add();
        $response = $addCentroForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdatecentroAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editCentroForm = new Application_Form_Admin_Centro_Edit();
        $response = $editCentroForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function addcentroAction()
    {
        $this->view->headTitle('Aggiungi centro assistenza');
    }

    public function updatecentroAction()
    {
        $this->view->headTitle('Aggiorna centro assistenza');
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellacentro', 'admin');
        }

        //associo l'azione alla form
        $urlHelper = $this->_helper->getHelper('url');
        $this->_editCentroForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificacentro',
            'id' => $id
        ),
            'default'
        ));

        //recupero il centro e popolo la form
        $row = $this->_adminModel->getCentroById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);
        unset($vector['Latitudine']);
        unset($vector['Longitudine']);

        $this->_editCentroForm->populate($vector);
    }


    public function modificacancellacentroAction()
    {
        $this->view->headTitle('Gestisci centri assistenza');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $centro = $this->_adminModel->selectCentro($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Centro',$centro);
    }

    public function aggiungicentroAction()
    {
        $this->view->headTitle('Aggiungi centro assistenza');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        $form = $this->_addCentroForm;

        //valido la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //richiamo la pagina dell'inserimento del centro
            //con return esco dal controller
            return $this->render('addcentro');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();

        //recupero lat e long che servono per la mappa
        $urlencodedAddress = urlencode($values['Indirizzo']);
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $urlencodedAddress . "&sensor=false";
        $ch = curl_init($details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoloc = json_decode(curl_exec($ch), true);
        $values['Latitudine'] = $geoloc['results'][0]['geometry']['location']['lat'];
        $values['Longitudine'] = $geoloc['results'][0]['geometry']['location']['lng'];

        $this->_adminModel->insertCentro($values);
    }

    public function modificacentroAction()
    {
        $this->view->headTitle('Aggiorna centro assistenza');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $form = $this->_editCentroForm;


        //valido la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            $urlHelper = $this->_helper->getHelper('url');
            $this->_editCentroForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificacentro',
                'id' => $id
            ),
                'default'
            ));

            //richiamo la pagina dell'aggiornamento del centro
            //con return esco dal controller
            return $this->render('updatecentro');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();

        //recupero lat e long che servono per la mappa
        $urlencodedAddress = urlencode($values['Indirizzo']);
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $urlencodedAddress . "&sensor=false";
        $ch = curl_init($details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoloc = json_decode(curl_exec($ch), true);
        $values['Latitudine'] = $geoloc['results'][0]['geometry']['location']['lat'];
        $values['Longitudine'] = $geoloc['results'][0]['geometry']['location']['lng'];

        $this->_adminModel->updateCentro($values,$id);

    }

    public function cancellacentroAction()
    {
        $this->view->headTitle('Cancella centro assistenza');
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteCentro($id);
        }

    }

    //<----!!FINE GESTIONE CENTRI ASSISTENZA!!---->


    //<----!!INIZIO GESTIONE COMPONENTI!!--->

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

    public function validateaddcomponentAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addComponentForm = new Application_Form_Admin_Component_Add();
        $response = $addComponentForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdatecomponentAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editComponentForm = new Application_Form_Admin_Component_Edit();
        $response = $editComponentForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }


    public function addcomponentAction()
    {
        $this->view->headTitle('Aggiungi componente');
    }

    public function updatecomponentAction()
    {
        $this->view->headTitle('Aggiorna componente');
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
        $row = $this->_adminModel->getComponentByIdFind($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        //se la foto non è stata inserita aggiungo l'elemento alla form
        if($vector['Foto']=='' || is_null($vector['Foto'])){
            $this->_editComponentForm->addElement('file', 'Foto', array(
                'label' => 'Immagine',
                'destination' => APPLICATION_PATH . '/../public/images/products',
                'validators' => array(
                    array('Count', false, 1),
                    array('Size', false, 102400),
                    array('Extension', false, array('jpg', 'gif'))),
            ));
        }

        //rimuovo i campi che non ci sono nella form e popola la form
        unset($vector['id']);
        unset($vector['Foto']);

        $this->_editComponentForm->populate($vector);
    }


    public function modificacancellacomponenteAction()
    {
        $this->view->headTitle('Gestisci componenti');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $componenti = $this->_adminModel->selectComponent($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Componenti',$componenti);
    }

    public function aggiungicomponenteAction()
    {
        $this->view->headTitle('Aggiungi componente');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        $form = $this->_addComponentForm;

        //valido la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //richiamo la pagina dell'aggiunta del componente
            //con return esco dal controller
            return $this->render('addcomponent');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();

        $this->_adminModel->insertComponent($values);
    }

    public function cancellacomponenteAction()
    {
        $this->view->headTitle('Cancella componente');
        //recupero l'id del componente da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteComponent($id);
        }

    }

    public function modificacomponenteAction()
    {
        $this->view->headTitle('Aggiorna componente');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $form = $this->_editComponentForm;


        //valido la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //riassocio l'azione alla form
            $urlHelper = $this->_helper->getHelper('url');
            $this->_editComponentForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificacomponente',
                'id' => $id
            ),
                'default'
            ));

            //richiamo la pagina della modifica del componente
            //con return esco dal controller
            return $this->render('updatecomponent');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();

        $this->_adminModel->updateComponent($values,$id);   //Definita in Model/Amministratore.php

    }

    //<----!!FINE GESTIONE COMPONENTI!!---->

    //<----!!INIZIO GESTIONE NTBU!!--->

    private function getAddNtbuForm(){
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addNtbuForm = new Application_Form_Admin_Ntbu_Add();
        $this->_addNtbuForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungintbu'
        ),
            'default'
        ));
        return $this->_addNtbuForm;
    }

    private function getEditNtbuForm()
    {
        $this->_editNtbuForm = new Application_Form_Admin_Ntbu_Edit();
        return $this->_editNtbuForm;
    }

    public function validateaddntbuAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addNtbuForm = new Application_Form_Admin_Ntbu_Add();
        $response = $addNtbuForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdatentbuAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editNtbuForm = new Application_Form_Admin_Ntbu_Edit();
        $response = $editNtbuForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function addntbuAction()
    {
        $this->view->headTitle('Aggiungi nota tecnica di buon uso');
    }

    public function updatentbuAction()
    {
        $this->view->headTitle('Aggiorna nota tecnica di buon uso');
        //recupero l'id della ntbu da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei componenti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellantbu', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editNtbuForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificantbu',
            'id' => $id
        ),
            'default'
        ));

        //recupero la ntbu
        $row = $this->_adminModel->getNtbuById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }


        //rimuovo i campi che non ci sono nella form e popola la form
        unset($vector['id']);

        $this->_editNtbuForm->populate($vector);
    }

    public function modificacancellantbuAction()
    {
        $this->view->headTitle('Gestisci note tecniche di buon uso');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $ntbu = $this->_adminModel->selectNtbu($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Ntbu',$ntbu);
    }

    public function aggiungintbuAction()
    {
        $this->view->headTitle('Aggiungi nota tecnica di buon uso');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        $form = $this->_addNtbuForm;

        //valido la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //richiamo la pagina dell'aggiunta del componente
            //con return esco dal controller
            return $this->render('addntbu');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();

        $this->_adminModel->insertNtbu($values);
    }

    public function cancellaNtbuAction()
    {
        $this->view->headTitle('Cancella nota tecnica di buon uso');
        //recupero l'id del componente da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteNtbu($id);
        }

    }

    public function modificantbuAction()
    {
        $this->view->headTitle('Aggiorna nota tecnica di buon uso');
        //questa azione deve essere richiamata solo da richieste post
        //se non è una post faccio il redirect alla index
        if (!$this->getRequest()->isPost()) {
            $this->_helper->redirector('index', 'admin');
        }

        //recupero l'id
        $id = intval($this->_request->getParam('id'));

        $form = $this->_editNtbuForm;


        //valido la form
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            //riassocio l'azione alla form
            $urlHelper = $this->_helper->getHelper('url');
            $this->_editNtbuForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificantbu',
                'id' => $id
            ),
                'default'
            ));

            //richiamo la pagina della modifica del componente
            //con return esco dal controller
            return $this->render('updatentbu');
        }

        //recupero i valori e li inserisco nel db
        $values = $form->getValues();

        $this->_adminModel->updateNtbu($values,$id);   //Definita in Model/Amministratore.php

    }

    //<----!!FINE GESTIONE NTBU!!---->


    //<-----!!INIZIO GESTIONE UTENTI------>

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

    public function validateadduserAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addUserForm = new Application_Form_Admin_User_Add();
        $response = $addUserForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdateuserAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editUserForm = new Application_Form_Admin_User_Edit();
        $response = $editUserForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    //carica la view per l'inserimento di un utente
    public function adduserAction()
    {
        $this->view->headTitle('Aggiungi utente');
        $select = $this->_addUserForm->getElement('centri');

        $rows = $this->_adminModel->selectCentro($paged=null,$order=null);
        $centri = array();

        foreach($rows->toArray() as $row)
        {
            $centri[$row['id']] = $row['Nome'];
        }

        $select->setMultiOptions($centri);

        //creo le checkbox di tutte le categorie
        $multicheckbox = $this->_addUserForm->getElement('Categorie');
        $rows = $this->_adminModel->getCategorie();
        $categorie = array();
        foreach($rows->toArray() as $row)
        {
            $categorie[$row['id']] = $row['Nome'];
        }
        $multicheckbox->setMultiOptions($categorie);
    }

    //popola la form per la modifica
    public function updateuserAction()
    {
        $this->view->headTitle('Aggiorna utente');
        $select = $this->_editUserForm->getElement('centri');

        $rows = $this->_adminModel->selectCentro($paged=null,$order=null);
        $centri = array();

        foreach($rows->toArray() as $row)
        {
            $centri[$row['id']] = $row['Nome'];
        }

        $select->setMultiOptions($centri);

        //creo le checkbox di tutte le categorie
        $multicheckbox = $this->_editUserForm->getElement('Categorie');
        $rows = $this->_adminModel->getCategorie();
        $categorie = array();
        foreach($rows->toArray() as $row)
        {
            $categorie[$row['id']] = $row['Nome'];
        }
        $multicheckbox->setMultiOptions($categorie);


        //recupero l'id dell'utente da modificare
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

        //recupero le categorie del prodotto
        $categorie=array();
        $temp = $this->_adminModel->getCatByUser($id);
        foreach($temp as $t)
        {
            $categoria = $this->_adminModel->getCatById($t->idCategoria);
            $categorie[] = $categoria['id'];
        }
        $this->_logger->log($categorie,Zend_Log::DEBUG);

        $this->view->assign('vector',$vector);


        //rimuovo i campi che non ci sono nella form
        unset($vector['idUtenti']);

        $this->_editUserForm->populate($vector);
        $multicheckbox->setValue($categorie);
    }

        //scarica dal db la lista delle faq
    public function modificacancellautenteAction()
    {
        $this->view->headTitle('Gestisci utenti');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $utenti = $this->_adminModel->selectUser($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Utenti',$utenti);
    }

    public function aggiungiutenteAction()
    {
        $this->view->headTitle('Aggiungi utente');
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

            $select = $this->_addUserForm->getElement('centri');

            $rows = $this->_adminModel->selectCentro($paged=null,$order=null);
            $centri =array();

            foreach($rows->toArray() as $row)
            {
                $centri[$row['id']] = $row['Nome'];
            }

            $select->setMultiOptions($centri);

            //creo le checkbox di tutte le categorie
            $multicheckbox = $this->_addUserForm->getElement('Categorie');
            $rows = $this->_adminModel->getCategorie();
            $categorie = array();
            foreach($rows->toArray() as $row)
            {
                $categorie[$row['id']] = $row['Nome'];
            }
            $multicheckbox->setMultiOptions($categorie);

            return $this->render('adduser'); //Esco poi dal controller con return
        }
        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_logger->log($values,Zend_Log::DEBUG);

        $centro = $values['centri'];
        $categorie = $values['Categorie'];
        unset($values['centri']);
        unset($values['Categorie']);

        $idUser = $this->_adminModel->insertUser($values);
        if($values['Ruolo']=='tec')
        {
            $this->_adminModel->insertAppartenenza($centro, $idUser);
        }else
        {
            foreach ($categorie as $categoria)
            {
                $this->_adminModel->associateStaffCategoria($idUser,$categoria);
            }
        }


    }

    public function modificautenteAction()
    {
        $this->view->headTitle('Aggiorna utente');
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
                'action' => 'modificautente',
                'id' => $id
            ),
                'default'
            ));

            $select = $this->_editUserForm->getElement('centri');

            $rows = $this->_adminModel->selectCentro($paged=null,$order=null);
            $centri =array();

            foreach($rows->toArray() as $row)
            {
                $centri[$row['id']] = $row['Nome'];
            }

            $select->setMultiOptions($centri);

            //creo le checkbox di tutte le categorie
            $multicheckbox = $this->_editUserForm->getElement('Categorie');
            $rows = $this->_adminModel->getCategorie();
            $categorie = array();
            foreach($rows->toArray() as $row)
            {
                $categorie[$row['id']] = $row['Nome'];
            }
            $multicheckbox->setMultiOptions($categorie);

            return $this->render('updateuser'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $centro = $values['centri'];
        $categorie = $values['Categorie'];
        unset($values['centri']);
        unset($values['Categorie']);

        if($values['Ruolo']=='tec')
        {
            $this->_adminModel->updateAppartenenza($centro, $id);
        }else{
            $this->_adminModel->updateStaffCategorie($categorie,$id);
        }

        $this->_adminModel->updateUser($values,$id);

    }

    public function cancellauserAction()
    {
        $this->view->headTitle('Cancella utente');
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteUser($id);
        }

    }

    //<-----------!!FINE GESTIONE UTENTI------------->



    //<------------!!INIZIO GESTIONE CATEGORIE TOP-------->

    private function getAddTopCategoryForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addTopCategoryForm = new Application_Form_Admin_TopCategory_Add();
        $this->_addTopCategoryForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungitopcategoria'
        ),
            'default'
        ));
        return $this->_addTopCategoryForm;
    }

    private function getEditTopCategoryForm()
    {
        $this->_editTopCategoryForm = new Application_Form_Admin_TopCategory_Edit();
        return $this->_editTopCategoryForm;
    }

    public function validateaddtopcategoryAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addTopCategoryForm = new Application_Form_Admin_TopCategory_Add();
        $response = $addTopCategoryForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdatetopcategoryAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editTopCategoryForm = new Application_Form_Admin_TopCategory_Edit();
        $response = $editTopCategoryForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }


    public function addtopcategoryAction()
    {
        $this->view->headTitle('Aggiungi categoria top');
    }

    public function updatetopcategoryAction()
    {
        $this->view->headTitle('Aggiorna categoria top');
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellatopcategoria', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editUserForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificatopcategoria',
            'id' => $id
        ),
            'default'
        ));

        $vector = array();
        //recupero l'utente
        $row = $this->_adminModel->getTopCatById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }


        $this->view->assign('vector',$vector);


        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);

        $this->_editTopCategoryForm->populate($vector);
    }

    public function modificacancellatopcategoriaAction()
    {
        $this->view->headTitle('Gestisci categorie top');
        $categorie = $this->_adminModel->getTopCats();

        //assegno le variabili alla view
        $this->view->assign('TopCategorie',$categorie);
    }

    public function aggiungitopcategoriaAction()
    {
        $this->view->headTitle('Aggiungi categoria top');
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addTopCategoryForm;

        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            return $this->render('addtopcategory'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $idIns = $this->_adminModel->insertTopCategory($values);   //Definita in Model/Amministratore.php
    }

    public function modificatopcategoriaAction()
    {
        $this->view->headTitle('Aggiorna categoria top');
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
        $form = $this->_editTopCategoryForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)

            $urlHelper = $this->_helper->getHelper('url');
            $this->_editComponentForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificatopcategoria',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updatetopcategory'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->updateTopCategory($values,$id);   //Definita in Model/Amministratore.php
    }

    public function eliminatopcategoriaAction()
    {
        $this->view->headTitle('Cancella categoria top');
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteTopCategory($id);
        }
    }

    //<------!!FINE GESTIONE CATEGORIE TOP------------->



    //<------!!INIZIO GESTIONE CATEGORIE--------------->

    private function getAddCategoryForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_addCategoryForm = new Application_Form_Admin_Category_Add();
        $this->_addCategoryForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'aggiungicategoria'
        ),
            'default'
        ));
        return $this->_addCategoryForm;
    }

    private function getEditCategoryForm()
    {
        $this->_editCategoryForm = new Application_Form_Admin_Category_Edit();
        return $this->_editCategoryForm;
    }

    public function validateaddcategoryAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $addCategoryForm = new Application_Form_Admin_Category_Add();
        $response = $addCategoryForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function validateupdatecategoryAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $editCategoryForm = new Application_Form_Admin_Category_Edit();
        $response = $editCategoryForm->processAjax($_POST);
        if ($response !== null) {
            $this->getResponse()->setHeader('Content-type', 'application/json')->setBody($response);
        }
    }

    public function addcategoryAction()
    {
        $this->view->headTitle('Aggiungi categoria');
        $select = $this->_addCategoryForm->getElement('Tipo');

        $rows = $this->_adminModel->getTopCats();
        $topcategorie =array();

        foreach($rows->toArray() as $row)
        {
            $topcategorie[$row['id']] = $row['Nome'];
        }

        $select->setMultiOptions($topcategorie);
    }

    public function updatecategoryAction()
    {
        $this->view->headTitle('Aggiorna categoria');
        //recupero l'id della faq da modificare
        $id = intval($this->_request->getParam('id'));

        //se l'id non è valido ritorno alla lista dei prodotti da modificare
        if($id == null){
            $this->_helper->redirector('modificacancellacategoria', 'admin');
        }

        $urlHelper = $this->_helper->getHelper('url');
        $this->_editCategoryForm->setAction($urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'modificacategoria',
            'id' => $id
        ),
            'default'
        ));

        $vector = array();
        //recupero l'utente
        $row = $this->_adminModel->getCatById($id);
        foreach($row as $key=>$value) {
            $vector[$key]=$value;
        }

        //rimuovo i campi che non ci sono nella form
        unset($vector['id']);

        $this->_editCategoryForm->populate($vector);

        $select = $this->_editCategoryForm->getElement('Tipo');

        $rows = $this->_adminModel->getTopCats();
        $topcategorie =array();

        foreach($rows->toArray() as $row)
        {
            $topcategorie[$row['id']] = $row['Nome'];
        }

        $select->setMultiOptions($topcategorie);
    }

    public function modificacancellacategoriaAction()
    {
        $this->view->headTitle('Gestisci categorie');

        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $categorie = $this->_adminModel->getCats($paged, $where =null, $order=null );

        //assegno le variabili alla view
        $this->view->assign('Categorie',$categorie);

    }

    public function aggiungicategoriaAction()
    {
        $this->view->headTitle('Aggiungi categoria');
        //Si attiva solo se la richiesta che ha attivato questa azione è di tipo post
        //Se non lo è...
        if (!$this->getRequest()->isPost()) {
            //...ritorna alla home page dell'admin (actionIndex)
            $this->_helper->redirector('logout', 'admin');        //Specificando solo il controller (index) prende come azione di default indexAction
        }

        //Il server ha ricreato l'applicazione avendo inviato il form,
        // devo incrociare i dati che mi sono arrivati, perciò devo reistanziare il form
        $form = $this->_addCategoryForm;

        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');

            $select = $this->_addCategoryForm->getElement('Tipo');

            $rows = $this->_adminModel->getTopCats();
            $topcategorie =array();

            foreach($rows->toArray() as $row)
            {
                $topcategorie[$row['id']] = $row['Nome'];
            }

            $select->setMultiOptions($topcategorie);

            return $this->render('addcategory'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $idIns = $this->_adminModel->insertCategory($values);   //Definita in Model/Amministratore.php
    }

    public function modificacategoriaAction()
    {
        $this->view->headTitle('Aggiorna categoria');
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
        $form = $this->_editCategoryForm;


        //Fa un incrocio fra $post e i campi ricevuti dalla form, restituisce true se sono compatibili, false altrimenti
        if (!$form->isValid($_POST)) {
            $form->setDescription('ATTENZIONE: alcuni dati inseriti sono errati!');
            //Se non è stato validato rivisualizzo il risultato dell'azione registrautente
            //Rivisualizzo quindi la form popolata (Aggiungendo però i messaggi di errore!)

            $urlHelper = $this->_helper->getHelper('url');
            $this->_editComponentForm->setAction($urlHelper->url(array(
                'controller' => 'admin',
                'action' => 'modificacategoria',
                'id' => $id
            ),
                'default'
            ));

            $form->populate($_POST);
            return $this->render('updatecategory'); //Esco poi dal controller con return
        }

        //Con getValues estraggo tutti i valori validati
        //Diventa un array di coppie nome-valori pronto per essere scritto sul DB se ho associato correttamente i nomi
        $values = $form->getValues();

        $this->_adminModel->updateCategory($values,$id);   //Definita in Model/Amministratore.php
    }

    public function eliminacategoriaAction()
    {
        $this->view->headTitle('Cancella categoria');
        //recupero l'id del prodotto da rimuovere
        $id = intval($this->_request->getParam('id'));

        if ($id !== 0) {
            $this->_adminModel->deleteCategory($id);
        }
    }

    //<----------!!FINE GESTIONE CATEGORIE---------->


    public function redirectorurlcercaAction()
    {
        $request = $this->getRequest();

        //arrivata una richiesta di cerca
        if ($request->isPost()) {
            $this->_redirector = $this->_helper->getHelper('Redirector');

            $post = $request->getPost();

            if(!is_null($post['prodotti']))
            {
                $query = $post['prodotti']['query'];
                $this->_redirector->gotoSimple('cerca', 'admin', null,array('prodotti' => $query));
            }elseif(!is_null($post['componenti']))
            {
                $query = $post['componenti']['query'];
                $this->_redirector->gotoSimple('cerca', 'admin', null,array('componenti' => $query));
            }elseif(!is_null($post['ntbu']))
            {
                $query = $post['ntbu']['query'];
                $this->_redirector->gotoSimple('cerca', 'admin', null,array('ntbu' => $query));
            }elseif(!is_null($post['faq']))
            {
                $query = $post['faq']['query'];
                $this->_redirector->gotoSimple('cerca', 'admin', null,array('faq' => $query));
            }elseif(!is_null($post['utenti']))
            {
                $query = $post['utenti']['query'];
                $this->_redirector->gotoSimple('cerca', 'admin', null,array('utenti' => $query));
            }elseif(!is_null($post['centri'])) {
                $query = $post['centri']['query'];
                $this->_redirector->gotoSimple('cerca', 'admin', null,array('centri' => $query));
            }else{
                $this->_redirector->gotoSimple('index', 'admin', null);
            }
        }
    }

    public function cercaAction()
    {
        $this->view->headTitle('Risultati ricerca');
        //recupero i parametri
        if(!is_null($query=$this->_getParam('prodotti', null)))
        {
            if(strpos($query, '*') === 0){
                $query = str_replace($query, '', 0, strlen('*'));
            }

            $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $prodotti = $this->_adminModel->getProdByName($query, $page, $order=null);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Prodotti' => $prodotti
                )
            );
            return $this->render('modificacancellaprodotto');
        }
        elseif(!is_null($query=$this->_getParam('componenti', null)))
        {
            if(strpos($query, '*') === 0){
                $query = str_replace($query, '', 0, strlen('*'));
            }

            $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $componenti = $this->_adminModel->getCompByName($query, $page, $order=null);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Componenti' => $componenti
                )
            );
            return $this->render('modificacancellacomponente');
        }
        elseif(!is_null($query=$this->_getParam('ntbu', null)))
        {
            if(strpos($query, '*') === 0){
                $query = str_replace($query, '', 0, strlen('*'));
            }

            $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $ntbu = $this->_adminModel->getNtbuByName($query, $page, $order=null);
            $this->_logger->log($ntbu,Zend_Log::DEBUG);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Ntbu' => $ntbu
                )
            );
            return $this->render('modificacancellantbu');
        }
        elseif(!is_null($query=$this->_getParam('faq', null)))
        {
            if(strpos($query, '*') === 0){
                $query = str_replace($query, '', 0, strlen('*'));
            }

            $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $faq = $this->_adminModel->getFaqByName($query, $page, $order=null);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Faq' => $faq
                )
            );
            return $this->render('modificacancellafaq');
        }
        elseif(!is_null($query=$this->_getParam('utenti', null)))
        {
            if(strpos($query, '*') === 0){
                $query = str_replace($query, '', 0, strlen('*'));
            }

            $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $utenti = $this->_adminModel->getUserByQuery($query, $page, $order=null);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Utenti' => $utenti
                )
            );
            return $this->render('modificacancellautente');
        }
        elseif(!is_null($query=$this->_getParam('centri', null)))
        {
            if(strpos($query, '*') === 0){
                $query = str_replace($query, '', 0, strlen('*'));
            }

            $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $centri = $this->_adminModel->getCentriByName($query, $page, $order=null);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Centro' => $centri
                )
            );
            return $this->render('modificacancellacentro');
        }
        else
        {
            $this->_redirector = $this->_helper->getHelper('Redirector');
            $this->_redirector->gotoSimple('index', 'admin', null);
        }

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }


    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}

