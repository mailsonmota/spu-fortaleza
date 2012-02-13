<?php

class AposentadoriaController extends BaseController
{
    public function atualizarAction()
    {
        $this->ajaxNoRender();
        
        if ($this->isPostAjax()) {
            $res = $this->_atualizarAposentadoria($this->_getParam('ids'), $this->_getParam('colunas'));
            die($res ? 'atualizado' : 'erro');
        }
    }
}