<?php

class Application_Model_Staff extends App_Model_Abstract
{
    public function __construct()
    {
        //$this->_logger = Zend_Registry::get('log');
    }

    public function getMalfunctionById($id)
    {
        return $this->getResource('Malfunction')->getMalfunctionById($id);
    }

    public function getMalfunctionByIdFind($id)
    {
        return $this->getResource('Malfunction')->getMalfunctionByIdFind($id);
    }

    public function insertMalfunction($newMalf)
    {
        return $this->getResource('Malfunction')->insertMalfunction($newMalf);

    }

    public function selectMalfunction($paged, $order)
    {
        return $this->getResource('Malfunction')->selectMalfunction($paged, $order);
    }

    public function updateMalfunction($updateMalf, $idMalf)
    {
        return $this->getResource('Malfunction')->updateMalfunction($updateMalf, $idMalf);
    }

    public function deleteMalfunction($idMalf)
    {
        return $this->getResource('Malfunction')->deleteMalfunction($idMalf);
    }

}