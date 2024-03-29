<?php

class Application_Resource_Ntbu extends Zend_Db_Table_Abstract
{
    protected $_name    = 'NTBU';
    protected $_primaryKey = 'id';
    protected $_rowClass = 'Application_Resource_Ntbu_Item';

    public function init()
    {
    }

    public function getNtbuById($id)
    {
        return $this->find($id)->current();
    }

    public function selectNtbu($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('NTBU');
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

    public function insertNtbu($ntbu)
    {
        $this->insert($ntbu);
    }

    public function deleteNtbu($id)
    {
        $this->delete('id = '.$id);
    }

    public function updateNtbu($ntbu, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($ntbu as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }

    public function getNtbuByName($nome, $paged, $order)
    {
        $nome = str_replace('*', '%', $nome);
        $selectByName = $this->select()
            ->setIntegrityCheck(false)
            ->from('NTBU')
            ->where('Nome LIKE ?', '%'.$nome.'%');
        $selectByDescrizione = $this->select() //query piuttosto lenta nell'esecuzione
        ->setIntegrityCheck(false)
            ->from('NTBU')
            ->where('Descrizione LIKE ?', '%'.$nome.'%');
        $select = $this->select()
            ->union(array($selectByName, $selectByDescrizione));
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