<?php
Loader::loadEntity('TipoProcesso');
Loader::loadEntity('TipoTramitacao');
Loader::loadEntity('TipoAbrangencia');
class TiposprocessoController extends BaseController
{
    public function indexAction()
    {
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $this->view->lista = $tipoProcesso->listar();
    }
    
    public function editarAction()
    {
        $id = $this->_getIdFromUrl();
        
        try {
            $tipoProcesso = new TipoProcesso($this->getTicket());
            $tipoProcesso->carregarPeloId($id);
            $listaTiposTramitacao = $this->_getListaTiposTramitacao();
            $listaTiposAbrangencia = $this->_getListaTiposAbrangencia();
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectListaTiposProcesso();
        }
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->listaTiposTramitacao = $listaTiposTramitacao;
        $this->view->listaTiposAbrangencia = $listaTiposAbrangencia;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }
    
    private function _getIdFromUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $id;
    }
    
    protected function _getListaTiposTramitacao()
    {
        $tipoTramitacao = new TipoTramitacao($this->getTicket());
        $tiposTramitacao = $tipoTramitacao->listar();
        $listaTiposTramitacao = array();
        foreach ($tiposTramitacao as $tipoTramitacao) {
            $listaTiposTramitacao[$tipoTramitacao->id] = $tipoTramitacao->descricao;
        }
        
        if (count($listaTiposTramitacao) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de tramitação cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaTiposTramitacao;
    }
    
    protected function _getListaTiposAbrangencia()
    {
        $tipoAbrangencia = new TipoAbrangencia($this->getTicket());
        $tiposAbrangencia = $tipoAbrangencia->listar();
        $listaTiposAbrangencia = array();
        foreach ($tiposAbrangencia as $tipoAbrangencia) {
            $listaTiposAbrangencia[$tipoAbrangencia->id] = $tipoAbrangencia->descricao;
        }
        
        if (count($listaTiposAbrangencia) == 0) {
            throw new Exception(
                'Não existe nenhum tipo de abrangência cadastrado no sistema. 
                Por favor, entre em contato com a administração do sistema.'
            );
        }
        
        return $listaTiposAbrangencia;
    }
    
    protected function _redirectListaTiposProcesso()
    {
        $this->_helper->redirector('index', $this->getController(), 'default');
    }
    
    public function assuntosAction()
    {
        $id = $this->_getIdFromUrl();
        
        $tipoProcesso = new TipoProcesso($this->getTicket());
        $tipoProcesso->carregarPeloId($id);
        
        $this->view->tipoProcesso = $tipoProcesso;
        $this->view->id = $tipoProcesso->getId();
        $this->view->isEdit = true;
    }
    
    
}