<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Product_Edit extends App_Form_Abstract
{
    protected $_adminModel;
    protected $_default;
    protected $_id = '';
    protected $_logger;

    public function init()
    {
        $this->_adminModel = new Application_Model_Admin();
        $this->setMethod('post');
        $this->setName('editproduct');
        $this->setAction('');

        //recupero il prodotto da modificare
        //$this->_default = $this->_adminModel->getProdById($this->_id);

        //nome del prodotto
        $this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength', true, array(1, 25))),
            'decorators' => $this->elementDecorators,
        ));


        $this->addElement('text', 'DescrizioneBreve', array(
            'label' => 'Descrizione Breve',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(array('StringLength', true, array(1, 250))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'DescrizioneLunga', array(
            'label' => 'Descrizione Estesa',
            'cols' => '60', 'rows' => '20',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength', true, array(1, 2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'NTBU', array(
            'label' => 'Note tecniche di buon uso',
            'cols' => '60', 'rows' => '20',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength', true, array(1, 2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('textarea', 'ModInstallazione', array(
            'label' => 'Modalità di installazione particolari',
            'cols' => '60', 'rows' => '20',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength', true, array(1, 2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Cilindrata', array(
            'label' => 'Cilindrata',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('Int', true),
                array('Between', true, array(0, 10000))
            ),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Potenza', array(
            'label' => 'Potenza (kW)',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0))
            ),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'MassaVuoto', array(
            'label' => 'Massa a vuoto',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0))
            ),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'VelocitaMax', array(
            'label' => 'Velocità massima',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0))
            ),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Modifica Prodotto',
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