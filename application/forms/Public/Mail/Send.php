<?php
class Application_Form_Public_Mail_Send extends App_Form_Abstract
{
    public function init()
    {
        $this->setMethod('post');
        //Nome della form
        $this->setName('inviamail');
        //Azione da eseguire � definita nel controller che utilizza questa form
        $this->setAction('');

        //email del mittente
        $this->addElement('text', 'sender', array(
            'label' => 'Email mittente',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('EmailAddress',  true  ),
                array('StringLength',true, array(1,60))
            ),
            'decorators' => $this->elementDecorators,
        ));

        //Input mittente della mail
        $this->addElement('text', 'namesender', array(
            'filters'    => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(3, 30))),
            'required'   => true,
            'label'      => 'Nome e cognome del mittente',
            'decorators' => $this->elementDecorators,
        ))->setAttrib('placeholder', 'Nome e Cognome');;

        //Input per l'oggetto della mail
        $this->addElement('text', 'subject', array(
            'filters'    => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(3, 30))),
            'required'   => true,
            'label'      => 'Oggetto',
            'decorators' => $this->elementDecorators,
        ))->setAttrib('placeholder', 'Oggetto');;

        //corpo del messaggio
        $this->addElement('textarea', 'body', array(
            'label' => 'Messaggio',
            'cols' => '60', 'rows' => '10',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,5000))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'send', array(
            'label'    => 'Invia',
            'decorators' => $this->buttonDecorators,
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend', 'class' => 'formerrors')),
            'Form'
        ));
    }
}