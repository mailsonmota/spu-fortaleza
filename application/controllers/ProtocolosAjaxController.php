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
        $protocolos = $service->getProtocolosFilhos($this->_getParam('parent-id'), $this->_getParam('origem'));

        $protocolosJson = array();
        $this->_mySort($protocolos, 'descricao');
        foreach ($protocolos as $protocolo) {
            $name = strpos($protocolo->path, '/') ? substr($protocolo->path, strpos($protocolo->path, '/') + 1) : $protocolo->path ;
            $shortname = explode("/", $name);
            $shortname = array_pop($shortname) . " - " . $protocolo->descricao;
            $protocolosJson[] = array('id' => $protocolo->id, 'name' => $shortname, 'description' => $name);
        }
        $this->_helper->json($protocolosJson, true);
    }

    private function _mySort(&$obj, $prop)
    {
        uasort($obj, function($a, $b) use ($prop) {
                return $a->$prop > $b->$prop ? 1 : -1;
            });
    }

}
