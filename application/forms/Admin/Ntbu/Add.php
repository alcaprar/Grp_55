<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Ntbu_Add extends App_Form_Abstract
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('addntbu');
        $this->setAction('');


        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,25))),
            'decorators' => $this->elementDecorators,
        ));


        $this->addElement('textarea', 'Descrizione', array(
            'label' => 'Descrizione ',
            'cols' => '60', 'rows' => '20',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Aggiungi Ntbu',
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