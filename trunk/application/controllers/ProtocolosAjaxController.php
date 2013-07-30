<?php

class ProtocolosAjaxController extends BaseAuthenticatedController
{

    public function listarDestinosAction()
    {
        $this->_helper->layout()->disableLayout();

        $tipoProcessoId = $this->_getTipoProcessoId() ? $this->_getTipoProcessoId() : null;

        $this->view->resultados = $this->_getListaProtocolosDestino($this->_getProtocoloOrigemId(), $tipoProcessoId);
    }

    protected function _getListaProtocolosDestino($protocoloOrigemId, $tipoProcessoId = null, $offset = 0)
    {
        $service = new Spu_Service_Protocolo($this->getTicket());
        return $service->getProtocolosDestino($protocoloOrigemId, $tipoProcessoId, $this->_getSearchTerm(), $offset, $this->_helper->paginator()->getPageSize());
    }

    protected function _getSearchTerm()
    {
        return $this->getRequest()->getParam('term', null);
    }

    protected function _getProtocoloOrigemId()
    {
        return $this->getRequest()->getParam('protocoloOrigem', null);
    }

    protected function _getTipoProcessoId()
    {
        return $this->getRequest()->getParam('tipoprocesso', null);
    }

    public function listarAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->resultados = $this->_getProtocolosAutocomplete();
    }

    protected function _getProtocolosAutocomplete()
    {
        $service = new Spu_Service_Protocolo($this->getTicket());
        return $service->getTodosProtocolosPaginado($this->_getOffset(), $this->_getPageSize(), $this->_getSearchTerm());
    }

    public function listarDestinosFilhosAction()
    {
        $service = new Spu_Service_Protocolo($this->getTicket());
        $origem = $this->_getParam('origem');
        $lotacoes = $this->_getProtocoloOrigem();
        if (count($lotacoes) == 1) {
            if (!$origem) $origem = $lotacoes;
        } else {
            foreach ($lotacoes as $value) {
                $prot = $service->getProtocolosFilhos($this->_getParam('parent-id'), $value);
                if(count($prot) > 1) $protocolos = $prot;
            }
        }
        
        if(count($protocolos) == 0)
            $protocolos = $service->getProtocolosFilhos($this->_getParam('parent-id'), $origem);

        $this->_helper->json($this->_arrayProtocolos($protocolos), true);
    }
    
    private function _arrayProtocolos($protocolos)
    {
        $protocolosJson = array();
        $nodeRefsExcludeds = $this->getExcludeNodeRed();
        
        foreach ($protocolos as $protocolo) {
            $nodeRefExcluded = in_array($protocolo->id, $nodeRefsExcludeds);
            if (count($protocolos) == 1 && strpbrk($protocolo->path, "/") != false)
                $protocolosJson[$protocolo->nome . " - " . $protocolo->descricao] = array('id' => $protocolo->id, 'name' => $protocolo->nome . " - " . $protocolo->descricao, 'description' => $protocolo->path);
            elseif (!$nodeRefExcluded) {
                $protocolosJson[$protocolo->nome . " - " . $protocolo->descricao] = array('id' => $protocolo->id, 'name' => $protocolo->nome . " - " . $protocolo->descricao, 'description' => $protocolo->path);
            }
        }

        ksort($protocolosJson);
        
        return array_values($protocolosJson);
    }

    public function _getProtocoloOrigem()
    {
        $protocoloService = new Spu_Service_Protocolo($this->getTicket());
        $protocolos = $protocoloService->getProtocolos();

        if (count($protocolos) != 1) {
            $protocolos_ids = array();
            foreach ($protocolos as $value)
                $protocolos_ids[] = $value->id;
            
            return $protocolos_ids;
        }

        return $protocolos[0]->id;
    }

}
