<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_User_Add extends App_Form_Abstract
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('adduser');
        $this->setAction('');

        //nome dell'utente
        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,45))),
            'decorators' => $this->elementDecorators,
        ));

        //cognome dell'utente
        $this->addElement('text', 'Cognome', array(
            'label' => 'Cognome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,45))),
            'decorators' => $this->elementDecorators,
        ));

        //email dell'utente
        $this->addElement('text', 'Email', array(
            'label' => 'Email',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('EmailAddress',  true  ),
                array('StringLength',true, array(1,60))
            ),
            'decorators' => $this->elementDecorators,
        ));

        //username dell'utente
        $this->addElement('text', 'Username', array(
            'label' => 'Username',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,25))),
            'decorators' => $this->elementDecorators,
        ));

        //password
        $this->addElement('text', 'Password', array(
            'label' => 'Password',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,25))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('select', 'Ruolo',array(
            'label' => 'Ruolo',
            'required' =>true,
            'decorators' => $this->elementDecorators,
            'registerInArrayValidator' => false,
            'multiOptions' => array(
                'tec' => 'Tecnico',
                'staff' => 'Staff amministrazione',
                'admin' => 'Amministratore sito',
            ),
        ));


        $this->addElement('submit', 'add', array(
            'label' => 'Aggiungi utente',
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