<?php

class Application_Resource_Component extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Componenti';
    protected $_primaryKey = 'id';
    protected $_rowClass = 'Application_Resource_Component_Item';

    public function init()
    {
    }

    // Estrae i dati di un componente
    public function getComponentById($id)
    {
        return $this->find($id)->current();
    }

    // Estrae tutti i componenti , eventualmente paginati ed ordinati
    public function selectComponent($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Componenti');
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

    //aggiunge un componente
    public function insertComponent($componente)
    {
        $this->insert($componente);
    }

    //rimuove un componente
    public function deleteComponent($id)
    {
        $this->fetchRow($this->select()->where('id = ?', $id))->delete();
    }

    //aggiorna il componente
    public function updateProduct($componente, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($componente as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }

}

