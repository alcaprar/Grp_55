<?php

class PublicController extends Zend_Controller_Action
{
    protected $_catalogModel;
    protected $_logger;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_logger = Zend_Registry::get('log');
        $this->_publicModel = new Application_Model_Public();
    }

    public function indexAction()
    {
        //Estrae le sottocategorie e le inserisce nella sidebar

        $this->_logger->log('Public - Index!', Zend_Log::DEBUG);

        //recupero i parametri
        $nomeCategoria = $this->_getParam('categoria', null);
        $idProdotto = $this->_getParam('prodotto',null);
        $this->_logger->log('$categoriaPar is_null: '. var_export(is_null($nomeCategoria),true),Zend_Log::DEBUG);
        $this->_logger->log('$prodottoPar is_null: '. var_export(is_null($idProdotto),true),Zend_Log::DEBUG);

        //se è passato il parametro categoria recupera i prodotti
        $prodotti=null;
        if(!is_null($nomeCategoria))
        {
            $prodotti = $this->_publicModel->getProdsByCat2($nomeCategoria);
        }

        //se è passato il parametro prodotto recupera il prodotto
        $prodotto = null;
        if(!is_null($idProdotto))
        {
            $prodotto = $this->_publicModel->getProdById($idProdotto);
        }

        $this->_logger->log($prodotto,Zend_Log::DEBUG);

        //recupera le categorie dal db attraverso il model
        $CategorieA = $this->_publicModel->getCatsByParId('A');
        $CategorieM = $this->_publicModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Categoria' => $nomeCategoria,
                'Prodotti' => $prodotti,
                'Prodotto' => $prodotto
            )
        );

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }
}

