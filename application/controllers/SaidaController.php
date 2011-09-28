<?php
require_once('BaseTramitacaoController.php');
class SaidaController extends BaseTramitacaoController
{
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            try {
                if ($this->_isPostComprovanteEncaminhamento()) {
                    $session = new Zend_Session_Namespace('comprovanteEncaminhamento');
                    $session->processos = $this->getRequest()->getParam('processos');
                    $this->_redirectComprovanteEncaminhamento();
                } else {
                    $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                    $tramitacaoService->cancelarEnvios($this->getRequest()->getPost());
                    $this->setSuccessMessage('Envios cancelados com sucesso.');
                    $this->_redirectEntrada();
                }
            } catch (Exception $e) {
                $this->setMessageForTheView($e->getMessage(), 'error');
            }
        }
        
        $this->view->q = urldecode($this->_getParam('q'));
         
        $service = new Spu_Service_Tramitacao($this->getTicket());
        $this->view->paginator = $this->_helper->paginator()->paginate(
            $service->getCaixaSaida(
                $this->_helper->paginator()->getOffset(),
                $this->_helper->paginator()->getPageSize(),
                $this->view->q
            )
        );
    }
    
    protected function _isPostComprovanteEncaminhamento()
    {
        return ($this->getRequest()->getParam('comprovanteEncaminhamento', false) !== false) ? true : false;    
    }
    
    protected function _redirectComprovanteEncaminhamento()
    {
        $this->_helper->redirector('comprovante-encaminhamento');
    }
    
    public function comprovanteEncaminhamentoAction()
    {
        $this->_helper->layout()->setLayout('relatorio');
        $session = new Zend_Session_Namespace('comprovanteEncaminhamento');
        $processosSelecionados = $session->processos;
        $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        $this->view->processos = $processos;
    }
}