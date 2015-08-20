<?php

class Application_Resource_Product extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Prodotti';
    protected $_primary  = 'id';
    protected $_rowClass = 'Application_Resource_Product_Item';

    public function init()
    {
    }

    // Estrae i dati di un prodotto
    public function getProdById($id)
    {
        return $this->find($id)->current();
    }

    // Estrae tutti i prodotti di una categoria
    public function getProd($cat)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Prodotti')
            ->where('Categorie.Nome = ?', $cat)
            ->join('Categorie','Categorie.id = Prodotti.idCategoria');
        return $this->fetchAll($select);
    }
}

