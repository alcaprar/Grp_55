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
        //recupera le categorie dal db attraverso il model
        //serve per il menu
        $CategorieA = $this->_publicModel->getCatsByParId('A');
        $CategorieM = $this->_publicModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
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
            return $this->_helper->redirector('index', 'public');
        }

        //se non è nullo recupero il prodotto
        $prodotto = $this->_publicModel->getProdById($idProdotto);

        //recupero le categorie per il menu
        $CategorieA = $this->_publicModel->getCatsByParId('A');
        $CategorieM = $this->_publicModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Prodotto' => $prodotto
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
            return $this->_helper->redirector('index', 'public');
        }

        //se non è nullo recupero i prodotti
        $prodotti = $this->_publicModel->getProdsByCat2($nomeCategoria, $paged, $order=null);

        //recupero le categorie per il menu
        $CategorieA = $this->_publicModel->getCatsByParId('A');
        $CategorieM = $this->_publicModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieA' => $CategorieA,
                'CategorieM' => $CategorieM,
                'Categoria' => $nomeCategoria,
                'Prodotti' => $prodotti
            )
        );
    }

    public function viewstaticAction()
    {
        //recupero la pagina statica
        $page = $this->_getParam('page', null);

        //se è nulla faccio il redirector sulla index action
        //pagine statiche
        if(is_null($page))
        {
            return $this->_helper->redirector('index', 'public');
        }

        //se è la pagina delle faq recupero le faq
        if($page=='faq')
        {
            $faq = $this->_publicModel->selectFaq($paged=null,$order=null);
            // Definisce le variabili per il viewer
            $this->view->assign('Faq', $faq);

        }
        //se non è nulla faccio il render della pagina
        $this->render($page);
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

    public function accessonegatoAction()
    {
        $redirect = $this->_helper->getHelper('url')->url(array(
            'controller' => 'public',
            'action' => 'index'
        ),
            'default'
        );
        $this->view->assign(array('redirect' => $redirect));
    }
}

