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

}