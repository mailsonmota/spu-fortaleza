<?php
require_once('BaseService.php');
Loader::loadService('BairroService');
class ManifestanteService extends BaseService
{
    private $_manifestantesBaseUrl = 'spu/manifestantes';
    private $_manifestantesTicketUrl = 'ticket';
    
    public function getManifestantes()
    {
        $url = $this->getBaseUrl() . "/" . $this->_manifestantesBaseUrl . "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        return $this->loadManyFromHash($result['Manifestantes']);
    }
    
    public function getManifestante($cpf)
    {
        $url = $this->getBaseUrl() . "/" . $this->_manifestantesBaseUrl . "/get/$cpf";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        return $this->loadFromHash(array_pop(array_pop(array_pop($result['Manifestante']))));
    }
    
    public function loadFromHash($hash)
    {
        $manifestante = new Manifestante();
        
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
    
    protected function _loadBairroFromHash($hash)
    {
        $hash = array_pop($hash);
        $bairroService = new BairroService($this->getTicket());
        $bairro = $bairroService->loadFromHash($hash);
        
        return $bairro;
    }
    
    public function loadManyFromHash($hash)
    {
        $manifestantes = array();
        foreach ($hash[0] as $hashManifestante) {
            
            if ($hashManifestante) {
                $hashDadosManifestante = array_pop($hashManifestante);
                $manifestantes[] = $this->loadFromHash($hashDadosManifestante);
            }
        }
        
        return $manifestantes;
    }
}