<?php

class Application_Model_Admin extends App_Model_Abstract
{

    public function __construct()
    {
    }

    //Istanzia Utenti e attiva insertUser di Utenti.php
    public function insertProduct($prodotto)
    {
        return $this->getResource('Product')->insertProduct($prodotto);
    }

    public function selectProduct($paged, $order)
    {
        return $this->getResource('Product')->selectProduct($paged, $order);
    }

    public function deleteProduct($id)
    {
        return $this->getResource('Product')->deleteProduct($id);
    }

    public function updateProduct($prodotto, $id)
    {
        return $this->getResource('Product')->updateProduct($prodotto,$id);
    }

    public function getProdById($idProdotto)
    {
        return $this->getResource('Product')->getProdById($idProdotto);
    }

    public function getUserByName($info)
    {
        return $this->getResource('Utenti')->getUserByName($info);
    }
}