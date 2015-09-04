<?php

class Application_Resource_Centri extends Zend_Db_Table_Abstract
{
    protected $_name = 'CentriAssistenza';
    protected $_primary = 'id';
    protected $_rowClass = 'Application_Resource_Centri_Item';

    public function init()
    {
    }

    public function insertCentro($centro)
    {
        $this->insert($centro);
    }

    // Estrae tutti i centri , eventualmente paginati ed ordinati
    public function selectCentro($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('CentriAssistenza');
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

    // Estrae i dati di un centro
    public function getCentroById($id)
    {
        return $this->find($id)->current();
    }

    //rimuove un centro
    public function deleteCentro($id)
    {
        $this->delete('id = '.$id);
    }

    //aggiorna il centro
    public function updateCentro($centro, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($centro as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }

    public function getCentriByName($nome, $paged, $order)
    {
        $nome = str_replace('*', '%', $nome);
        $selectByName = $this->select()
            ->setIntegrityCheck(false)
            ->from('CentriAssistenza')
            ->where('Nome LIKE ?', '%'.$nome.'%');
        $selectByIndirizzo = $this->select() //query piuttosto lenta nell'esecuzione
        ->setIntegrityCheck(false)
            ->from('CentriAssistenza')
            ->where('Indirizzo LIKE ?', '%'.$nome.'%');
        $selectByTelefono = $this->select() //query piuttosto lenta nell'esecuzione
        ->setIntegrityCheck(false)
            ->from('CentriAssistenza')
            ->where('Telefono LIKE ?', '%'.$nome.'%');
        $select = $this->select()
            ->union(array($selectByName, $selectByIndirizzo,$selectByIndirizzo));
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