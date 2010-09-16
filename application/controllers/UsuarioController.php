<?php

Loader::loadAlfrescoApiClass('AlfrescoPeople');
Loader::loadEntity('Usuario');
class UsuarioController extends BaseController
{
    public function cadastroAction()
    {
    	$authInstance = Zend_Auth::getInstance()->getIdentity();
        $this->view->person = $authInstance['user'];
    }
}