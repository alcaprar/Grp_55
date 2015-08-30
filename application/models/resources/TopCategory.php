<?php

class Application_Resource_TopCategory extends Zend_Db_Table_Abstract
{
    protected $_name = 'TopCategorie';
    protected $_primary = 'id';
    protected $_rowClass = 'Application_Resource_TopCategory_Item';

    public function init()
    {
    }

    // Estrae i dati della categoria $id
    public function getTopCatById($id)
    {
        return $this->find($id)->current();
    }

    //estrae tutte le categorie
    public function getTopCategorie()
    {
        $select = $this->select()
            ->from('TopCategorie')
            ->order('Nome');
        return $this->fetchAll($select);
    }

    public function insertTopCategory($categoria)
    {
        return $this->insert($categoria);
    }

    public function deleteTopCategory($id)
    {
        $this->fetchRow($this->select()->where('id = ?', $id))->delete();
    }

    public function updateTopCategory($categoria, $id)
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
}