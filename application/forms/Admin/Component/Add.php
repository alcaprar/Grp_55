<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Component_Add extends App_Form_Abstract
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('addcomponent');
        $this->setAction('');

        //nome del prodotto
        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,100))),
            'decorators' => $this->elementDecorators,
        ));


		$this->addElement('file', 'Foto', array(
			'label' => 'Immagine',
			'destination' => APPLICATION_PATH . '/../public/images/component',
			'validators' => array(
			array('Count', false, 1),
			array('Size', false, 102400,array(
                'messages' => 'File non valido.'
            )),
			array('Extension', false, array('jpg', 'gif'))),
            'decorators' => $this->fileDecorators,
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
            'label' => 'Aggiungi Componente',
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