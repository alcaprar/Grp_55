<?php

class Application_Resource_Category extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Categorie';
    protected $_primary  = 'id';
    protected $_rowClass = 'Application_Resource_Category_Item';

    public function init()
    {
    }

    public function insertCategory($categoria)
    {
        return $this->insert($categoria);
    }

    public function deleteCategory($id)
    {
        $this->fetchRow($this->select()->where('id = ?', $id))->delete();
    }

    public function updateCategory($categoria, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($categoria as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);
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
    public function getCategorie($where=null,$paged=null, $order=null)
    {
        $select = $this->select()
            ->from('Categorie')
            ->order('Nome');
        if(true ===is_array($where)){
            $select->where('id IN(?)',$where);
        }
        if (true === is_array($order)) {
            $select->order($order);
        }
        if (null !== $paged) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage(3)
                ->setPageRange(5)
                ->setCurrentPageNumber((int) $paged);
            return $paginator;
        }

        return $this->fetchAll($select);
    }
}