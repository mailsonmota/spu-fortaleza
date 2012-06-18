<?php
require_once('BaseTramitacaoController.php');
class DespacharController extends BaseTramitacaoController
{
    public function indexAction()
    {
        $sessionDespachar = new Zend_Session_Namespace('despachar');
        
        if ($this->getRequest()->isPost()) {
            if (!empty($_FILES)) {
            	if ($_FILES['fileToUpload']['name']) {
                        /*$fileTmp = $this->_uploadFilePathConverter($_FILES['fileToUpload']['name'],
                          $_FILES['fileToUpload']['tmp_name']);*/
	                
                        $fileTmp = array('filePath' => $this->_uploadFilePathConverter($_FILES['fileToUpload']['name'],
                                                                               $_FILES['fileToUpload']['tmp_name']),
                                         'fileType' => $_FILES['fileToUpload']['type'],
                                         'tipoDocumento' => $this->_getParam('tipo_documento'));

	                if (!$sessionDespachar->filesToUpload) {
	                	$sessionDespachar->filesToUpload = array();
	                }
	                
	                foreach ($sessionDespachar->filesToUpload as $fileToUpload) {
	                    //if ($fileToUpload == $fileTmp) {
                            if ($fileToUpload['filePath'] == $fileTmp['filePath']) {
	                        $this->setErrorMessage('Este arquivo já se encontra na lista de arquivos a ser submetida.');
	                        $this->_redirectIndex();
	                    }
	                }
	                $sessionDespachar->filesToUpload[] = $fileTmp;
            	} else {
            		$this->setMessageForTheView('Por favor, escolha um arquivo para adicionar.');
            	}
            } else {
                try {
                    $postData = $this->filterValuesArray($this->getRequest()->getPost());
                    
                    $tramitacaoService = new Spu_Service_Tramitacao($this->getTicket());
                    $tramitacaoService->comentarVarios($postData);
                    
                    $arquivoService = new Spu_Service_Arquivo($this->getTicket());

                    foreach ($postData['processos'] as $processoId) {
                        $postData['destNodeUuid'] = $processoId;
                        foreach ($sessionDespachar->filesToUpload as $fileToUpload) {
			    $postData['fileToUpload'] = $fileToUpload['filePath'];
			    $postData = array_merge($postData, $fileToUpload);

                            $arquivoService->uploadArquivo($postData);
                        }
                    }
                    
                    $this->setSuccessMessage('Despachos criados com sucesso.');
                    $this->_redirectEmAnalise();
                } catch (Exception $e) {
                    $this->setMessageForTheView($e->getMessage(), 'error');
                }
            }
        }
        
        $processos = array();
        
        try {
            $session = new Zend_Session_Namespace('comentar');
            $processosSelecionados = $session->processos;
            $processos = $this->_getListaCarregadaProcessos($processosSelecionados);
        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->_redirectEmAnalise();
        }
        
        $this->view->processos = $processos;
        $this->view->filesToUpload = $sessionDespachar->filesToUpload;
        $this->view->serviceTipoDocumento = new Spu_Service_TipoDocumento($this->getTicket());
    }
    
    public function removerarquivoAction()
    {
        $numero = $this->getRequest()->getParam('removerarquivo');
        $sessionDespachar = new Zend_Session_Namespace('despachar');
        unset($sessionDespachar->filesToUpload[$numero]);
        $sessionDespachar->filesToUpload = array_values($sessionDespachar->filesToUpload);
        $this->setSuccessMessage('Arquivo removido da lista de arquivos a ser submetida.');
        $this->_redirectIndex();
    }
    
    protected function _redirectIndex()
    {
        $this->_helper->redirector('index', 'despachar', 'default');
    }
}