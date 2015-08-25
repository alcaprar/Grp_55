<?php

class Application_Resource_Category extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Categorie';
    protected $_primary  = 'id';
    protected $_rowClass = 'Application_Resource_Category_Item';

    public function init()
    {
    }

    // Estrae i dati della categoria $id
    public function getCatById($id)
    {
        return $this->find($id)->current();
    }

    // Estrae tutte le Categorie di uno dei due macro-gruppi
    public function getCats($top)
    {
        $select = $this->select()
            ->where('Tipo IN(?)', $top)
            ->order('Nome');
        return $this->fetchAll($select);
    }

    //estrae tutte le categorie
    public function getCategorie()
    {
        $select = $this->select()
            ->from('Categorie')
            ->order('Nome');
        return $this->fetchAll($select);
    }
}