<?php
require_once('App/Form/Abstract.php');

class Application_Form_Admin_Product_Add extends App_Form_Abstract
{
	protected $_adminModel;

	public function init()
	{
		//$this->_adminModel = new Application_Model_Admin();
		$this->setMethod('post');
		$this->setName('addproduct');
		$this->setAction('');

		//nome del prodotto
		$this->addElement('text', 'Nome', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,25))),
            'decorators' => $this->elementDecorators,
		));

        $this->addElement('select', 'idCategoria',array(
            'label' => 'Categoria',
            'required' =>true,
            'decorators' => $this->elementDecorators,
            'registerInArrayValidator' => false
        ));

        $this->addElement('file', 'Foto', array(
            'label' => 'Immagine',
            'destination' => APPLICATION_PATH . '/../public/images/products',
            'validators' => array(
                array('Count', false, 1),
                array('Size', false, 102400),
                array('Extension', false, array('jpg', 'gif'))),
            'decorators' => $this->fileDecorators,
        ));

		$this->addElement('text', 'DescrizioneBreve', array(
            'label' => 'Descrizione Breve',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(array('StringLength',true, array(1,250))),
			'decorators' => $this->elementDecorators,
		));

		$this->addElement('textarea', 'DescrizioneLunga', array(
            'label' => 'Descrizione Estesa',
        	'cols' => '60', 'rows' => '10',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
			'decorators' => $this->elementDecorators,
		));

        $this->addElement('multiCheckbox', 'Componenti', array(
                'label' => 'Componenti',
                'required' => 'true',
                'decorators' => $this->elementDecorators,
                'registerInArrayValidator' => false
            )
        );

		$this->addElement('textarea', 'NTBU', array(
			'label' => 'Note tecniche di buon uso',
			'cols' => '60', 'rows' => '5',
			'filters' => array('StringTrim'),
			'required' => true,
			'validators' => array(array('StringLength',true, array(1,2500))),
			'decorators' => $this->elementDecorators,
		));

        $this->addElement('textarea', 'ModInstallazione', array(
            'label' => 'Modalità di installazione particolari',
            'cols' => '60', 'rows' => '5',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('text', 'Cilindrata', array(
            'label' => 'Cilindrata',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(
				array('Int', true),
				array('Between', true, array(0,10000))
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
            'label' => 'Aggiungi Prodotto',
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