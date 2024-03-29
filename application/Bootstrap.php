<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $_view;
    protected $_logger;
    private $_acl = null;
    private $_auth = null;
    private $_role = null;

    //lo stream del log viene inviato a firebug (su mozilla)
    protected function _initLogging()
    {
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);

        Zend_Registry::set('log', $logger);

        $this->_logger = $logger;
        $this->_logger->log('Bootstrap', Zend_Log::DEBUG);

    }

    protected function _initRequest()
        // Aggiunge un'istanza di Zend_Controller_Request_Http nel Front_Controller
        // che permette di utilizzare l'helper baseUrl() nel Bootstrap.php
    {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        $request = new Zend_Controller_Request_Http();
        $front->setRequest($request);
    }

    protected function _initViewSettings()
    {
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'it-IT');
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/bootstrap.min.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/font-awesome.min.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/prettyPhoto.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/price-range.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/animate.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/responsive.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/main.css'));
        $this->_view->headScript()->appendFile('https://code.jquery.com/jquery-1.10.2.js');
        $this->_view->headScript()->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
        $this->_view->headScript()->appendFile($this->_view->baseUrl('js/functions.js'));
        $this->_view->headTitle('Grp_55 TecWeb')
            ->setSeparator(' - ');
    }

    protected function _initDefaultModuleAutoloader()
    {
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('App_');
        $this->getResourceLoader()
            ->addResourceType('modelResource','models/resources','Resource');
    }

    protected function _initAutoload()
    {
        $this->_acl = new Application_Model_Acl();
        $this->_auth = Zend_Auth::getInstance();
        $this->_role = !$this->_auth->hasIdentity() ? 'unregistered' : $this->_auth->getIdentity()->Ruolo;
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new App_Controller_Plugin_Acl($this->_acl));
    }

    protected function _initNavigationHeader()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');

        $view = $layout->getView();

        $layout->Ruolo = $this->_role;

        $navMainArray = array(
            array(
                'controller' => 'public',
                'action' => 'index',
                'label' =>'Home',
                'resource' => 'public',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'who'),
                'label'=>'Chi siamo',
                'resource' => 'public',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'where'),
                'label'=>'Dove siamo',
                'resource' => 'public',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'faq'),
                'label'=>'FAQ',
                'resource' => 'public',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'contact'),
                'label'=>'Contatti',
                'resource' => 'public',
                'privilege' => 'index'
            )
        );

        $navTopArray = array(
            array(
                'controller' => 'admin',
                'action' => 'index',
                'label' => 'Home admin',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller' => 'staff',
                'action' => 'index',
                'label' => 'Home staff',
                'resource' => 'staff',
                'privilege' => 'index'
            ),
            array(
                'controller' => 'tecnico',
                'action' => 'index',
                'label' => 'Home tecnico',
                'resource' => 'tecnico',
                'privilege' => 'index'
            ),
            array(
                'controller' => 'public',
                'action' => 'login',
                'label' => 'Login',
                'resource' => 'public',
                'privilege' => 'login'
            ),
            array(
                'controller' => 'public',
                'action' => 'logout',
                'label' => 'Logout',
                'resource' => 'public',
                'privilege' => 'logout'
            ),
        );

        $configMain = new Zend_Config($navMainArray);
        $configTop = new Zend_Config($navTopArray);

        $navigationMain = new Zend_Navigation($configMain);
        $navigationTop = new Zend_Navigation($configTop);


        $view->navigation($navigationMain)
            ->setAcl($this->_acl)
            ->setRole($this->_role);
        $view->navigation($navigationTop)->setAcl($this->_acl)
            ->setRole($this->_role);

        $layout->mainMenu = $navigationMain;
        $layout->topMenu = $navigationTop;
    }

    protected function _initNavigationFooter()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');

        $navServiziArray = array(
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'contact'),
                'label'=>'Contatti',
                'resource' => 'public',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'faq'),
                'label'=>'FAQ\'s',
                'resource' => 'public',
                'privilege' => 'index'
            ),
        );

        $navSuDiNoiArray = array(
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'copyright'),
                'label'=>'Copyright',
                'resource' => 'public',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'public',
                'action'=>'viewstatic',
                'params'=>array('page'=>'who'),
                'label'=>'Informazioni sull\'azienda',
                'resource' => 'public',
                'privilege' => 'index'
            ),
        );

        $configServizi = new Zend_Config($navServiziArray);
        $configSuDiNoi  = new Zend_Config($navSuDiNoiArray);

        $navigationServizi = new Zend_Navigation($configServizi);
        $navigationSuDiNoi = new Zend_Navigation($configSuDiNoi);


        $layout->serviziMenu = $navigationServizi;
        $layout->suDiNoiMenu = $navigationSuDiNoi;

    }

    protected function _initNavigationAdmin()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');

        $navProdottiArray = array(
            array(
                'controller' => 'admin',
                'action'=>'addproduct',
                'label' =>'Aggiungi prodotto',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellaprodotto',
                'label'=>'Modifica/cancella prodotti',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller' => 'admin',
                'action'=>'addcomponent',
                'label' =>'Aggiungi componente',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellacomponente',
                'label'=>'Modifica/cancella componenti',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller' => 'admin',
                'action'=>'addntbu',
                'label' =>'Aggiungi nota tecnica di buon uso',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellantbu',
                'label'=>'Modifica/cancella note tecniche di buon uso',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
        );

        $navCategorieArray = array(
            array(
                'controller' => 'admin',
                'action'=>'addtopcategory',
                'label' =>'Aggiungi macro-categoria',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellatopcategoria',
                'label'=>'Modifica/cancella macro-categoria',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller' => 'admin',
                'action'=>'addcategory',
                'label' =>'Aggiungi categoria',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellacategoria',
                'label'=>'Modifica/cancella categorie',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
        );

        $navUtentiArray = array(
            array(
                'controller' => 'admin',
                'action'=>'adduser',
                'label' =>'Aggiungi utente',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellautente',
                'label'=>'Modifica/cancella utenti',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
        );

        $navFaqArray = array(
            array(
                'controller' => 'admin',
                'action'=>'addfaq',
                'label' =>'Aggiungi FAQ',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellafaq',
                'label'=>'Modifica/cancella FAQ\'s',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
        );

        $navCentriArray = array(
            array(
                'controller' => 'admin',
                'action'=>'addcentro',
                'label' =>'Aggiungi centro assistenza',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'admin',
                'action'=>'modificacancellacentro',
                'label'=>'Modifica/cancella centri assistenza',
                'resource' => 'admin',
                'privilege' => 'index'
            ),
        );



        $configProdotti = new Zend_Config($navProdottiArray);
        $configCategorie = new Zend_Config($navCategorieArray);
        $configUtenti = new Zend_Config($navUtentiArray);
        $configCentri = new Zend_Config($navCentriArray);
        $configFaq = new Zend_Config($navFaqArray);

        $navigationProdotti = new Zend_Navigation($configProdotti);
        $navigationCategorie = new Zend_Navigation($configCategorie);
        $navigationUtenti = new Zend_Navigation($configUtenti);
        $navigationCentri = new Zend_Navigation($configCentri);
        $navigationFaq = new Zend_Navigation($configFaq);


        $layout->prodottiMenu = $navigationProdotti;
        $layout->categorieMenu = $navigationCategorie;
        $layout->utentiMenu = $navigationUtenti;
        $layout->centriMenu = $navigationCentri;
        $layout->faqMenu = $navigationFaq;

    }

    protected function _initNavigationStaff()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');

        $navStaffArray = array(
            array(
                'controller'=>'staff',
                'action'=>'addmalfunction',
                'label'=>'Aggiungi malfunzionamento',
                'resource' => 'staff',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'staff',
                'action'=>'associatemalfunction',
                'label'=>'Associa/Dissocia Malfunzionamento',
                'resource' => 'staff',
                'privilege' => 'index'
            ),
            array(
                'controller'=>'staff',
                'action'=>'modificacancellamalfunzionamento',
                'label'=>'Modifica/cancella Malfunzionamento',
                'resource' => 'staff',
                'privilege' => 'index'
            ),
        );

        $configStaff = new Zend_Config($navStaffArray);

        $navigationStaff= new Zend_Navigation($configStaff);

        $layout->staffMenu = $navigationStaff;

    }

    protected function _initMail()
    {
        try {
            $config = array(
                'auth' => 'login',
                'username' => 'grp55tw@gmail.com',
                'password' => 'univpm2015',
                'ssl' => 'tls',
                'port' => 587
            );

            $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            Zend_Mail::setDefaultTransport($mailTransport);
        } catch (Zend_Exception $e){
            $this->_logger->log('Eccezione initMail',Zend_Log::WARN);
        }
    }

    protected function _initDbParms()
    {
        //include il file con i parametri per la connessione
        //include_once (APPLICATION_PATH . '/../include/connect.php');  //DA USARE NEL PROGETTO: '/../../include/connect.php'
        //Crea oggetto Adapter PDO Mysql
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'db4free.net',
            'username' => 'grp55tw',
            'password' => 'tecweb',
            'dbname'   => 'grp55twdb'
        ));
        //Passa l'oggetto PDO a tutte le tabelle
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }


}

$translateValidators = array(
    Zend_Validate_NotEmpty::IS_EMPTY => 'Campo obbligatorio!',
    Zend_Validate_Regex::NOT_MATCH => 'Valore invalido!',
    Zend_Validate_StringLength::TOO_SHORT => 'Min di %min% caratteri.',
    Zend_Validate_StringLength::TOO_LONG => 'Max %max% caratteri.'

);
$translator = new Zend_Translate('array', $translateValidators);
Zend_Validate_Abstract::setDefaultTranslator($translator);

