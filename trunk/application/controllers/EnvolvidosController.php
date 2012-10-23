<?php

class EnvolvidosController extends BaseController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector(null, null, null, array('q' => trim(str_replace("/", "%2F", $_POST['q']))));
        }

        if ($this->_getParam('q')) {
            $postData["login"] = $this->getUser()->login;
            $postData += $this->_filterDados();
            
            $processoService = new Spu_Service_Processo($this->getTicketSearch());
            $resultado = $processoService->consultar($postData, 0, 4999);
            
            $this->view->totalDocumentos = count($resultado);
            $this->view->paginator = $this->_helper->paginator()->paginate($resultado);
        } else {
            $this->setMessageForTheView('Por favor, busque pelo Nome, CPF ou CNPJ.');
        }

        $this->view->q = str_replace("%2F", "/", $this->_getParam('q'));
    }
    
    private function _filterDados()
    {
        $param =  str_replace("%2F", "/", $this->_getParam('q'));
        $data = array("envolvido" => "", "cpf" => "");
        
        if (is_numeric(str_replace(array(".", "-", "/"), "", $param)))
            $data["cpf"] = $param;
        else
            $data["envolvido"] = $param;
        
        return $data;
            
    }

    public function detalhesAction()
    {
        $manifestanteService = new Spu_Service_Manifestante($this->getTicketSearch());
        $manifestante = $manifestanteService->getManifestante($this->_getCpfFromUrl());

        $this->view->manifestante = $manifestante;
        $this->view->id = $manifestante->getCpf();
        $this->view->isEdit = true;
    }

    private function _getCpfFromUrl()
    {
        $cpfCnpj = $this->getRequest()->getParam('term', null);
        
        if (strlen($cpfCnpj) == 14)
            return str_replace(array(".", "-"), "", $cpfCnpj);
        
        return  $cpfCnpj;
    }
}