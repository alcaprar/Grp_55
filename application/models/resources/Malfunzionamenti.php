<?php

class Application_Resource_Malfunzionamenti extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Malfunzionamenti';
    protected $_rowClass = 'Application_Resource_Malfunzionamenti_Item';

    public function init()
    {
    }

    //Estrae i componenti di un prodotto
    public function getMalfById($id)
    {
        $select = $this->select()
            ->where('idProdotto IN(?)', $id)
            ->order('Malfunzionamento');
        return $this->fetchAll($select);
    }
}