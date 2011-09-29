<?php
/**
 * Classe para acessar os serviços de acesso à arquivo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @author Gil Magno <gilmagno@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Arquivo extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_processoBaseUrl = 'spu/processo';

    /**
     * Retorna os arquivos anexos de um processo
     * 
     * @param string $nodeUuid
     * @return Spu_Entity_Arquivo[]
     */
    public function getArquivos($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivos/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->loadManyFromHash($result);
    }

    /**
     * Anexa um arquivo em um processo
     * 
     * @param array $postData
     *              $postData['destNodeUuid'] - an alfresco node id
     *              $postData['fileToUpload'] - file address on local filesystem. ex.: @/tmp/filename.txt
     * @return array
     */
    public function uploadArquivo($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/uploadarquivo";
        $result = $this->_doAuthenticatedPostFormDataRequest($url, $postData);
        
        return $result;
    }

    /**
     * Salva um formulário de assunto
     * 
     * @param array $postData
     * @return array
     */
    public function salvarFormulario($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/salvar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    /**
     * Retorna a URL de download de um arquivo
     * 
     * @param array $hash
     * @return string
     */
    public function getArquivoDownloadUrl($arquivoInfos)
    {
        $url = "{$this->getBaseUrl()}/api/node/workspace/SpacesStore/{$arquivoInfos['id']}/content/{$arquivoInfos['nome']}";
        $url = $this->addAlfTicketUrl($url);
        
        return $url;
    }
    
    /**
     * Retorna a URL de download de um formulário de assunto
     * 
     * @param string $arquivoHash
     * @return string
     */
    public function getArquivoFormularioDownloadUrl($assuntoId)
    {
        $url = $this->getBaseUrl() . "/spu/formulario/get/assunto/" . $assuntoId;
        $url = $this->addAlfTicketUrl($url);
        
        return $url;
    }

    /**
     * Recupera o conteúdo de um arquivo
     * @param array $getData ['id', 'nome']
     * @return string
     */
    public function getContentFromUrl($url)
    {
        $result = $this->_doAuthenticatedGetStringRequest($url);
        
        if (strpos($result, 'Internal Error') > -1) {
            throw new Exception('Erro ao capturar o formulario');
        }
        
        return $result;
    }
    
    /**
     * Retorna o XML com as respostas de um formulário de assunto
     * 
     * @param string $processoId
     * @return Spu_Entity_RespostasFormulario
     */
    public function getRespostasFormulario($processoId)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/get/$processoId";
        $result = $this->_doAuthenticatedGetStringRequest($url);
        
        $respostasFormulario = new Spu_Entity_RespostasFormulario();
        if ($this->_isValidRespostasXML($result)) {
            $respostasFormulario->loadFromXML($result);
        }
        
        return $respostasFormulario;
    }
    
    /**
     * Verifica se o xml é válido
     * 
     * @param string $xml
     * @return boolean
     */
    protected function _isValidRespostasXML($xml)
    {
        return (is_string($xml) AND strpos($xml, '<title>Apache') === false) ? true : false;
    }
    
    /**
     * Carrega o Arquivo através do hash
     * 
     * @param array $hash
     * @return Spu_Entity_Arquivo
     */
    public function loadFromHash($hash)
    {
        $arquivo = new Spu_Entity_Arquivo();
        $arquivo->setId($this->_getHashValue($hash, 'id'));
        $arquivo->setNome($this->_getHashValue($hash, 'nome'));
        
        return $arquivo;
    }
    
    /**
     * Carrega vários arquivos através de um hash
     * 
     * @param array $hash
     * @return multitype:Spu_Entity_Arquivo
     */
    public function loadManyFromHash($hash)
    {
        $arquivos = Array();
        foreach ($hash as $hashArquivo) {
            $arquivos[] = $this->loadFromHash($hashArquivo);
        }
        
        return $arquivos;
    }
}
