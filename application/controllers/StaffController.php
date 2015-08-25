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

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}