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
		$this->addElement('text', 'name', array(
            'label' => 'Nome',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,25))),
            'decorators' => $this->elementDecorators,
		));

		//tipo del prodotto, prima creare il model dell'admin
		/*$categories = array();
		$cats = $this->_adminModel->getSubCats();
		foreach ($cats as $cat) {
			$categories[$cat -> catId] = $cat->name;
		}
		$this->addElement('select', 'catId', array(
            'label' => 'Categoria',
            'required' => true,
			'multiOptions' => $categories,
			'decorators' => $this->elementDecorators,
		));
		*/

		//immagine del prodotto
		/*
		 	$this->addElement('file', 'image', array(
			'label' => 'Immagine',
			'destination' => APPLICATION_PATH . '/../public/images/products',
			'validators' => array(
			array('Count', false, 1),
			array('Size', false, 102400),
			array('Extension', false, array('jpg', 'gif'))),
            'decorators' => $this->fileDecorators,
			));
		*/

		$this->addElement('text', 'descShort', array(
            'label' => 'Descrizione Breve',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(array('StringLength',true, array(1,30))),
			'decorators' => $this->elementDecorators,
		));

		$this->addElement('textarea', 'descLong', array(
            'label' => 'Descrizione Estesa',
        	'cols' => '60', 'rows' => '20',
            'filters' => array('StringTrim'),
            'required' => true,
            'validators' => array(array('StringLength',true, array(1,2500))),
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