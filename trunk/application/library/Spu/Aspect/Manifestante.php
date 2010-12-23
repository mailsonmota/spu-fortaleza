<?php
require_once('Base.php');
Loader::loadDao('ManifestanteDao');
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
    
    public function listar()
    {
        $dao = $this->_getDao();
        $hashManifestantes = $dao->getManifestantes();
        
        $manifestantes = array();
        foreach ($hashManifestantes[0] as $hashManifestante) {
        	
        	if ($hashManifestante) {
                $hashDadosManifestante = array_pop($hashManifestante);
                $manifestante = new Manifestante($this->_getTicket());
                $manifestante->loadFromHash($hashDadosManifestante);
                $manifestantes[] = $manifestante;
            }
        }
        
        return $manifestantes;
    }
    
    protected function _getDao()
    {
    	$dao = new ManifestanteDao($this->_getTicket());
    	return $dao;
    }
    
    public function loadFromHash($hash)
    {
        $this->setCpf($this->_getHashValue($hash, 'cpfCnpj'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setSexo($this->_getHashValue($hash, 'sexo'));
        $this->setLogradouro($this->_getHashValue($hash, 'logradouro'));
        $this->setNumero($this->_getHashValue($hash, 'numero'));
        $this->setCep($this->_getHashValue($hash, 'cep'));
        $this->setBairro($this->_loadBairroFromHash($this->_getHashValue($hash, 'bairro')));
        $this->setCidade($this->_getHashValue($hash, 'cidade'));
        $this->setUf($this->_getHashValue($hash, 'uf'));
        $this->setTelefone($this->_getHashValue($hash, 'telefone'));
        $this->setTelefoneComercial($this->_getHashValue($hash, 'telefoneComercial'));
        $this->setCelular($this->_getHashValue($hash, 'celular'));
        $this->setObservacao($this->_getHashValue($hash, 'observacao'));
    }
    
	protected function _loadBairroFromHash($hash)
    {
    	$hash = array_pop($hash);
        $bairro = new Bairro($this->_ticket);
        $bairro->loadFromHash($hash);
        
        return $bairro;
    }
    
    public function carregarPeloCpf($cpf)
    {
        $dao = $this->_getDao();
        $hashManifestante = $dao->getManifestante($this->_desmascararCpf($cpf));
        
        $hashDadosManifestante = array_pop(array_pop(array_pop($hashManifestante)));
        
        $this->loadFromHash($hashDadosManifestante);
    }
    
    protected function _desmascararCpf($cpf)
    {
    	return preg_replace("'[.,-]'", '', $cpf);
    }
}
?>