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
        //recupera le categorie dal db attraverso il model
        //serve per il menu
        $CategorieA = $this->_tecnicoModel->getCatsByParId('A');
        $CategorieM = $this->_tecnicoModel->getCatsByParId('M');


        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM
            )
        );
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
                $malfunctions[] = $malfunction->Malfunzionamento;
            }
        }

        //recupero le categorie per il menu
        $CategorieA = $this->_tecnicoModel->getCatsByParId('A');
        $CategorieM = $this->_tecnicoModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Prodotto' => $prodotto,
                'Componenti' => $components,
                'Malfunzionamenti' => $malfunctions
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

        //recupero le categorie per il menu
        $CategorieA = $this->_tecnicoModel->getCatsByParId('A');
        $CategorieM = $this->_tecnicoModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
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

        //recupero le categorie per il menu
        $CategorieA = $this->_tecnicoModel->getCatsByParId('A');
        $CategorieM = $this->_tecnicoModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
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