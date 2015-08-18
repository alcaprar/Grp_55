<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $_view;

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
        $this->_view->headTitle('Grp_55 TecWeb')
            ->setSeparator(' - ');
    }

}

