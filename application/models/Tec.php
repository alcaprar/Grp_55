<?php

class Application_Model_Tec extends App_Model_Abstract
{
    public function __construct()
    {
        //$this->_logger = Zend_Registry::get('log');
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

    public function getMoreInfoById($idProdotto)
    {
        $comp = $this->getResource('Composizione')->getCompositionById($idProdotto);
        $malfunzionamenti = $this->getResource('Malfunzionamenti')->getMalfById($idProdotto);
        return array($comp, $malfunzionamenti);
    }

    public function selectFaq($paged, $order)
    {
        return $this->getResource('Faq')->selectFaq($paged, $order);
    }
}