<?php

class Application_Model_Tecnico extends App_Model_Abstract
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

    public function getComponentsByProd($idProdotto)
    {
        return $this->getResource('Composition')->getComponentsByProd($idProdotto);
    }

    public function getMalfunctionsByProd($idProdotto)
    {
        return $this->getResource('Malfunctions')->getMalfunctionsByProd($idProdotto);
    }

    public function getComponentById($id)
    {
        return $this->getResource('Component')->getComponentById($id);
    }

    public function getMalfunctionById($id)
    {
        return $this->getResource('Malfunction')->getMalfunctionById($id);
    }

    public function selectFaq($paged, $order)
    {
        return $this->getResource('Faq')->selectFaq($paged, $order);
    }

    public function getNtbuByProd($idProdotto)
    {
        return $this->getResource('Ntbuprodotti')->getNtbuByProd($idProdotto);
    }

    public function getNtbuById($id)
    {
        return $this->getResource('Ntbu')->getNtbuById($id);
    }

    public function selectMalfunction($paged, $order)
    {
        return $this->getResource('Malfunction')->selectMalfunction($paged, $order);
    }
}