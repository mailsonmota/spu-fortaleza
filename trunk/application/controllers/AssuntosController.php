<?php
class AssuntosController extends BaseController
{
    public function indexAction()
    {
        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $listaAssuntos = $assuntoService->getAssuntos();
        $this->view->lista = $listaAssuntos;
    }

    public function editarAction()
    {
        $id = $this->_getIdFromUrl();

        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $assunto = $assuntoService->getAssunto($id);
        if ($this->getRequest()->isPost()) {
            try {
                $assunto = $assuntoService->editar($id, $this->getRequest()->getPost());
                $this->setMessageForTheView('Assunto salvo com sucesso.', 'success');
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $this->view->assunto = $assunto;
        $this->view->id = $assunto->id;
        $this->view->isEdit = true;
    }

    public function inserirAction()
    {
        $id = $this->_getTipoProcessoFromUrl();

        $assunto = new Assunto();
        if ($this->getRequest()->isPost()) {
            try {
                $assuntoService = new Spu_Service_Assunto($this->getTicket());
                $assunto = $assuntoService->inserir($this->getRequest()->getPost());
                $this->setMessageForTheView('Assunto salvo com sucesso.', 'success');
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }
         
        $this->view->assunto = $assunto;
        $this->view->id = $assunto->getId();
        $this->view->isEdit = true;
        $this->view->tipoProcesso = $id;
    }

    private function _getIdFromUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $id;
    }

    public function formularioAction()
    {
        $id = $this->_getIdFromUrl();

        $assuntoService = new Spu_Service_Assunto($this->getTicket());
        $assunto = $assuntoService->getAssunto($id);

        if ($this->getRequest()->isPost()) {
            print '<pre>';var_dump($this->_getAllParams());exit;
        }

        $this->view->assunto = $assunto;
        $this->view->id = $assunto->getId();
        $this->view->isEdit = true;
    }

    private function _getTipoProcessoFromUrl()
    {
        $tipoProcesso = $this->getRequest()->getParam('tipoprocesso');
        return $tipoProcesso;
    }

    public function removerAction()
    {
        try {
            if (!$this->getRequest()->isPost() OR !$this->getRequest()->getParam('assuntos')) {
                throw new Exception("Por favor, selecione pelo menos um assunto para remover.");
            }
            $assuntoService = new Spu_Service_Assunto($this->getTicket());
            $assuntoService->removerVarios($this->getRequest()->getPost());
            $this->setSuccessMessage('Assuntos removidos com sucesso.');
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());            
        }
        $idTipoProcesso = $this->getRequest()->getParam('tipoProcessoId');
        $this->_redirectListagemAssuntos($idTipoProcesso);
        
    }
    
    protected function _redirectListagemAssuntos($idTipoProcesso)
    {
        $this->_helper->redirector('assuntos', 'tiposprocesso', 'default', array('id' => $idTipoProcesso));
    }
    
}