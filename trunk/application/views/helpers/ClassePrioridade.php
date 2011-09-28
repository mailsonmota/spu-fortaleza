<?php
class Zend_View_Helper_ClassePrioridade extends Zend_View_Helper_Abstract
{
    public function classePrioridade(Spu_Entity_Processo $processo)
    {
        if ($processo->prioridade->nome == 'Urgente') {
            return 'prioridade-urgente';
        }
        
        return '';
    }
}
