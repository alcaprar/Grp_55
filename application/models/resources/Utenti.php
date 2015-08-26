<?php

class Application_Resource_Utenti extends Zend_Db_Table_Abstract
{
    protected $_name    = 'Utenti';
    protected $_primary  = 'idUtenti';
    protected $_rowClass = 'Application_Resource_Utenti_Item';

    public function init()
    {
    }

    public function insertUser($user)
    {
        $this->insert($user);
    }

    public function getUserByName($userName)
    {
        return $this->fetchRow($this->select()->where('Username = ?', $userName));
    }

    public function getUserById($id)
    {
        return $this->find($id)->current();
    }

    // Estrae tutti gli utenti , eventualmente paginati ed ordinati
    public function selectUser($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Utenti');
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

    //rimuove un utente
    public function deleteUserq($id)
    {
        $this->fetchRow($this->select()->where('idUtenti = ?', $id))->delete();
    }

    //aggiorna l'user
    public function updateUser($user, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('idUtenti = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($user as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }
}