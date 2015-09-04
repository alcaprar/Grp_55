<?php
require_once('App/Form/Abstract.php');

class Application_Form_Staff_Malfunzionamento_Edit extends App_Form_Abstract
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('editmalfunction');
        $this->setAction('');

        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('StringLength',true, array(1,50))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'Malfunzionamento', array(
            'label' => 'Malfunzionamento: ',
            'cols' => '60', 'rows' => '5',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'Soluzione', array(
            'label' => 'Soluzione: ',
            'cols' => '60', 'rows' => '5',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Modifica Malfunzionamento',
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