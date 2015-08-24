<?php

class PublicController extends Zend_Controller_Action
{
    protected $_catalogModel;
    protected $_logger;
    protected $_loginForm;
    protected $_authService;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_logger = Zend_Registry::get('log');
        $this->_publicModel = new Application_Model_Public();
        $this->_authService = new Application_Service_Auth();
        $this->view->loginForm = $this->getLoginForm();
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
        }


        //recupera le categorie dal db attraverso il model
        //serve per il menu
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

        //pagine statiche
        if(!is_null($viewStatic))
        {
            $this->render($viewStatic);
        }

    }

    //carica la view per la form di login
    public function loginAction()
    {
    }

    //metodo per la verifica del login
    public function effettualoginAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }
        $form = $this->_loginForm;
        if (!$form->isValid($request->getPost())) {
            $form->setDescription('Attenzione: alcuni dati inseriti sono errati.');
            return $this->render('login');
        }

        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Autenticazione fallita. Riprova');
            return $this->render('login');
        }
        return $this->_helper->redirector('index', $this->_authService->getIdentity()->Ruolo);
    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }

    //ottiene la form di login, richiamato dall'action login
    private function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_loginForm = new Application_Form_Public_Auth_Login();
        $this->_loginForm->setAction($urlHelper->url(array(
            'controller' => 'public',
            'action' => 'effettualogin'
            ),
            'default'
        ));
        return $this->_loginForm;
    }
}

