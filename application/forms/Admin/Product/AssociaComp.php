<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Product_AssociaComp extends App_Form_Abstract
{

    public function init()
    {
        $this->setMethod('post');
        $this->setName('associateproduct');
        $this->setAction('');



        $this->addElement('select', 'idProdotto',array(
            'label' => 'Prodotto',
            'required' =>true,
            'decorators' => $this->elementDecorators,
            'registerInArrayValidator' => false
        ));


        $this->addElement('multiCheckbox', 'Componenti', array(
                'label' => 'Componenti',
                'required' => 'true',
                'decorators' => $this->elementDecorators,
                'registerInArrayValidator' => false
            )
        );



        $this->addElement('submit', 'add', array(
            'label' => 'Associa componenti',
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