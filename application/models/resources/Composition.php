<?php

class Application_Resource_Composition extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Composizione';
    protected $_primary = array('idProdotto', 'idComponente');
    protected $_rowClass = 'Application_Resource_Composition_Item';

    public function init()
    {
    }

    public function getComponentsByProd($idProdotto)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Composizione')
            ->where('idProdotto = ?', $idProdotto)
            ->order('idComponente');
        return $this->fetchAll($select);
    }
}

