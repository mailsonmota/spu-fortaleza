<?php
Loader::loadDao('AssuntoDao');
class AssuntosController extends BaseController
{

	public function indexAction()
	{
		$assuntoDao = new AssuntoDao($this->getTicket());
		$listaAssuntos = $assuntoDao->getAssuntos();
		$this->view->lista = $listaAssuntos;
	}

	public function editarAction()
    {
        $id = $this->_getIdFromUrl();
        
        $assuntoDao = new AssuntoDao($this->getTicket());
        $assunto = $assuntoDao->getAssunto($id);
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

		$assuntoDao = new AssuntoDao($this->getTicket());
		$assunto = $assuntoDao->getAssunto($id);

		$arquivoDao = new ArquivoDao($this->getTicket());
		
		$this->view->assunto = $assunto;
		$this->view->id = $assunto->getId();
		$this->view->isEdit = true;
	}
    
    private function _getTipoProcessoFromUrl()
    {
        $tipoProcesso = $this->getRequest()->getParam('tipoprocesso');
        return $tipoProcesso;
    }
}