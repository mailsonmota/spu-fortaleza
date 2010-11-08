<?php
require_once('../library/Alfresco/API/AlfrescoManifestantes.php');
require_once('BaseAlfrescoEntity.php');
require_once('Bairro.php');
class Manifestante extends BaseAlfrescoEntity
{
    protected $_cpf;
    protected $_nome;
    protected $_bairro;
    
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
    
    public function getBairro()
    {
        return $this->_bairro;
    }
    
    public function setBairro($value)
    {
        $this->_bairro = $value;
    }
    
    public function getNomeBairro()
    {
    	return $this->getBairro()->descricao;
    }
    
    public function listar()
    {
        $service = new AlfrescoManifestantes(self::ALFRESCO_URL, $this->_getTicket());
        $hashManifestantes = $service->getManifestantes();
        
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
    
    public function loadFromHash($hash)
    {
        $this->setCpf($this->_getHashValue($hash, 'cpfCnpj'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setBairro($this->_loadBairroFromHash($this->_getHashValue($hash, 'bairro')));
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
        $service = new AlfrescoManifestantes(self::ALFRESCO_URL, $this->_getTicket());
        $hashManifestante = $service->getManifestante($cpf);
        
        $hashDadosManifestante = array_pop(array_pop(array_pop($hashManifestante)));
        
        $this->loadFromHash($hashDadosManifestante);
    }
}
?>