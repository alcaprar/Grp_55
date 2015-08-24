<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Centri_Add extends App_Form_Abstract
{
    protected $_adminModel;

    public function init()
    {
        $this->setMethod('post');
        $this->setName('addcenter');
        $this->setAction('');

        //nome del centro
        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,45))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'Indirizzo', array(
            'label' => 'Indirizzo: ',
            'cols' => '50', 'rows' => '1',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,250))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Aggiungi Centro Assistenza',
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