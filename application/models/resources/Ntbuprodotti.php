<?php

class Application_Resource_Ntbuprodotti extends Zend_Db_Table_Abstract
{
    protected $_name    = 'NTBUProdotti';
    protected $_primary = array('idProdotto', 'idNTBU');
    protected $_rowClass = 'Application_Resource_Composition_Item';

    public function init()
    {
    }

    public function associateNtbu($idProdotto, $idNtbu)
    {
        $composizione = array(
            'idProdotto' => $idProdotto,
            'idNTBU' => $idNtbu
        );

        return $this->insert($composizione);

    }

    public function getNtbuByProd($idProdotto)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('NTBUProdotti')
            ->where('idProdotto = ?', $idProdotto)
            ->order('idNTBU');
        return $this->fetchAll($select);
    }

    public function deleteNtbuprodotti($id)
    {
        $this->delete('idProdotto = '.$id);
    }
}
