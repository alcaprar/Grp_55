<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $this->_helper->redirector('index','public');
    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }

}

