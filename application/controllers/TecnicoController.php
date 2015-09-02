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
    }

    public function indexAction()
    {
        $this->view->headTitle('Home tecnico');
    }

    public function schedaprodottoAction()
    {
        $this->view->headTitle('Scheda prodotto');
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
        $this->view->headTitle('Scheda componente');
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
        $this->view->headTitle('Catalogo tecnico');
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

    public function listamalfunzionamentiAction()
    {
        $this->view->headTitle('Lista malfunzionamenti');
        //recupero l'eventuale pagina
        $paged = $this->_request->getParam('page',1);

        $malfunzionamenti = $this->_tecnicoModel->selectMalfunction($paged, $order=null);

        //assegno le variabili alla view
        $this->view->assign('Malfunzionamenti',$malfunzionamenti);
    }

    public function cercaAction()
    {
        $this->view->headTitle('Risultati ricerca');
        //recupero i parametri
        $query = $this->_getParam('query', null);
        $page = $this->_getParam('page', 1);

        //se non è nullo recupero i prodotti
        $malfunzionamenti = $this->_tecnicoModel->getMalfunctionsByName($query, $page, $order=null);

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'query' => $query,
                'Malfunzionamenti' => $malfunzionamenti
            )
        );
    }

    public function redirectorurlcercaAction()
    {
        $request = $this->getRequest();

        //arrivata una richiesta di cerca
        if ($request->isPost()) {
            $query = $request->getPost()['querymalf'];
            $this->_redirector = $this->_helper->getHelper('Redirector');

            $this->_redirector->gotoSimple('cerca',
                'tecnico',
                null,
                array('query' => $query));
        }

    }

    public function livesearchAction()
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $this->_helper->getHelper('layout')->disableLayout();
            $this->_helper->viewRenderer->setNoRender();

            $param = $request->getParam('query');
            $malfunctions = $this->_tecnicoModel->getMalfunctionsByName($param, $paged=null, $order=null);
            $this->_helper->json($malfunctions, $sendNow = true, $keepLayouts = false, $encodeData = true);
        } else {
            return;
        }
    }

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}