<?php

class PublicController extends Zend_Controller_Action
{
    protected $_publicModel;
    protected $_logger;
    protected $_loginForm;
    protected $_emailForm;
    protected $_authService;
    protected $_redirector;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_logger = Zend_Registry::get('log');
        $this->_publicModel = new Application_Model_Public();
        $this->_authService = new Application_Service_Auth();
        $this->view->loginForm = $this->getLoginForm();
        $this->view->emailForm = $this->getEmailForm();

        //recupera le categorie Top
        $TopCats = $this->_publicModel->getTopCats();

        //recupera le categorie dal db attraverso il model
        //serve per il menu
        /*$CategorieA = $this->_publicModel->getCatsByParId('A');
        $CategorieM = $this->_publicModel->getCatsByParId('M');*/

        $navMenus = array();

        foreach($TopCats as $topcat) {
            $categorie = $this->_publicModel->getCatsByParId($topcat->Nome);
            $this->_logger->log($categorie, Zend_Log::DEBUG);
            $navCatArray = array();
            foreach($categorie as $cat)
            {
                $this->_logger->log($cat, Zend_Log::DEBUG);
                $navCatArray[] = array(
                    'controller' => 'public',
                    'action' => 'catalogo',
                    'params' => array('categoria' => $cat->Nome),
                    'label' => $cat->Nome,
                    'resource' => 'public',
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
            return $this->_helper->redirector('index', 'public');
        }

        //se non è nullo recupero il prodotto
        $prodotto = $this->_publicModel->getProdById($idProdotto);

        // Definisce le variabili per il viewer
        $this->view->assign(array(
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

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'Categoria' => $nomeCategoria,
                'Prodotti' => $prodotti
            )
        );
    }

    public function cercaAction()
    {
        //recupero i parametri
        $query = $this->_getParam('query', null);
        if(strpos($query, '*') == 0){
            $query = str_replace($query, '', 0, strlen('*'));
        }
        $page = $this->_getParam('page', 1);

            //se non è nullo recupero i prodotti
            $prodotti = $this->_publicModel->getProdByName($query, $page, $order=null);

            $this->_logger->log($prodotti,Zend_Log::DEBUG);

            // Definisce le variabili per il viewer
            $this->view->assign(array(
                    'query' => $query,
                    'Prodotti' => $prodotti
                )
            );


    }

    public function redirectorurlcercaAction()
    {
        $request = $this->getRequest();

        //arrivata una richiesta di cerca
        if ($request->isPost()) {
            $query = $request->getPost()['query'];
            $this->_redirector = $this->_helper->getHelper('Redirector');

            $this->_redirector->gotoSimple('cerca',
                'public',
                null,
                array('query' => $query));
        }

    }

    //da completare per la ricerca live

    public function livesearchAction()
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $this->_helper->getHelper('layout')->disableLayout();
            $this->_helper->viewRenderer->setNoRender();

            $param = $request->getParam('query');
            $products = $this->_publicModel->getProdByName($param, $page = null, $order = null);
            $this->_logger->log($products, Zend_Log::DEBUG);
            $data = array();
            foreach ($products as $key=>$value) {
                $data[$key] = $value;
            };
            $this->_helper->json($products, $sendNow = true, $keepLayouts = false, $encodeData = true);
        } else {
            return;
        }
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

        } elseif($page =='where')
        {

            $centri = $this->_publicModel->selectCentro($paged=null,$order=null);

            $tecnici = array();

            foreach($centri as $centro)
            {
                $tecnici[$centro['id']] =$this->_publicModel->selectAppartenenza($centro['id']);
            }

            //$this->_logger->log($tecnici,Zend_Log::DEBUG);
            $this->view->assign(array(
                'Centri'=>$centri,
                'Tecnici'=>$tecnici
                )
            );
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

    public function inviamailAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->_helper->redirector('viewstatic', 'public', 'default', array('page' => 'contact'));
        }
        $form = $this->_emailForm;
        if (!$form->isValid($request->getPost())) {
            $form->setDescription('Attenzione: alcuni dati inseriti sono errati.');
            return $this->render('contact');
        }
        try{
            $values = $form->getValues();
            $this->_logger->log($values,Zend_Log::DEBUG);
            $mail = new Zend_Mail();
            $mail->setBodyText($form->getElement('body'))
                ->setFrom($values['sender'], $values['namesender'])
                ->addTo('grp55tw@gmail.com', 'BMW Assistance')
                ->setSubject($values['subject'])
                ->setBodyText($values['body'])
                ->send();
        } catch (Zend_Mail_Transport_Exception $e){
            $this->_logger->log('C\'é stato un problema con l\'invio', Zend_Log::DEBUG);
            $form->setDescription('C\'é stato un problema con l\'invio, riprovare.');
            return $this->render('contact');
        }
    }

    public function successomailAction()
    {
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

    private function getEmailForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_emailForm = new Application_Form_Public_Mail_Send();
        $this->_emailForm->setAction($urlHelper->url(array(
            'controller' => 'public',
            'action' => 'inviamail'
        ),
            'default'
        ));
        return $this->_emailForm;
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

    //Cancella l'identità e poi reindirizza all'azione index del controller public
    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index', 'public');
    }
}

