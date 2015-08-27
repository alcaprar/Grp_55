<?php

class App_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    protected $_acl=null;
    //protected $_role=null;
    protected $_auth=null;

    public function __construct(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        //$this->_auth = $auth;

        $this->_auth = Zend_Auth::getInstance();
        $this->_role = !$this->_auth->hasIdentity() ? 'unregistered' : $this->_auth->getIdentity()->Ruolo;
        //$this->_acl = new Application_Model_Acl();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $resource = $request->getControllerName();
        $action = $request->getActionName();
        $role = $this->_role;
        if(!$this->_acl->isAllowed($role, $resource, $action)) {
            $this->_auth->clearIdentity();
            $this->denyAccess();
        }
        /*
        if (!$this->_acl->isAllowed($this->_role, $request->getControllerName())) {
            $this->_auth->clearIdentity();
            $this->denyAccess();
        }*/
    }

    public function denyAccess()
    {
        $this->_request->setModuleName('default')
            ->setControllerName('public')
            ->setActionName('accessonegato');
    }
}