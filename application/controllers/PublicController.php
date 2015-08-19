<?php

class PublicController extends Zend_Controller_Action
{
    protected $_catalogModel;

    public function init()
    {
        $this->_helper->layout->setLayout('layout');
        $this->_catalogModel = new Application_Model_Catalog();
    }

    public function indexAction()
    {
        //Estrae le sottocategorie e le inserisce nella sidebar

        $CategorieAM = $this->_catalogModel->getCatsByParId('AM');
        $CategorieM = $this->_catalogModel->getCatsByParId('M');

        // Definisce le variabili per il viewer
        $this->view->assign(array(
                'CategorieAM' => $CategorieAM,
                'CategorieM' => $CategorieM
            )
        );

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }
}

