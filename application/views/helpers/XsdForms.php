<?php
class Zend_View_Helper_XsdForms extends Zend_View_Helper_Abstract
{
	protected $_idFormulario;
	protected $_valorDestino;
	protected $_idContainer;
	protected $_html;
	
	public function xsdForms($idFormulario, $valorDestino, $idContainer)
	{
		$this->_idFormulario = $idFormulario;
		$this->_valorDestino = $valorDestino;
		$this->_idContainer = $idContainer;
		
		$this->_render();
	}
	
	private function _render()
	{
		$script = "jQuery(document).ready(function() {
                        var xsdUrl = \"" . $this->_getUrl() . "\"
                        generateForm(xsdUrl,'xsdform_container');
                        jQuery('#" . $this->_idContainer . "').submit(function() {
                            generateXml(xsdUrl, this." . $this->_valorDestino . "); 
                            return false;
                        });
                        generateXsdFormUI();
                    });";
		
		$this->view->headScript()->appendScript($script, 'text/javascript');
	}
	
	private function _getUrl()
	{
		return $this->_getBaseUrl() . '/formulario/content/id/' . $this->_idFormulario;
	}
	
	private function _getBaseUrl()
	{
		return $this->view->baseUrl();
	}
}