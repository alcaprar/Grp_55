<?php

class AdminController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_form;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_logger = Zend_Registry::get('log');
        //$this->_publicModel = new Application_Model_Public(); //sostituire con il model per Admin da creare
    }

    public function indexAction()
    {

        $this->_logger->log('Admin - Index!', Zend_Log::DEBUG);

        $this->view->productForm = $this->getProductForm();
        //pagine statiche
        /*if(!is_null($viewStatic))
        {
            $this->render($viewStatic);
        }*/
    }

    public function addProductAction()
    {

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
            'action' => 'addProduct'
            ),
            'default'
        ));
        return $this->_form;
    }
}

