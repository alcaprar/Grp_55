<?php
//Classe che implementa la Form di Login, è in Public perchè è associata al controller Public (che la gestisce)
//La cartella Auth mi definisce a cosa serve la Form che sto creando (per l'autorizzazione appunto)
class Application_Form_Public_Auth_Login extends App_Form_Abstract
{
    public function init()
    {
        $this->setMethod('post');
        //Nome della form
        $this->setName('autentica');
        //Azione da eseguire è definita nel controller che utilizza questa form
        $this->setAction('');

        //Input per lo username
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(3, 16)),
                array('regex', true, array(
                    'pattern'   => '/^([A-Za-z0-9_\-\.\@\?]){3,16}$/')),
                array('Db_RecordExists', true, array(
                    'table' => 'Utenti',
                    'field' => 'Username',
                    'messages' => array(
                        'noRecordFound' => "Utente con username '%value%' non esistente."
                    )))),
            'required'   => true,
            'label'      => 'Username',
            'decorators' => $this->elementDecorators,
        ))->setAttrib('placeholder', 'Username');;

        //Input per la passoword
        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 32)),
                array('regex', true, array(
                    'pattern'   => '/^([A-Za-z0-9_\-\.\@\?]){3,32}$/'))),
            'required'   => true,
            'label'      => 'Password',
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'login', array(
            'label'    => 'Login',
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
