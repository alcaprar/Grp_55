<?php

class Application_Resource_Faq extends Zend_Db_Table_Abstract
{
    protected $_name = 'FAQ';
    protected $_primary = 'id';
    protected $_rowClass = 'Application_Resource_Faq_Item';

    public function init()
    {
    }

    public function insertFaq($faq)
    {
        $this->insert($faq);
    }

    // Estrae tutte le faq , eventualmente paginati ed ordinati
    public function selectFaq($paged=null, $order=null)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('FAQ');
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

    // Estrae i dati di una faq
    public function getFaqById($id)
    {
        return $this->find($id)->current();
    }

    //rimuove una faq
    public function deleteFaq($id)
    {
        $this->fetchRow($this->select()->where('id = ?', $id))->delete();
    }

    //aggiorna la faq
    public function updateFaq($faq, $id)
    {
        //Fa la SELECT, essendo il risultato una sola riga, faccio fetchRow
        $old = $this->fetchRow($this->select()->where('id = ?', $id));   // (filtro) seleziona l'utente con l'id specificato

        //Aggiorno poi i campi desiderati
        foreach ($faq as $key => $value) {
            $old->$key = $value;
        }

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }
}