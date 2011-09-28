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
    
    public function protocoloSelect($label, 
                                    $name, 
                                    $protocolos, 
                                    $origemId = null, 
                                    $tipoProcessoId = null, 
                                    $options = array())
    {
        $this->_label = $label;
        $this->_name = $name;
        $this->_protocolosRaiz = $protocolos;
        $this->_origemId = $origemId;
        $this->_tipoProcessoId = $tipoProcessoId;
        $this->_options = $options;
        
        $this->_prepareHtml();
        $this->_prepareJs();
        
        return $this->_html;
    }
    
    protected function _prepareHtml()
    {
        $labelClass = $this->_getLabelClass();
        $id = $this->_getId();
        $rootSelectName = $this->_getRootSelectName();
        
        $html  = "<dt><label for=\"$id\" class=\"$labelClass\">{$this->_label}:</label></dt>";
        $html .= "<dd>
                      <input type=\"hidden\" name=\"{$this->_name}\" id=\"$id\" class=\"$labelClass\" />
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
            $html .= "<option value='{$protocolo->getId()}'>{$protocolo->nome}</option>";
        }
        
        return $html;
    }
    
    protected function _prepareJs()
    {
        $childrenSelectName = $this->_getChildrenSelectName();
        
        $js = "
            $(document).ready(function() {
                $('#{$this->_getRootSelectName()}').change(function() {
                    var select = this
                    $.ajax({
                        dataType: 'json',
                        url: '{$this->_getBaseServiceListarDestinosFilhosUrl()}/parent-id/' + $(select).val(),
                        success: function(data) {
                            //Remove o select filho
                            $('#{$childrenSelectName}').remove();
                            
                            //Insere o select filho
                            $(select).after(' {$this->_getSelectFilhos()}');
                            
                            //Insere a opcao vazia no select filho (escolher o proprio pai)
                            //$('#{$childrenSelectName}').append('<option value=\"' + $(select).val() + '\"></option>');
                            
                            //Adiciona as options para o select filho
                            {$this->_getOptionsChildrenSelect()}
                            
                            //Comportamento ao alterar o valor selecionado do select filho
                            $('#{$childrenSelectName}').change(function() {
                                $('#{$this->_getId()}').val($(this).val());
                            });
                            
                            //Define, como padrão, o valor selecionado para o valor do select pai
                            $('#{$this->_getId()}').val($('#{$childrenSelectName} option:first').val());
                        }, 
                        error: function(data) {
                            $('#{$this->_getId()}').val('');
                            $('#{$childrenSelectName}').remove();
                        }
                   });
               });
            });";
        
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
    
    protected function _getOptionsChildrenSelect()
    {
        return "
            $(data).each(function(i, value) {
                var name = (value.id != $('#{$this->_getRootSelectName()} option:selected').val()) ? value.name : '';
                $('#{$this->_getChildrenSelectName()}').append(
                    '<option value=\"' + value.id + '\">' + name + '</option>'
                );
            });";
    }
}
