<?php
Loader::loadService('AssuntoService');
class AssuntosController extends BaseController
{

	public function indexAction()
	{
		$assuntoService = new AssuntoService($this->getTicket());
		$listaAssuntos = $assuntoService->getAssuntos();
		$this->view->lista = $listaAssuntos;
	}

	public function editarAction()
    {
        $id = $this->_getIdFromUrl();
        
        $assuntoService = new AssuntoService($this->getTicket());
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
        		$assuntoService = new AssuntoService($this->getTicket());
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

		$assuntoService = new AssuntoService($this->getTicket());
		$assunto = $assuntoService->getAssunto($id);

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