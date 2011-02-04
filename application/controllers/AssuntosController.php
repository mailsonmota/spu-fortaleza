<?php
Loader::loadEntity('Assunto');
class AssuntosController extends BaseController
{

	public function indexAction()
	{
		$assunto = new Assunto($this->getTicket());
		$listaAssuntos = $assunto->listar();
		$this->view->lista = $listaAssuntos;
	}

	public function editarAction()
    {
        $id = $this->_getIdFromUrl();
        
        $assunto = new Assunto($this->getTicket());
        $assunto->carregarPeloId($id);
        if ($this->getRequest()->isPost()) {
            try {
                $assunto->editar($this->getRequest()->getPost());
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
        
        $assunto = new Assunto($this->getTicket());
        if ($this->getRequest()->isPost()) {
        	try {
        		$assunto->inserir($this->getRequest()->getPost());
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

		$assunto = new Assunto($this->getTicket());
		$assunto->carregarPeloId($id);

		$this->view->assunto = $assunto;
		$this->view->id = $assunto->getId();
		$this->view->isEdit = true;
		$this->view->result = $assunto->getFormularioXsd();
	}
    
    private function _getTipoProcessoFromUrl()
    {
        $tipoProcesso = $this->getRequest()->getParam('tipoprocesso');
        return $tipoProcesso;
    }
}