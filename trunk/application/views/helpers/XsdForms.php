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
		
		return $this;
	}
	
	public function render()
	{
		$this->_prepare();
		return $this->_html;
	}
	
	private function _prepare()
	{
		$html = "<script type=\"text/javascript\">
                    jQuery(document).ready(function() {
                        var xsdUrl = \"" . $this->_getUrl() . "\"
                        generateForm(xsdUrl,'xsdform_container');
                        jQuery('#" . $this->_idContainer . "').submit(function() {
                            generateXml(xsdUrl, this." . $this->_valorDestino . "); 
                            return false;
                        });
                    generateXsdFormUI();
                    });
                </script>";
		
		$this->_html = $html;
	}
	
	private function _getUrl()
	{
		return $this->_getBaseUrl() . '/formulario/content/id/' . $this->_idFormulario;
	}
	
	private function _getBaseUrl()
	{
		return Zend_Controller_Front::getInstance()->getBaseUrl();
	}
}