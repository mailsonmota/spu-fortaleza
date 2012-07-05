<?php

class Zend_View_Helper_Processo extends Zend_View_Helper_Abstract
{

    private $_processo;

    public function processo($obj)
    {
        $this->setProcesso($obj->processo);

        return $this;
    }

    public function getProcesso()
    {
        return $this->_processo;
    }

    public function setProcesso($obj)
    {
        $this->_processo = $obj;
    }

    public function dataAbertura()
    {
        $data = explode('/', $this->getProcesso()->numero);
        $data = str_split($data[0], 2);

        return "{$this->getProcesso()->data} - " . $data[count($data) - 5] . ":" . $data[count($data) - 4];
    }

    public function proprietario()
    {
        $proprietario = explode("/", $this->getProcesso()->proprietario->path);
        $proprietario = $proprietario[0] . "/" . $proprietario[count($proprietario) - 1];

        return "{$proprietario} ({$this->getProcesso()->proprietario->descricao})";
    }

    public function destino($real = false)
    {
        if (count($this->getProcesso()->movimentacoes) == 1)
            $destino = $this->getProcesso()->movimentacoes[0]->de->path;
        else
            $destino = $this->getProcesso()->movimentacoes[0]->para->path;
        
        if ($real)
            return $destino;
        
        $destino = explode("/", $this->getProcesso()->movimentacoes[0]->para->path);
        $destino = $destino[0] . "/" . $destino[count($destino) - 1];
        $destino .= " ({$this->getProcesso()->movimentacoes[0]->para->descricao})";

        return $destino;
    }

}
