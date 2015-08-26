<?php

class Application_Model_Staff extends App_Model_Abstract
{
    public function __construct()
    {
        //$this->_logger = Zend_Registry::get('log');
    }

    public function insertMalf($newMalf, $idProdotto)
    {
        return (
            $this->getResource('Malfunction')->insertMalfunction($newMalf)
            &&
            $this->getResource('Malfunctions')->associateMalfunction($newMalf->id, $idProdotto)
        );
    }

    public function updateMalf($updateMalf, $idMalf)
    {
        return $this->getResource('Malfunction')->updateMalfunction($updateMalf, $idMalf);
    }
}