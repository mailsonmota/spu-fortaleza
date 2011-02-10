<?php
class ConsultarController extends BaseController
{
	public function indexAction()
	{
	}
	
	public function executarAction()
	{
		if (!$this->getRequest()->isPost()) {
			$this->setErrorMessage('Busca inválida.');
			$this->_redirectToConsulta();
		}
		
		$postData = $this->getRequest()->getPost();
		
		if (isset($postData['globalSearch'])) {
			$globalSearch = $postData['globalSearch'];
			$field = $this->_getFieldFromFilter($globalSearch);
			$postData[$field] = $globalSearch;
		}
		
		$processoDao = new ProcessoDao($this->getTicket());
		$resultado = $processoDao->consultar($postData);
		
		if (count($resultado) == 1) {
			$processoId = $resultado[0]->id;
			$this->_redirectToProcesso($processoId);
		} else {
			echo '<pre>'; var_dump($resultado); echo '</pre>'; exit;
		}
	}
	
	private function _getFieldFromFilter($filter)
	{
		$field = 'any';
		if ($this->_isNumeroProcesso($filter)) {
			$field = 'numero';
		}
		return $field;
	}
	
	private function _isNumeroProcesso($filter)
	{
		// Modelo: AP0712095609/2010
		return (strlen($filter) ==  17);
	}
	
	private function _redirectToConsulta()
	{
		$this->_helper->redirector('index');
	}
	
	private function _redirectToProcesso($processoId)
	{
		$this->_helper->redirector('detalhes', 'processo', 'default', array('id' => $processoId));
	}
}