<?php
Loader::loadEntity('Processo');
Loader::loadEntity('Bairro');
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
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEscolhaTipoProcesso();
        }
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposProcesso = $listaTiposProcesso;
        $this->view->listaAssuntos = $listaAssuntos;
        $this->view->listaBairros = $listaBairros;
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
            throw new Exception('O tipo de processo selecionado não possui nenhum assunto. Por favor, escolha outro.');
        }
        
        return $listaAssuntos;
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
                'Não existe nenhum bairro cadastrado no sistema. Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaBairros;
    }
    
    protected function _redirectEscolhaTipoProcesso()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }
}