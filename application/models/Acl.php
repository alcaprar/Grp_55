<?php

class Application_Model_Acl extends Zend_Acl
{
    public function __construct()
    {
        //definisco i ruoli
        $this->addRole(new Zend_Acl_Role('unregistered'));
        $this->addRole(new Zend_Acl_Role('tecnico'),'unregistered');
        $this->addRole(new Zend_Acl_Role('staff'),'tecnico');
        $this->addRole(new Zend_Acl_Role('admin'),'staff');

        //definisco le risorse
        $this->addResource(new Zend_Acl_Resource('error'));
        $this->addResource(new Zend_Acl_Resource('index'));
        $this->addResource(new Zend_Acl_Resource('public'));
        $this->addResource(new Zend_Acl_Resource('tecnico'));
        $this->addResource(new Zend_Acl_Resource('staff'));
        $this->addResource(new Zend_Acl_Resource('admin'));

        //definisco i privilegi
        $this->allow('unregistered',array('error','index','public'));
        $this->deny('unregistered','public','logout');
        $this->allow('tecnico','public','logout');
        $this->deny('tecnico','public','login');
        $this->allow('tecnico','tecnico');
        $this->allow('staff','staff');
        $this->allow('admin','admin');


        /*
        // ACL per ruolo di default, utente guest
        $this->addRole(new Zend_Acl_Role('unregistered'))
            ->add(new Zend_Acl_Resource('public'))
            ->add(new Zend_Acl_Resource('error'))
            ->add(new Zend_Acl_Resource('index'))
            ->allow('unregistered', array('public','error','index'));

        // ACL per tecnico
        $this->addRole(new Zend_Acl_Role('tecnico'), 'unregistered')
            ->add(new Zend_Acl_Resource('tecnico'))
            ->allow('tecnico','tecnico');

        // ACL per staff tecnico
        $this->addRole(new Zend_Acl_Role('staff'), 'tecnico')
            ->add(new Zend_Acl_Resource('staff'))
            ->allow('staff','staff');

        // ACL per admin
        $this->addRole(new Zend_Acl_Role('admin'), 'staff')
            ->add(new Zend_Acl_Resource('admin'))
            ->allow('admin','admin');
        */
    }
}