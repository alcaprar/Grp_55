<?php

class PublicController extends Zend_Controller_Action
{
    protected $_catalogModel;
    protected $_logger;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_logger = Zend_Registry::get('log');
        $this->_catalogModel = new Application_Model_Catalog();
    }

    public function indexAction()
    {
        //Estrae le sottocategorie e le inserisce nella sidebar

        $this->_logger->log('Public - Index!', Zend_Log::DEBUG);

        //recupera il parametro
        $categoria = $this->_getParam('categoria', null);
        $this->_logger->log('$categoria: '. var_export(is_null($categoria),true),Zend_Log::DEBUG);

        //se è passato il parametro recupera i prodotti
        $prodotti=null;
        if(!is_null($categoria))
        {
            $prodotti = $this->_catalogModel->getProdByCat2($categoria);
        }
        $this->_logger->log('$prodotti: '. var_export(is_null($prodotti),true),Zend_Log::DEBUG);
        $this->_logger->log($prodotti,Zend_Log::DEBUG);

        //recupera le categorie dal db attraverso il model
        $CategorieA = $this->_catalogModel->getCatsByParId('A');
        $CategorieM = $this->_catalogModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Parametro' => $categoria,
                'Prodotti' => $prodotti
            )
        );

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }
}

