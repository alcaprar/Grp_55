<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_TopCategory_Edit extends App_Form_Abstract
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('edittopcategory');
        $this->setAction('');

        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,45))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Modifica Macro-Categoria',
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