<?php
Loader::loadEntity('Processo');
Loader::loadEntity('Bairro');
Loader::loadEntity('Protocolo');
class AbrirprocessoController extends BaseController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector(
                'formulario', 
                $this->getController(), 
                'default',
                array('tipoprocesso' => $this->_getIdTipoProcessoPost())
            );
        }
        
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $listaTiposProcesso = $this->_getListaTiposProcesso();
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposProcesso = $listaTiposProcesso;
    }
    
    protected function _getIdTipoProcessoPost()
    {
        return ($this->getRequest()->getParam('tipoprocesso')) ? $this->getRequest()->getParam('tipoprocesso') : null;
    }
    
    protected function _getListaTiposProcesso()
    {
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $tiposProcesso = $tipoProcesso->listar();
        $listaTiposProcesso = array();
        foreach ($tiposProcesso as $tipoProcesso) {
            $listaTiposProcesso[$tipoProcesso->id] = $tipoProcesso->nome;
        }
        
        return $listaTiposProcesso;
    }
    
    public function formularioAction()
    {
        try {
            $tipoProcesso = $this->_getTipoProcesso($this->_getIdTipoProcessoUrl());
            $listaTiposProcesso = $this->_getListaTiposProcesso();
            $listaAssuntos = $this->_getListaAssuntos($tipoProcesso);
            $listaBairros = $this->_getListaBairros();
            $listaTiposManifestante = $this->_getListaTiposManifestante($tipoProcesso);
            $listaPrioridades = $this->_getListaPrioridades();
            $listaProtocolos = $this->_getListaProtocolos();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEscolhaTipoProcesso();
        }
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();
            $processoObj = new Processo($this->getTicket());
            $return = $processoObj->abrirProcesso($postData);
        }
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposProcesso = $listaTiposProcesso;
        $this->view->listaAssuntos = $listaAssuntos;
        $this->view->listaBairros = $listaBairros;
        $this->view->listaTiposManifestante = $listaTiposManifestante;
        $this->view->listaPrioridades = $listaPrioridades;
        $this->view->listaProtocolos = $listaProtocolos;
    }
    
    protected function _getIdTipoProcessoUrl()
    {
        $idTipoProcesso = $this->getRequest()->getParam('tipoprocesso');
        return $idTipoProcesso;
    }
    
    protected function _getTipoProcesso($idTipoProcesso = null)
    {
        $tipoProcesso = new TipoProcesso($this->getTicket());
        if ($idTipoProcesso) {
            $tipoProcesso->carregarPeloId($idTipoProcesso);
        }
        
        return $tipoProcesso;
    }
    
    protected function _getListaAssuntos(TipoProcesso $tipoProcesso)
    {
        $assuntos = $tipoProcesso->getAssuntos();
        $listaAssuntos = array();
        foreach ($assuntos as $assunto) {
            $listaAssuntos[$assunto->id] = $assunto->nome;
        }
        
        if (count($listaAssuntos) == 0) {
            throw new Exception(
                'O tipo de processo selecionado não possui nenhum assunto. Por favor, escolha outro.'
            );
        }
        
        return $listaAssuntos;
    }
    
    protected function _getListaTiposManifestante($tipoProcesso)
    {
        $tiposManifestante = $tipoProcesso->getTiposManifestante();
        
        $listaTiposManifestante = array();
        foreach ($tiposManifestante as $tipoManifestante) {
            $listaTiposManifestante[$tipoManifestante->id] = $tipoManifestante->descricao;
        }
        
        if (count($listaTiposManifestante) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de manifestante cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaTiposManifestante;
    }
    
    protected function _getListaBairros()
    {
        $bairro = new Bairro($this->getTicket());
        $bairros = $bairro->listar();
        $listaBairros = array();
        foreach ($bairros as $bairro) {
            $listaBairros[$bairro->id] = $bairro->descricao;
        }
        
        if (count($listaBairros) == 0) {
            throw new Exception(
                'Não existe nenhum bairro cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaBairros;
    }
    
    protected function _getListaPrioridades()
    {
        $prioridade = new Prioridade($this->getTicket());
        $prioridades = $prioridade->listar();
        $listaPrioridades = array();
        foreach ($prioridades as $prioridade) {
            $listaPrioridades[$prioridade->id] = $prioridade->descricao;
        }
        
        if (count($listaPrioridades) == 0) {
            throw new Exception(
                'Não existe nenhuma prioridade de processo cadastrada no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaPrioridades;
    }
    
    protected function _getListaProtocolos()
    {
        $protocolo = new Protocolo($this->getTicket());
        $protocolos = $protocolo->listar();
        $listaProtocolos = array();
        foreach ($protocolos as $protocolo) {
            $listaProtocolos[$protocolo->id] = $protocolo->descricao;
        }
        
        if (count($listaProtocolos) == 0) {
            throw new Exception(
                'Não existe nenhum protocolo cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaProtocolos;
    }
    
    protected function _redirectEscolhaTipoProcesso()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }
}