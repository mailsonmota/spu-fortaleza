<?php
require_once('Base.php');
Loader::loadService('ManifestanteService');
Loader::loadClassification('Bairro');
class Manifestante extends Spu_Aspect_Base
{
    protected $_cpf;
    protected $_nome;
    protected $_sexo;
    protected $_logradouro;
    protected $_numero;
    protected $_cep;
    protected $_bairro;
    protected $_cidade;
    protected $_uf;
    protected $_telefone;
    protected $_telefoneComercial;
    protected $_celular;
    protected $_observacao;
    
    public function getCpf()
    {
        return $this->_cpf;
    }
    
    public function setCpf($value)
    {
        $this->_cpf = $value;
    }
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }
    
    public function getSexo()
    {
        return $this->_sexo;
    }
    
    public function setSexo($value)
    {
        $this->_sexo = $value;
    }
    
    public function getLogradouro()
    {
        return $this->_logradouro;
    }
    
    public function setLogradouro($value)
    {
        $this->_logradouro = $value;
    }
    
    public function getNumero()
    {
        return $this->_numero;
    }
    
    public function setNumero($value)
    {
        $this->_numero = $value;
    }
    
    public function getCep()
    {
        return $this->_cep;
    }
    
    public function setCep($value)
    {
        $this->_cep = $value;
    }
    
    public function getBairro()
    {
        return $this->_bairro;
    }
    
    public function setBairro($value)
    {
        $this->_bairro = $value;
    }
    
    public function getCidade()
    {
        return $this->_cidade;
    }
    
    public function setCidade($value)
    {
        $this->_cidade = $value;
    }
    
    public function getUf()
    {
        return $this->_uf;
    }
    
    public function setUf($value)
    {
        $this->_uf = $value;
    }
    
    public function getTelefone()
    {
        return $this->_telefone;
    }
    
    public function setTelefone($value)
    {
        $this->_telefone = $value;
    }
    
    public function getTelefoneComercial()
    {
        return $this->_telefoneComercial;
    }
    
    public function setTelefoneComercial($value)
    {
        $this->_telefoneComercial = $value;
    }
    
    public function getCelular()
    {
        return $this->_celular;
    }
    
    public function setCelular($value)
    {
        $this->_celular = $value;
    }
    
    public function getObservacao()
    {
        return $this->_observacao;
    }
    
    public function setObservacao($value)
    {
        $this->_observacao = $value;
    }
    
    public function getNomeBairro()
    {
        return $this->getBairro()->descricao;
    }
    
    public function getEndereco()
    {
        $logradouro = $this->getLogradouro();
        $cidade = $this->getCidade();
        
        if ($logradouro OR $cidade) {
            $numero = $this->getNumero();
            $uf = $this->getUf();
            $endereco = "$logradouro $numero, $cidade - $uf";
        } else {
            $endereco = '';
        }
        
        return $endereco; 
    }
    
    public function getContato()
    {
        $telefone = $this->getTelefone();
        $telefoneComercial = $this->getTelefoneComercial();
        $celular = $this->getCelular();
        $contato = '';
        if ($telefone) {
            $contato .= "$telefone";
        }
        if ($telefoneComercial) {
            if ($contato != '') {
                $contato .= ', ';
            }
            $contato .= "$telefoneComercial (Comercial)";
        }
        if ($celular) {
            if ($contato != '') {
                $contato .= ', ';
            }
            $contato .= "$celular (Celular)";
        }
        
        return $contato; 
    }
    
    protected function _desmascararCpf($cpf)
    {
        return preg_replace("'[.,-]'", '', $cpf);
    }
}