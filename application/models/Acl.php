<?php

class Application_Model_Acl extends Zend_Acl
{
    public function __construct()
    {
        // ACL per ruolo di default, utente guest
        $this->addRole(new Zend_Acl_Role('unregistered'))
            ->add(new Zend_Acl_Resource('public'))
            ->add(new Zend_Acl_Resource('error'))
            ->add(new Zend_Acl_Resource('index'))
            ->allow('unregistered', array('public','error','index'));

        // ACL per tecnico
        $this->addRole(new Zend_Acl_Role('tec'), 'unregistered')
            ->add(new Zend_Acl_Resource('tec'))
            ->allow('tec','tec');

        // ACL per staff tecnico
        $this->addRole(new Zend_Acl_Role('staff'), 'tec')
            ->add(new Zend_Acl_Resource('staff'))
            ->allow('staff','staff');

        // ACL per admin
        $this->addRole(new Zend_Acl_Role('admin'), 'staff')
            ->add(new Zend_Acl_Resource('admin'))
            ->allow('admin','admin');
    }
}