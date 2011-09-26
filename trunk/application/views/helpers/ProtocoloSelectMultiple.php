<?php
require_once 'ProtocoloSelect.php';
class Zend_View_Helper_ProtocoloSelectMultiple extends Zend_View_Helper_ProtocoloSelect
{
    public function protocoloSelectMultiple($label, 
                                            $name, 
                                            $protocolos, 
                                            $origemId = null, 
                                            $tipoProcessoId = null, 
                                            $options = array())
    {
        return parent::protocoloSelect($label, $name, $protocolos, $origemId, $tipoProcessoId, $options);
    }
    
    protected function _prepareHtml()
    {
        $labelClass = $this->_getLabelClass();
        $id = $this->_getId();
        $rootSelectName = $this->_getRootSelectName();
        
        $html  = "<dt><label for=\"$id\" class=\"$labelClass\">{$this->_label}:</label></dt>";
        $html .= "<dd>
                      <select name=\"{$this->_name}\" id=\"$id\" class=\"$labelClass\" multiple=\"multiple\"></select>
                      <ul id=\"{$this->_getListId()}\"></ul>
                      <select name=\"{$rootSelectName}\" id=\"{$rootSelectName}\">{$this->_getRootOptions()}</select>
                  </dd>";
        
        $this->_html = $html;
    }
    
    protected function _getListId()
    {
        return $this->_name . '_list';
    }
    
    protected function _prepareJs()
    {
        $childrenSelectName = $this->_getChildrenSelectName();
        
        $js = "
            $(document).ready(function() {
                $('#{$this->_getId()}').hide();
                $('#{$this->_getRootSelectName()}').change(function() {
                    var select = this
                    $.ajax({
                        dataType: 'json',
                        url: '{$this->_getBaseServiceListarDestinosFilhosUrl()}/parentId/' + $(select).val(),
                        success: function(data) {
                            //Remove o select filho
                            $('#{$childrenSelectName}').remove();
                            
                            //Remove o link de Adicionar do select filho
                            $('.{$this->_getAddAnotherLinkClass()}').remove();
                            
                            //Insere um novo select filho
                            $(select).after(' {$this->_getSelectFilhos()}');
                            
                            //Adiciona a opção vazia (escolher o proprio pai)
                            $('#{$childrenSelectName}').append('<option value=\"' + $(select).val() + '\"></option>');
                            
                            //Adiciona a opcao selecionar todos
                            $('#{$childrenSelectName}').append('<option value=\"*\">Todos</option>');
                            
                            //Adiciona os protocolos filhos ao select filho
                            {$this->_getOptionsChildrenSelect()}
                            
                            //Insere o link Adicionar
                            $('#{$childrenSelectName}').after(' {$this->_getAddAnotherLink()}');
                            
                            //Define o comportamento do link Adicionar
                            $('.{$this->_getAddAnotherLinkClass()}').click(function() {
                                        
                                //Valor selecionado do select filho
                                var val = $('#{$childrenSelectName}').val();
                                
                                //Valor selecionado do select pai
                                var rootSelectValue = $('#{$this->_getRootSelectName()}').val()
                                
                                //Texto do valor selecionado do select pai
                                var rootSelectText = $('#{$this->_getRootSelectName()} option:selected').text()
                                
                                //Texto do valor selecionado do select filho
                                var text = $('#{$childrenSelectName} option:selected').text();
                                
                                //Texto a ser exibido na lista (caso seja filho, exibir o nome do pai antes)
                                text = (val != rootSelectValue) ? rootSelectText + '/' + text : rootSelectText
                                
                                //Verifica se já nao foi adicionado
                                if (val == '*' && $('#{$this->_getListId()} li:contains(\'' + text + '\')').size()) {
                                    return false;
                                } else if ($('#{$this->_getId()} option[value=\'' + val + '\']').size()) {
                                    return false;
                                }
                                
                                //Conteúdo da LI
                                var html = '<li>' + text + ' {$this->_getRemoveLink()} ';
                                if (val == '*') {
                                    $('#{$childrenSelectName} option[value!=\'*\']').each(function() {
                                        html += '<input type=\"hidden\" value=\"' + $(this).val() + '\" />';
                                    });
                                } else {
                                    html += '<input type=\"hidden\" value=\"' + val + '\" />';
                                }
                                html += '</li>';
                                
                                //Insere na lista a opcao escolhida
                                $('#{$this->_getListId()}').append(html);
                                 
                                 //Adiciona a option no select multiplo hidden
                                 if (val == '*') {
                                     $('#{$childrenSelectName} option[value!=\'*\']').each(function() {
                                         if (!$('#{$this->_getId()} option[value=\'' + $(this).val() + '\']').size()) {
                                             $('#{$this->_getId()}').append(
                                                 '<option selected=\"selected\" value=\"' + $(this).val() + '\">' + 
                                                 $(this).val() + '</option>'
                                             );
                                         }
                                     });
                                 } else {
                                     if (!$('#{$this->_getId()} option[value=\'' + val + '\']').size()) {
                                         $('#{$this->_getId()}').append(
                                            '<option selected=\"selected\" value=\"' + val + '\">' + val + '</option>'
                                         );
                                     }
                                 }
                                 
                                 return false;
                            });
                            
                            //Define o comportamento do link Remover
                            $('.{$this->_getRemoveLinkClass()}').live('click', function() {
                                    
                                //Value do item a remover
                                $(this).parent().find('input').each(function () {
                                    val = $(this).val();
                                
                                    //Remove do select multiplo
                                    $('#{$this->_getId()} option[value=\'' + val + '\']').remove();
                                })
                                
                                //Remove da lista
                                $(this).parent().remove();
                                
                                return false;
                            });
                        }, 
                        error: function(data) {
                            alert('erro');
                        }
                   });
               });
            });";
        
        $this->view->headScript()->appendScript($js, 'text/javascript');
    }

    protected function _getHiddenInput()
    {
        return "<input type=\"hidden\" name=\"{$this->_name}[]\" id=\"$id\" class=\"$labelClass\" />";
    }

    protected function _getRemoveLink()
    {
        return "<a href=\"#\" class=\"{$this->_getRemoveLinkClass()}\">Remover</a>";
    }

    protected function _getRemoveLinkClass()
    {
        return "{$this->_name}_remove-selection";
    }
    
    protected function _getAddAnotherLink()
    {
        return "<a href=\"#\" class=\"{$this->_getAddAnotherLinkClass()}\">Adicionar</a>";
    }

    protected function _getAddAnotherLinkClass()
    {
        return "{$this->_name}_add-another";
    }
}
