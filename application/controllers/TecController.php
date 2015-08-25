<?php

class TecController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_authService;
    protected $_publicModel;
    protected $_tecModel;

    public function init()
    {
        $this->_helper->layout->setLayout('tecnico');
        $this->_logger = Zend_Registry::get('log');
        $this->_publicModel = new Application_Model_Public();
        $this->_tecModel = new Application_Model_Tec();
        $this->_authService = new Application_Service_Auth();
    }

    public function indexAction()
    {
        //Estrae le sottocategorie e le inserisce nella sidebar

        $this->_logger->log('Public - Index!', Zend_Log::DEBUG);

        //recupero i parametri
        $nomeCategoria = $this->_getParam('categoria', null);
        $idProdotto = $this->_getParam('prodotto',null);
        $viewStatic = $this->_getParam('viewStatic',null);
        $paged = $this->_getParam('page', 1);
        //log parametri
        $this->_logger->log('is_null($nomeCategoria): '. var_export(is_null($nomeCategoria),true),Zend_Log::DEBUG);
        $this->_logger->log('isnull($idProdotto): '. var_export(is_null($idProdotto),true),Zend_Log::DEBUG);
        $this->_logger->log('is_null($viewStatic): '. var_export(is_null($viewStatic),true),Zend_Log::DEBUG);
        $this->_logger->log('$paged= '. var_export($paged,true),Zend_Log::DEBUG);

        //se è passato il parametro categoria recupera i prodotti
        $prodotti=null;
        if(!is_null($nomeCategoria))
        {
            $prodotti = $this->_publicModel->getProdsByCat2($nomeCategoria, $paged, $order=null);
        }

        //se è passato il parametro prodotto recupera il prodotto
        $prodotto = null;
        if(!is_null($idProdotto))
        {
            $prodotto = $this->_publicModel->getProdById($idProdotto);
            $moreInfo = $this->_tecModel->getMoreInfoById($idProdotto);
        }


        //recupera le categorie dal db attraverso il model
        //serve per il menu
        $CategorieA = $this->_publicModel->getCatsByParId('A');
        $CategorieM = $this->_publicModel->getCatsByParId('M');

        //recupero le faq
        $faq = $this->_publicModel->selectFaq($paged=null, $order=null);

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Categoria' => $nomeCategoria,
                'Prodotti' => $prodotti,
                'Prodotto' => $prodotto,
                'MoreInfo' => $moreInfo,
                'Faq' => $faq
            )
        );

        //pagine statiche
        if(!is_null($viewStatic))
        {
            $this->render($viewStatic);
        }
    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}