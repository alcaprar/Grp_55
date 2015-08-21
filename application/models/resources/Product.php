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

    // Estrae tutti i prodotti di una categoria, eventualmente paginati ed ordinati
    public function getProdsByCat($cat, $paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Prodotti')
            ->where('Categorie.Nome = ?', $cat)
            ->join('Categorie','Categorie.id = Prodotti.idCategoria',array());
        if (true === is_array($order)) {
            $select->order($order);
        }
        if (null !== $paged) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage(3)
                ->setCurrentPageNumber((int) $paged);
            return $paginator;
        }
        return $this->fetchAll($select);
    }

}

