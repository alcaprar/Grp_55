<?php

class Application_Model_Admin extends App_Model_Abstract
{

    public function __construct()
    {
    }

    public function getTopCats()
    {
        return $this->getResource('TopCategory')->getTopCategorie();
    }

    public function getTopCatById($id)
    {
        return $this->getResource('TopCategory')->getTopCatById($id);
    }

    public function getCats()
    {
        return $this->getResource('Category')->getCategorie();
    }

    public function getCatById($id)
    {
        return $this->getResource('Category')->getCatById($id);
    }

    public function insertTopCategory($categoria)
    {
        return $this->getResource('TopCategory')->insertTopCategory($categoria);
    }

    public function deleteTopCategory($id)
    {
        return $this->getResource('TopCategory')->deleteTopCategory($id);
    }

    public function updateTopCategory($categoria, $id)
    {
        return $this->getResource('TopCategory')->updateTopCategory($categoria, $id);
    }

    public function insertCategory($categoria)
    {
        return $this->getResource('Category')->insertCategory($categoria);
    }

    public function deleteCategory($id)
    {
        return $this->getResource('Category')->deleteCategory($id);
    }

    public function updateCategory($categoria, $id)
    {
        return $this->getResource('Category')->updateCategory($categoria, $id);
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

    public function associateComponent($idProdotto, $idComponente)
    {
        return $this->getResource('Composition')->associateComponent($idProdotto,$idComponente);
    }

    public function insertComponent($componente)
    {
        return $this->getResource('Component')->insertComponent($componente);
    }

    public function selectComponent($paged, $order)
    {
        return $this->getResource('Component')->selectComponent($paged, $order);
    }

    public function deleteComponent($id)
    {
        return $this->getResource('Component')->deleteComponent($id);
    }

    public function updateComponent($componente, $id)
    {
        return $this->getResource('Component')->updateComponent($componente,$id);
    }

    public function getComponentById($idComponent)
    {
        return $this->getResource('Component')->getComponentById($idComponent);
    }

    public function getComponentByIdFind($idComponente)
    {
        return $this->getResource('Component')->getComponentByIdFind($idComponente);
    }

    public function insertFaq($faq)
    {
        return $this->getResource('Faq')->insertFaq($faq);
    }

    public function selectFaq($paged, $order)
    {
        return $this->getResource('Faq')->selectFaq($paged, $order);
    }

    public function deleteFaq($id)
    {
        return$this->getResource('Faq')->deleteFaq($id);
    }

    public function updateFaq($faq, $id)
    {
        return $this->getResource('Faq')->updateFaq($faq,$id);
    }

    public function getFaqById($idFaq)
    {
        return $this->getResource('Faq')->getFaqById($idFaq);
    }

    public function insertCentro($centro)
    {
        return $this->getResource('Centri')->insertCentro($centro);
    }

    public function selectCentro($paged, $order)
    {
        return $this->getResource('Centri')->selectCentro($paged, $order);
    }

    public function deleteCentro($id)
    {
        return$this->getResource('Centri')->deleteCentro($id);
    }

    public function updateCentro($centro, $id)
    {
        return $this->getResource('Centri')->updateCentro($centro,$id);
    }

    public function getCentroById($idCentro)
    {
        return $this->getResource('Centri')->getCentroById($idCentro);
    }

    public function getUserByName($info)
    {
        return $this->getResource('Utenti')->getUserByName($info);
    }

    public function getUserById($id)
    {
        return $this->getResource('Utenti')->getUserById($id);
    }

    public function selectUser($paged, $order)
    {
        return $this->getResource('Utenti')->selectUser($paged, $order);
    }

    public function insertUser($user)
    {
        return $this->getResource('Utenti')->insertUser($user);
    }
}