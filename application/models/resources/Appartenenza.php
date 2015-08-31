<?php

class Application_Resource_Appartenenza extends Zend_Db_Table_Abstract
{
    protected $_name = 'Appartenenza';
    protected $_primary = array('idCentro', 'idTecnico');
    protected $_rowClass = 'Application_Resource_Appartenenza_Item';

    public function init()
    {
    }

    public function insertAppartenenza($idCentro, $idTecnico){
        $appartenenza = array(
            'idCentro' => $idCentro,
            'idTecnico' => $idTecnico
        );

        return $this->insert($appartenenza);
    }

    public function selectAppartenenza($idCentro)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Appartenenza')
            ->where('Appartenenza.idCentro = ?', $idCentro)
            ->join('Utenti','Utenti.idUtenti = Appartenenza.idTecnico');
        Zend_Registry::get('log')->log($select->__toString(),Zend_Log::DEBUG);
        return $this->fetchAll($select);
    }

    public function deleteAppartenenza($idTecnico)
    {
        $this->fetchRow($this->select()->from('Appartenenza')->where('idTecnico = ?', $idTecnico))->delete();
    }

    public function updateAppartenenza($idCentro, $idTecnico)
    {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from('Appartenenza')
            ->where('idTecnico = ?', $idTecnico);
        $old = $this->fetchRow($select);

        $old->idCentro = $idCentro;

        //Aggiorna (UPDATE) la riga nel database con i nuovi valori
        $old->save($old);

    }
}
