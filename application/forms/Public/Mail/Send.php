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
            'filters' => array('StringTrim'),
            'placeholder' => 'Email',
            'class' => 'form-control',
            'required' => true,
            'validators' => array(
                array('EmailAddress',  true  ),
                array('StringLength',true, array(1,60))
            ),
            'decorators' =>array(
                array('ViewHelper'),
                array('HtmlTag', array('tag' => 'div', 'class'=>'form-group col-md-6')),
                array('Errors', array('class'=>'error col-md-12')),
            )
        ));

        //Input mittente della mail
        $this->addElement('text', 'namesender', array(
            'filters'    => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(3, 30))),
            'required'   => true,
            'class' => 'form-control',
            'decorators' =>array(
                array('ViewHelper'),
                array('HtmlTag', array('tag' => 'div', 'class'=>'form-group col-md-6')),
                array('Errors', array('class'=>'error col-md-12')),
            ),
            'placeholder' => 'Nome e cognome del mittente',
        ));


        //Input per l'oggetto della mail
        $this->addElement('text', 'subject', array(
            'filters'    => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(3, 30))),
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'Oggetto',
            'decorators' =>array(
                array('ViewHelper'),
                array('HtmlTag', array('tag' => 'div', 'class'=>'form-group col-md-12')),
                array('Errors', array('class'=>'error col-md-12')),
            ),
        ))->setAttrib('placeholder', 'Oggetto');;

        //corpo del messaggio
        $this->addElement('textarea', 'body', array(
            'placeholder' => 'Scrivi il tuo messaggio qui',
            'cols' => '60', 'rows' => '10',
            'filters' => array('StringTrim'),
            'required' => true,
            'class' => 'form-control',
            'validators' => array(array('StringLength',true, array(1,5000))),
            'decorators' =>array(
                array('ViewHelper'),
                array('HtmlTag', array('tag' => 'div', 'class'=>'form-group col-md-12')),
                array('Errors', array('class'=>'error col-md-12')),
            ),
        ));

        $this->addElement('submit', 'send', array(
            'label'    => 'Invia',
            'class' => 'btn btn-primary pull-right name',
                'decorators' =>array(
                    array('ViewHelper'),
                    array('HtmlTag', array('tag' => 'div', 'class'=>'form-group col-md-12')),
                    array('Errors', array('class'=>'error')),
                ),
            )
        );

    }
}