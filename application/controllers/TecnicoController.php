<?php

class TecnicoController extends Zend_Controller_Action
{
    protected $_logger;
    protected $_authService;
    protected $_tecnicoModel;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_logger = Zend_Registry::get('log');
        $this->_tecnicoModel = new Application_Model_Tecnico();
        $this->_authService = new Application_Service_Auth();

        //recupera le categorie Top
        $TopCats = $this->_tecnicoModel->getTopCats();

        $navMenus = array();

        foreach($TopCats as $topcat) {
            $categorie = $this->_tecnicoModel->getCatsByParId($topcat->Nome);
            $this->_logger->log($categorie, Zend_Log::DEBUG);
            $navCatArray = array();
            foreach($categorie as $cat)
            {
                $this->_logger->log($cat, Zend_Log::DEBUG);
                $navCatArray[] = array(
                    'controller' => 'tecnico',
                    'action' => 'catalogo',
                    'params' => array('categoria' => $cat->Nome),
                    'label' => $cat->Nome,
                    'resource' => 'tecnico',
                    'privilege' => 'catalogo'
                );
            }

            $configTopCat = new Zend_Config($navCatArray);

            $navMenus[$topcat['Nome']] = new Zend_Navigation($configTopCat);
            $this->_logger->log($navMenus[$topcat['Nome']], Zend_Log::DEBUG);
        }

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'Menu' => $navMenus,
                'TopCats' => $TopCats)
        );

        /*$navCatAutoArray = array();
        foreach ($CategorieA->toArray() as $categoria) {
            $navCatAutoArray[] = array(
                'controller' => 'public',
                'action' => 'catalogo',
                'params' => array('categoria' => $categoria['Nome']),
                'label' => $categoria['Nome'],
                'resource' => 'public',
                'privilege' => 'catalogo'
            );
        }
        $configAuto = new Zend_Config($navCatAutoArray);

        $navigationAuto = new Zend_Navigation($configAuto);

        $navCatMotoArray = array();
        foreach ($CategorieM->toArray() as $categoria) {
            $navCatMotoArray[] = array(
                'controller' => 'public',
                'action' => 'catalogo',
                'params' => array('categoria' => $categoria['Nome']),
                'label' => $categoria['Nome'],
                'resource' => 'public',
                'privilege' => 'catalogo'
            );
        }

        $configMoto = new Zend_Config($navCatMotoArray);

        $navigationMoto = new Zend_Navigation($configMoto);*/
    }

    public function indexAction()
    {

    }

    public function schedaprodottoAction()
    {
        //recupero i parametri
        $idProdotto = $this->_getParam('prodotto',null);
        $paged = $this->_getParam('page', 1);

        //se è nullo faccio il redirector sulla index action
        $prodotto=null;
        if(is_null($idProdotto))
        {
            return $this->_helper->redirector('index', 'tecnico');
        }

        //se è passato il parametro prodotto recupera il prodotto
        $prodotto = null;
        $ntbus = array();
        $components = array();
        $malfunctions = array();
        if(!is_null($idProdotto))
        {
            $prodotto = $this->_tecnicoModel->getProdById($idProdotto);
            $temp = $this->_tecnicoModel->getComponentsByProd($idProdotto);
            foreach($temp as $t)
            {
                $component = $this->_tecnicoModel->getComponentById($t->idComponente);
                $components[] = $component;
            }

            $temp = $this->_tecnicoModel->getMalfunctionsByProd($idProdotto);
            foreach($temp as $t)
            {
                $malfunction = $this->_tecnicoModel->getMalfunctionById($t->idMalfunzionamento);
                $malfunctions[] = $malfunction;
            }
            $temp = $this->_tecnicoModel->getNtbuByProd($idProdotto);
            foreach($temp as $t)
            {
                $ntbu = $this->_tecnicoModel->getNtbuById($t->idNTBU);
                $ntbus[] = $ntbu;
            }
        }
        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'Prodotto' => $prodotto,
                'Componenti' => $components,
                'Malfunzionamenti' => $malfunctions,
                'Ntbu' => $ntbus
            )
        );
    }

    public function schedacomponenteAction()
    {
        //recupero i parametri
        $idComponente = $this->_getParam('componente',null);
        $paged = $this->_getParam('page', 1);

        //se è nullo faccio il redirector sulla index action
        $componente=null;
        if(is_null($idComponente))
        {
            return $this->_helper->redirector('index', 'tecnico');
        }

        //se non è nullo recupero il prodotto
        $componente = $this->_tecnicoModel->getComponentById($idComponente);


        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'Componente' => $componente
            )
        );
    }

    public function catalogoAction()
    {
        //recupero la categoria e la pagina
        $nomeCategoria = $this->_getParam('categoria', null);
        $paged = $this->_getParam('page', 1);

        //se è nullo faccio il redirector sulla index action
        $prodotti=null;
        if(is_null($nomeCategoria))
        {
            return $this->_helper->redirector('index', 'tecnico');
        }

        //se non è nullo recupero i prodotti
        $prodotti = $this->_tecnicoModel->getProdsByCat2($nomeCategoria, $paged, $order=null);

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'Categoria' => $nomeCategoria,
                'Prodotti' => $prodotti
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