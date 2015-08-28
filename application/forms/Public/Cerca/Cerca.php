<?php

class Application_Form_Public_Cerca_Cerca extends App_Form_Abstract
{
    public function init()
    {
        $this->setMethod('post');
        //Nome della form
        $this->setName('cerca');
        //Azione da eseguire è definita nel controller che utilizza questa form
        $this->setAction('');

        //Input per lo username
        $this->addElement('text', 'query', array(
            'filters'    => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(3, 16)),
                array('regex', true, array(
                    'pattern'   => '/^([A-Za-z0-9_\-\.\@\?]){3,16}$/')),
               ),
        ))->setAttrib('placeholder', 'Cerca prodotti');;


        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend', 'class' => 'formerrors')),
            'Form'
        ));
    }
}