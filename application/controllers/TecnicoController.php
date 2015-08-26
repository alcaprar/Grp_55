<?php

class TecnicoController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_authService;
    protected $_tecnicoModel;

    public function init()
    {
        $this->_helper->layout->setLayout('tecnico');
        $this->_logger = Zend_Registry::get('log');
        $this->_tecnicoModel = new Application_Model_Tecnico();
        $this->_authService = new Application_Service_Auth();
    }

    public function indexAction()
    {
        //Estrae le sottocategorie e le inserisce nella sidebar

        //recupero i parametri
        $nomeCategoria = $this->_getParam('categoria', null);
        $idProdotto = $this->_getParam('prodotto',null);
        $viewStatic = $this->_getParam('viewStatic',null);
        $paged = $this->_getParam('page', 1);

        //se è passato il parametro categoria recupera i prodotti
        $prodotti=null;
        if(!is_null($nomeCategoria))
        {
            $prodotti = $this->_tecnicoModel->getProdsByCat2($nomeCategoria, $paged, $order=null);
        }

        //se è passato il parametro prodotto recupera il prodotto
        $prodotto = null;
        $components = array();
        $malfunctions = array();
        if(!is_null($idProdotto))
        {
            $prodotto = $this->_tecnicoModel->getProdById($idProdotto);
            $temp = $this->_tecnicoModel->getComponentsByProd($idProdotto);
            foreach($temp as $t)
            {

                $components = $this->_tecnicoModel->getComponentById($t->idComponente);
            }
            $temp = $this->_tecnicoModel->getMalfunctionsByProd($idProdotto);
            foreach($temp as $t)
            {
                $malfunctions = $this->_tecnicoModel->getMalfunctionById($t->idMalfunzionamento);
            }
        }


        //recupera le categorie dal db attraverso il model
        //serve per il menu
        $CategorieA = $this->_tecnicoModel->getCatsByParId('A');
        $CategorieM = $this->_tecnicoModel->getCatsByParId('M');


        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Categoria' => $nomeCategoria,
                'Prodotti' => $prodotti,
                'Prodotto' => $prodotto,
                'Componenti' => $components,
                'Malfunzionamenti' => $malfunctions
            )
        );
    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}