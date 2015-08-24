<?php

class Application_Resource_Utenti extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Utenti';
    protected $_primary  = 'idUtenti';
    protected $_rowClass = 'Application_Resource_Utenti_Item';

    public function init()
    {
    }

    public function getUserByName($usrName)
    {
        return $this->fetchRow($this->select()->where('Username = ?', $usrName));
    }
}