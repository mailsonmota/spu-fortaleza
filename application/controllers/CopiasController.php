<?php

require_once('BaseTramitacaoController.php');

class CopiasController extends BaseTramitacaoController
{

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            if (count($this->getRequest()->getParam('processos')) > self::LIMITE_MOVIMENTACAO) {
                $this->setErrorMessage("Atenção, você não pode movimentar mais do que " . self::LIMITE_MOVIMENTACAO . " processos por vez!");
                $this->_redirectCopias();
            }
            
            try {
                $copiaProcessoService = new Spu_Service_CopiaProcesso($this->getTicket());
                $copiaProcessoService->excluirTodos($this->getRequest()->getPost());
                $this->setMessageForTheView('Cópias excluídas com sucesso.', 'success');
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }

        $service = new Spu_Service_CopiaProcesso($this->getTicket());
        $busca = $service->getCopias(
            $this->_helper->paginator()->getOffset(), 
            $this->_helper->paginator()->getPageSize(), 
            urldecode($this->_getParam('q'))
        );
        
        $this->view->paginator = $this->_helper->paginator()->paginate($busca);
        $this->view->totalDocumentos = count($busca);
    }
    
    protected function _redirectCopias()
    {
        $this->_helper->redirector('index', 'copias');
    }

}