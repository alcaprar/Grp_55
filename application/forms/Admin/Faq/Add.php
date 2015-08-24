<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Faq_Add extends App_Form_Abstract
{
    protected $_adminModel;

    public function init()
    {
        //$this->_adminModel = new Application_Model_Admin();
        $this->setMethod('post');
        $this->setName('addfaq');
        $this->setAction('');

        $this->addElement('textarea', 'Domanda', array(
            'label' => 'Domanda: ',
            'cols' => '60', 'rows' => '5',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'Risposta', array(
            'label' => 'Risposta: ',
            'cols' => '60', 'rows' => '5',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Aggiungi FAQ',
            'decorators' => $this->buttonDecorators,
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            array('Description', array('placement' => 'prepend', 'class' => 'formerror')),
            'Form'
        ));
    }
}