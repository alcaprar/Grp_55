<?php

class Application_Resource_StaffCategorie extends Zend_Db_Table_Abstract
{
    protected $_name    = 'StaffCategorie';
    protected $_primary = array('idUtente', 'idCategoria');
    protected $_rowClass = 'Application_Resource_StaffCategorie_Item';

    public function init()
    {
    }

    public function associateStaffCategoria($idUtente, $idCategoria)
    {
        $composizione = array(
            'idUtente' => $idUtente,
            'idCategoria' => $idCategoria
        );

        return $this->insert($composizione);

    }

    public function getCatByUser($idUtente)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('StaffCategorie')
            ->where('idUtente = ?', $idUtente)
            ->order('idCategoria');
        return $this->fetchAll($select);
    }

    public function deleteStaffCategorie($idUtente)
    {
        $this->delete('idUtente = '.$idUtente);
    }
}