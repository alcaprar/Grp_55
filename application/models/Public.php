<?php

class Application_Model_Public extends App_Model_Abstract
{
    public function __construct()
    {
        //$this->_logger = Zend_Registry::get('log');
    }

    public function getTopCats()
    {
        return $this->getResource('TopCategory')->getTopCategorie();
    }

    public function getCatById($id)
    {
        return $this->getResource('Category')->getCatById($id);
    }

    public function getCatsByParId($parId)
    {
        return $this->getResource('Category')->getCats($parId);
    }

    public function getProdsByCat2($catName, $paged=null, $order=null)
    {
        return $this->getResource('Product')->getProdsByCat($catName, $paged, $order);
    }

    public function getProdById($idProdotto)
    {
        return $this->getResource('Product')->getProdById($idProdotto);
    }

    public function getProdByName($nomeProdotto, $paged, $order)
    {
        return $this->getResource('Product')->getProdByName($nomeProdotto, $paged, $order);
    }

    public function selectFaq($paged, $order)
    {
        return $this->getResource('Faq')->selectFaq($paged, $order);
    }

    public function selectCentro($paged, $order)
    {
        return $this->getResource('Centri')->selectCentro($paged, $order);
    }

    public function selectAppartenenza($idCentro)
    {
        return $this->getResource('Appartenenza')->selectAppartenenza($idCentro);
    }

    public function deleteAppartenenza($idTecnico)
    {
        return $this->getResource('Appartenenza')->deleteAppartenenza($idTecnico);
    }

    public function updateAppartenenza($idCentro, $idTecnico)
    {
        return $this->getResource('Appartenenza')->updateAppartenenza($idCentro,$idTecnico);
    }
}