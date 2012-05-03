<?php

class EnvolvidosAjaxController extends BaseController
{

    public function envolvidoAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->manifestante = $this->_getManifestante($this->_getSearchTerm());
    }

    protected function _getManifestante($cpfCnpj)
    {
        $manifestanteService = new Spu_Service_Manifestante($this->getTicketSearch());
        return $manifestanteService->getManifestante($cpfCnpj);
    }

    protected function _getSearchTerm()
    {
        $cpfCnpj = $this->getRequest()->getParam('term', null);

        if (strlen($cpfCnpj) == 14)
            return str_replace(array(".", "-"), "", $cpfCnpj);

        return $cpfCnpj;
    }

    public function manifestantesAction()
    {
        $this->_helper->layout->disableLayout();
        sleep(2);

        if ($this->getRequest()->isPost()) {

            $processoService = new Spu_Service_Processo($this->getTicketSearch());
            $postData["envolvido"] = $this->getRequest()->getPost("nome");
            
            $this->view->manifestantes = $this->_filtrarDadosManifestantes($processoService->consultar($postData, 0, 4999));
        } else
            die();
    }
    
    private function _filtrarDadosManifestantes($man)
    {
        $dadosUnicos = array();
        
        foreach ($man as $value)
            $dadosUnicos[$value->manifestante->nome] = $value->manifestante->cpf;
        
        $manifestantes = array();
        foreach ($dadosUnicos as $key => $value)
            $manifestantes[] = array("nome" => $key, "cpf" => $value );
        
        return $manifestantes;
    }

}