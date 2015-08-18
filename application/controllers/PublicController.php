<?php
class PublicController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('main');
    }

    public function indexAction()
    {

    }

    public function viewstaticAction () {
        $page = $this->_getParam('staticPage');
        $this->render($page);
    }
}
