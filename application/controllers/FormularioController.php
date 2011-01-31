<?php 
Loader::loadDao('Arquivo');
class FormularioController extends BaseController
{
    public function contentAction()
    {
        $id = $this->getRequest()->getParam('id');
        $this->_helper->layout()->disableLayout();
        $dao = new ArquivoDao($this->getTicket());
        $params = array("id" => $id, "nome" => "coiso.xsd");
        $result = $dao->getContentFromUrl($params);
        $this->view->result = $result;
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $this->view->id = $id;
    }
}
