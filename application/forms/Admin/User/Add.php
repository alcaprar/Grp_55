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
            'validators' => array(
                array('StringLength',true, array(1,45)),
                array('Alpha',true)),
            'decorators' => $this->elementDecorators,
        ));

        //cognome dell'utente
        $this->addElement('text', 'Cognome', array(
            'label' => 'Cognome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('StringLength',true, array(1,45)),
                array('Alpha',true)),
            'decorators' => $this->elementDecorators,
        ));


        $email = $this->createElement('text', 'Email', array(
            'label' => 'Email',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('EmailAddress',  true),
                array('StringLength',true, array(1,60)),
                array('Db_NoRecordExists', true, array(
                    'table' => 'Utenti',
                    'field' => 'Email',
                    'messages' => array(
                        'recordFound' => "Email già esistente."
                    )))
            ),
            'decorators' => $this->elementDecorators,
        ));
        $email->addErrorMessage('Email non valida!');

        $this->addElement($email);

        //username dell'utente
        $this->addElement('text', 'Username', array(
            'label' => 'Username',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,25)),
            array('regex', true, array(
                'pattern'   => '/^([A-Za-z0-9_\-\.\@\?]){3,16}$/')),
                array('Db_NoRecordExists', true, array(
                    'table' => 'Utenti',
                    'field' => 'Username',
                    'messages' => array(
                        'recordFound' => "Utente già esistente."
                    )))),
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
                'tecnico' => 'Tecnico',
                'staff' => 'Staff amministrazione',
            ),
        ));

        $this->addElement('select', 'centri',array(
            'label' => 'Centro di appartenenza',
            'required' =>false,
            'decorators' => $this->elementDecorators,
            'registerInArrayValidator' => false
        ));

        $this->addElement('multiCheckbox', 'Categorie', array(
                'label' => 'Categorie che può gestire',
                'required' => false,
                'decorators' => $this->elementDecorators,
                'registerInArrayValidator' => false,
            )
        );

        $urlHelper = new Zend_View_Helper_Url();
        $url = $urlHelper->url(array(
            'controller' => 'admin',
            'action' => 'addcategory',
        ),
            'default'
        );

        $this->addElement('Note','AddCategory',array(
            'value' => '<a class ="addcategory" href="'.$url.'">Inserisci una nuova categoria</a>',
            'decorators' => $this->elementDecorators,
            'class' => 'addcategory'
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