<?php
require_once('BaseTramitacaoController.php');
class CopiasController extends BaseTramitacaoController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                $copiaProcessoService = new Spu_Service_CopiaProcesso($this->getTicket());
                $copiaProcessoService->excluirTodos($this->getRequest()->getPost());
                $this->setMessageForTheView('Cópias excluídas com sucesso.', 'success');
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }
        
        $service = new Spu_Service_CopiaProcesso($this->getTicket());
        $this->view->paginator = $this->_helper->paginator()->paginate(
	        $service->getCopias(
		        $this->_helper->paginator()->getOffset(),
		        $this->_helper->paginator()->getPageSize(),
		        urldecode($this->_getParam('q'))
	        )
        );
    }
}