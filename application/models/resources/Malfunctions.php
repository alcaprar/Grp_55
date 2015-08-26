<?php

class Application_Resource_Malfunctions extends Zend_Db_Table_Abstract
{
    protected $_name = 'MalfunzionamentiProdotti';
    protected $_primary = array('idProdotto', 'idMalfunzionamento');
    protected $_rowClass = 'Application_Resource_Malfunctions_Item';

    public function init()
    {
    }

    public function getMalfunctionsByProd($idProdotto)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('MalfunzionamentiProdotti')
            ->where('idProdotto = ?', $idProdotto);
        return $this->fetchAll($select);
    }

    public function associateMalfunction($idMalfunction, $idProdotto)
    {
        return $this->insert(array(
            "idMalfunzionamento" => $idMalfunction,
            "idProdotto" => $idProdotto
        ));
    }
}

