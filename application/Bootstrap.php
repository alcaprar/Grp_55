<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $_view;
    protected $_logger;

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
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=ISO-8859-1');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'it-IT');
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/bootstrap.min.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/font-awesome.min.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/prettyPhoto.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/price-range.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/animate.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/responsive.css'));
        $this->_view->headLink()->appendStylesheet($this->_view->baseUrl('css/main.css'));
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
    Zend_Validate_StringLength::TOO_LONG => 'Max %max% caratteri.',
    Zend_Validate_EmailAddress::INVALID => 'E-mail invalida!',
    Zend_Validate_EmailAddress::INVALID_FORMAT => 'E-mail invalida!'
);
$translator = new Zend_Translate('array', $translateValidators);
Zend_Validate_Abstract::setDefaultTranslator($translator);

