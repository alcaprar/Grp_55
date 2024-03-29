<?php

class Application_Resource_Malfunction extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Malfunzionamenti';
    protected $_primaryKey = 'id';
    protected $_rowClass = 'Application_Resource_Malfunction_Item';

    public function init()
    {
    }

    // Estrae i malfunzionamenti di un prodotto
    public function getMalfunctionById($id)
    {
        return $this->find($id)->current();
    }

    // Estrae i dati di un componente
    public function getMalfunctionByIdFind($id)
    {
        return $this->find($id)->current();
    }

    public function getMalfunctionsByName($nome, $paged, $order)
    {
        $nome = str_replace('*', '', $nome);
        $select1 = $this->select()
            ->setIntegrityCheck(false)
            ->from('Malfunzionamenti')
            ->where('Malfunzionamento LIKE ?', '% '.$nome.' %');
        $select2 = $this->select()
            ->setIntegrityCheck(false)
            ->from('Malfunzionamenti')
            ->where('Malfunzionamento LIKE ?',''.$nome.' %');
        $select3 = $this->select()
            ->setIntegrityCheck(false)
            ->from('Malfunzionamenti')
            ->where('Malfunzionamento LIKE ?', '% '.$nome.'');
        $select = $this->select()
            ->union(array($select1, $select2, $select3));
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

    // Estrae tutti i malfunzionamenti, eventualmente paginati ed ordinati
    public function selectMalfunction($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Malfunzionamenti');
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

    //aggiunge un malfunzionamento
    public function insertMalfunction($malfunction)
    {
        $this->insert($malfunction);
    }

    //rimuove un malfunzionamento
    public function deleteMalfunction($id)
    {
        $this->delete('id = '.$id);
    }

    //aggiorna un malfunzionamento
    public function updateMalfunction($malfunction, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato
        //Aggiorno poi i campi desiderati
        foreach ($malfunction as $key => $value) {
            $old->$key = $value;
        }
        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);
    }

}

