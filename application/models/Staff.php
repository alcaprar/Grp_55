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

    public function getMalfunctionsByIdProd($idprod)
    {
        return $this->getResource('Malfunctions')->getMalfunctionsByProd($idprod);
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

    public function getProducts()
    {
        return $this->getResource('Product')->selectProduct();
    }

    public function getProdByName($prodname)
    {
        return $this->getResource('Product')->getProdByName($prodname);
    }

    public function getProdById($id)
    {
        return $this->getResource('Product')->getProdById($id);
    }

    public function associateMalfunction($idProdotto, $malf)
    {
        $this->getResource('Malfunctions')->deleteMalfunctions($idProdotto);

        for($i=0; $i< sizeof($malf);$i++)
        {
            $this->getResource('Malfunctions')->associateMalfunction($malf[$i],$idProdotto);
        }
    }

}