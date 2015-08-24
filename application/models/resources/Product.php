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

    // Estrae tutti i prodotti , eventualmente paginati ed ordinati
    public function selectProduct($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Prodotti');
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

    //aggiunge un prodotto
    public function insertProduct($prodotto)
    {
        $this->insert($prodotto);
    }

    //rimuove un prodotto
    public function deleteProduct($id)
    {
        $this->fetchRow($this->select()->where('id = ?', $id))->delete();
    }

    //aggiorna il prodotto
    public function updateProduct($prodotto, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($prodotto as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }
}

