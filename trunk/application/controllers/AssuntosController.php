<?php
Loader::loadEntity('TipoAssunto.php');
class AssuntosController extends BaseCrudController
{
    const entity = 'TipoAssunto';
    
    public function formulariosAction() 
    {
        $this->objeto->__construct(NULL, $this->getRequest()->getParam('assunto', NULL));
        $this->view->objeto = $this->objeto;
    }
}