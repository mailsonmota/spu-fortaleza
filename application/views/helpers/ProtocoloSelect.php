<?php
class Zend_View_Helper_ProtocoloSelect extends Zend_View_Helper_Abstract
{
    protected $_label;
    protected $_name;
    protected $_protocolosRaiz;
    protected $_origemId;
    protected $_tipoProcessoId;
    protected $_options = array();
    protected $_html = '';
    
    public function protocoloSelect($label, $name, $protocolosRaiz, $origemId, $tipoProcessoId, $options = array())
    {
        $this->_label = $label;
        $this->_name = $name;
        $this->_protocolosRaiz = $protocolosRaiz;
        $this->_origemId = $origemId;
        $this->_tipoProcessoId = $tipoProcessoId;
        $this->_options = $options;
        
        $this->_prepareHtml();
        $this->_prepareJs();
        
        return $this->_html;
    }
    
    protected function _prepareHtml()
    {
        $label = $this->_label;
        $labelClass = $this->_getLabelClass();
        $name = $this->_name;
        $id = $this->_getId();
        $listId = $this->_getListId();
        $rootSelectName = $this->_getRootSelectName();
        
        $html  = "";
        $html .= "<dt><label for=\"$id\" class=\"$labelClass\">$label:</label></dt>";
        $html .= "<dd>
                      <ul id=\"$listId\"></ul>
                      <input type=\"hidden\" name=\"$name\" id=\"$id\" class=\"$labelClass\" />
                      <select name=\"{$rootSelectName}\" id=\"{$rootSelectName}\">{$this->_getRootOptions()}</select>
                  </dd>";
        
        $this->_html = $html;
    }
    
    protected function _getId()
    {
        $id = $this->_name;
        if (isset($this->_options['id'])) {
            $id = $this->_options['id'];
        }
        
        return $id;
    }
    
    protected function _getListId()
    {
        return $this->_name . '_list';
    }
    
    protected function _getLabelClass()
    {
        $class = '';
        if (isset($this->_options['required'])) {
            $class .= 'required';
        }
        
        return $class;
    }
    
    protected function _getRootSelectName()
    {
        return $this->_name . '_root';
    }
    
    protected function _getRootOptions()
    {
        $html = '<option value="0"></option>';
        foreach ($this->_protocolosRaiz as $protocolo) {
            $html .= "<option value='{$protocolo->getId()}'>{$protocolo->path}</option>";
        }
        
        return $html;
    }
    
    protected function _prepareJs()
    {
        $rootSelectName = $this->_getRootSelectName();
        $baseServiceUrl = $this->_getBaseServiceListarDestinosFilhosUrl();
        $childrenSelectName = $this->_getChildrenSelectName();
        
        $js = "
            $(document).ready(function() {
                $('#{$rootSelectName}').change(function() {
                    var select = this
                    $.ajax({
                        dataType: 'json',
                        url: '{$baseServiceUrl}/parentId/' + $(select).val(),
                        success: function(data) {
                            $('#{$childrenSelectName}').remove();
                            $(select).after(' {$this->_getSelectFilhos()}');
                            $('#{$childrenSelectName}').append('<option value=\"0\"></option>');
                            $('#{$childrenSelectName}').append('<option value=\"-1\">Todos os Subsetores</option>');
                            $(data).each(function(i, value) {
                                $('#{$childrenSelectName}').append(
                                    '<option value=\"' + value.id + '\">' + value.name + '</option>'
                                );
                            });
                        }, 
                        error: function(data) {
                            alert('erro');
                            //$('#assunto option').remove();
                        }
                   });
               });
            });
        ";
        
        $this->view->headScript()->appendScript($js, 'text/javascript');
    }

    protected function _getBaseServiceListarDestinosFilhosUrl()
    {
        return $this->view->url(
            array(
                'controller' => 'protocolos-ajax', 
                'action' => 'listar-destinos-filhos', 
                'origem' => $this->_origemId, 
                'tipoProcesso' => $this->_tipoProcessoId, 
            )
        );
    }
    
    protected function _getSelectFilhos()
    {
        return "<select name=\"{$this->_getChildrenSelectName()}\" id=\"{$this->_getChildrenSelectName()}\"></select>";
    }
    
    protected function _getChildrenSelectName()
    {
        return $this->_name . '_children';
    }
}
