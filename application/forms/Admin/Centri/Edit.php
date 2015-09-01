<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Centri_Edit extends App_Form_Abstract
{
    protected $_adminModel;
    protected $_default;
    protected $_id = '';
    protected $_logger;

    public function init()
    {
        $this->setMethod('post');
        $this->setName('editcenter');
        $this->setAction('');

        //nome del centro
        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,45))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Indirizzo', array(
            'label' => 'Indirizzo',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,250))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Telefono', array(
            'label' => 'Telefono',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('StringLength',true, array(5,20)),
                array('regex' , true, array(
                    'pattern'=> '/^\+[0-9 ]+$/'
                ))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Mobile', array(
            'label' => 'Cellulare',
            'filters' => array('StringTrim'),
            'required' => false,
            'validators' => array(
                array('StringLength',true, array(5,20)),
                array('regex' , true, array(
                    'pattern'=> '/^\+[0-9 ]+$/'
                ))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Fax', array(
            'label' => 'Fax',
            'filters' => array('StringTrim'),
            'required' => false,
            'validators' => array(
                array('StringLength',true, array(5,20)),
                array('regex' , true, array(
                    'pattern'=> '/^\+[0-9 ]+$/'
                ))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Skype', array(
            'label' => 'Skype',
            'filters' => array('StringTrim'),
            'required' => false,
            'validators' => array(
                array('StringLength',true, array(5,20)),
                array('regex' , true, array(
                    'pattern'=> '/^\+[0-9 ]+$/'
                ))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Email', array(
            'label' => 'Email',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('EmailAddress',  true  ),
                array('StringLength',true, array(1,100))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Modifica Centro Assistenza',
            'decorators' => $this->buttonDecorators,
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            array('Description', array('placement' => 'prepend', 'class' => 'formerror')),
            'Form'
        ));
    }

    public function populate($data)
    {
        foreach($data as $field => $value)
        {
            $this->{$field}->setValue($value);
        }
        return $this;
    }
}