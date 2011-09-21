<?php
/**
 * Classe para acessar os serviços de Manifestante do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Manifestante extends Spu_Service_Abstract
{
	/**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_manifestantesBaseUrl = 'spu/manifestantes';
    
    /**
     * Retorna os Manifestantes de todos os processos que o usuário tem acesso
     * 
     * @param integer $offset
     * @param integer $pageSize
     * @param string $filter
     * @return Spu_Entity_Aspect_Manifestante[]
     */
    public function getManifestantes($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_manifestantesBaseUrl . "/listar/$offset/$pageSize/?s=$filter";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->loadManyFromHash($result['Manifestantes']);
    }
    
    /**
     * Retorna os dados de um Manifestante à partir do CPF/CNPJ
     * 
     * @param string $cpf
     * @return Spu_Entity_Aspect_Manifestante
     */
    public function getManifestante($cpf)
    {
        $url = $this->getBaseUrl() . "/" . $this->_manifestantesBaseUrl . "/get/$cpf";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->loadFromHash(array_pop($result['Manifestante']));
    }
    
    /**
     * Carrega o Manifestante à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Aspect_Manifestante
     */
    public function loadFromHash($hash)
    {
        $manifestante = new Spu_Entity_Aspect_Manifestante();
        
        $manifestante->setCpf($this->_getHashValue($hash, 'cpfCnpj'));
        $manifestante->setNome($this->_getHashValue($hash, 'nome'));
        $manifestante->setSexo($this->_getHashValue($hash, 'sexo'));
        $manifestante->setLogradouro($this->_getHashValue($hash, 'logradouro'));
        $manifestante->setNumero($this->_getHashValue($hash, 'numero'));
        $manifestante->setCep($this->_getHashValue($hash, 'cep'));
        $manifestante->setBairro($this->_loadBairroFromHash($this->_getHashValue($hash, 'bairro')));
        $manifestante->setCidade($this->_getHashValue($hash, 'cidade'));
        $manifestante->setUf($this->_getHashValue($hash, 'uf'));
        $manifestante->setTelefone($this->_getHashValue($hash, 'telefone'));
        $manifestante->setTelefoneComercial($this->_getHashValue($hash, 'telefoneComercial'));
        $manifestante->setCelular($this->_getHashValue($hash, 'celular'));
        $manifestante->setObservacao($this->_getHashValue($hash, 'observacao'));
        
        return $manifestante;
    }
    
    /**
     * Carrega o Bairro do manifestante à partir de um hash
     * 
     * @param unknown_type $hash
     * @return Spu_Entity_Classification_Bairro
     */
    protected function _loadBairroFromHash($hash)
    {
        $hash = array_pop($hash);
        $bairroService = new Spu_Service_Bairro($this->getTicket());
        $bairro = $bairroService->loadFromHash($hash);
        
        return $bairro;
    }
    
    /**
     * Carrega vários Manifestantes à partir de um hash
     * 
     * @param unknown_type $hash
     * @return Spu_Entity_Aspect_Manifestante[]
     */
    public function loadManyFromHash($hash)
    {
    	$manifestantes = array();
        foreach ($hash as $hashManifestante) {
            if ($hashManifestante) {
                $hashDadosManifestante = array_pop($hashManifestante);
                $manifestantes[] = $this->loadFromHash($hashDadosManifestante);
            }
        }
        
        return $manifestantes;
    }
}