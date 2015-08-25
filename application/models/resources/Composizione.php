<?php

class Application_Resource_Composizione extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Composizione';
    protected $_rowClass = 'Application_Resource_Composizione_Item';

    public function init()
    {

    }

    //Estrae i componenti di un prodotto
    public function getCompositionById($id)
    {
        $select = $this->select()
            ->where('idProdotto IN(?)', $id)
            ->order('idComponente');
        return $this->fetchAll($select);
    }
}