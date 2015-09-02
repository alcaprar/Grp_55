<?php

class Application_Service_Auth
{
    protected $_adminModel;
    protected $_auth;
    protected $_logger;

    public function __construct()
    {
        $this->_adminModel = new Application_Model_Admin();
        $this->_logger = Zend_Registry::get('log');
    }

    public function authenticate($credentials)
    {
        $adapter = $this->getAuthAdapter($credentials);
        $auth    = $this->getAuth();
        $result  = $auth->authenticate($adapter);

        if (!$result->isValid()) {
            return false;
        }
        $user = $this->_adminModel->getUserByName($credentials['username']);
        $auth->getStorage()->write($user);

        return true;
    }

    public function getAuth()
    {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth;
    }

    public function getIdentity()
    {
        $auth = $this->getAuth();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }
        return false;
    }

    public function clear()
    {
        if($this->getAuth()->getIdentity()->Ruolo=='staff'){
            Zend_Session::namespaceUnset('staff');
        }

        $this->getAuth()->clearIdentity();
    }

    public function getAuthAdapter($values)
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table_Abstract::getDefaultAdapter(),
            'Utenti',
            'Username',
            'Password'
        );
        $authAdapter->setIdentity($values['username']);
        $authAdapter->setCredential($values['password']);
        return $authAdapter;
    }
}
