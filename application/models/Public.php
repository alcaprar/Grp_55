<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 19/08/2015
 * Time: 16.11
 */

//require_once 'App\Model\Abstract.php';

class Application_Model_Public extends App_Model_Abstract
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

    public function getProdsByCat($catId, $paged=null, $order=null, $deep=true)
    {
        if (true === $deep) {
            $ids = $this->getResource('Category')->getCatChilIds($catId, true);
            $ids[] = $catId;
            $catId = $ids;
        }
        return $this->getResource('Product')->getProdsByCat($catId, $paged, $order);
    }

    public function getProdsByCat2($catName, $paged=null, $order=null)
    {
        return $this->getResource('Product')->getProdsByCat($catName, $paged, $order);
    }

    public function getProdById($idProdotto)
    {
        return $this->getResource('Product')->getProdById($idProdotto);
    }
}