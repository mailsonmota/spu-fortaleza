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