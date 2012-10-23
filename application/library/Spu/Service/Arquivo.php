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
     * path para serviços do cmis
     * @var string 
     */
    private $_pathCmis = 'cmis';

    public function createDocument($nodeId, array $postData = array())
    {

        $url = $this->getBaseUrl() . "/{$this->_pathCmis}/i/{$nodeId}/children";
        $postData += array('xml' => $this->_getXmlForCreateDocument());
        echo '<pre>';
        var_dump($this->_doAuthenticatedPostAtomRequest($url, $postData));
        echo '</pre>';
        die();
    }

    private function _getXmlForCreateDocument()
    {
        $xml  = "<?xml version='1.0' encoding='utf-8'?>";
        $xml .= "<entry xmlns='http://www.w3.org/2005/Atom' xmlns:cmisra='http://docs.oasis-open.org/ns/cmis/restatom/200908/' xmlns:cmis='http://docs.oasis-open.org/ns/cmis/core/200908/'>";
        $xml    .= "<title>arquivo.xml</title>";
        $xml    .= "<name>arquivo.xml</name>";
        $xml    .= "<summary>VERY important document</summary>";
        $xml    .= "<content type='text'>nome: igor rocha cpf: 90571517315</content>";
        $xml    .= "<cmisra:object><cmis:properties><cmis:propertyId propertyDefinitionId='cmis:objectTypeId'>";
        $xml        .= "<cmis:value>cmis:document</cmis:value>";
        $xml    .= "</cmis:propertyId></cmis:properties></cmisra:object>";
        $xml .= "</entry>";
        
        return $xml;
    }

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
     * Atualiza o conteúdo de um formulário do tipo Document dentro do Alfresco
     * 
     * @category CMIS
     * @param string $idDocument
     * @param array $data
     * @return boolean
     * @throws Alfresco_Rest_Exception 
     */
    public function updateFormulario($idDocument, $data)
    {
        $url =  "{$this->getBaseUrl()}/{$this->_pathCmis}/i/{$idDocument}/content";
        $result = $this->_doAuthenticatedPutRequest($url, $data);
        
        if ($result != "") {
            throw new Alfresco_Rest_Exception("Opss!!! Ocorreu um erro.");
        }
        
        return true;
    }
    
    /**
     * Busca o id de um formulário do tipo respostas através do id da folder ao qual o
     * formulário está armazenado.
     * 
     * @category CMIS
     * @param string $idFolder
     * @return string
     * @throws Alfresco_Rest_Exception 
     */
    public function getIdRespostasFormulario($idFolder)
    {
        $query  = "SELECT D.cmis:objectId FROM cmis:document D WHERE IN_FOLDER('workspace://SpacesStore/$idFolder') AND CONTAINS(D, 'cmis:name:\'respostasFormulario*\'')";
        
        $url =  "{$this->getBaseUrl()}/{$this->_pathCmis}/query?q=" . urlencode($query);
        $result = $this->_doAuthenticatedGetAtomRequest($url);
        
        $idDocument = $result->getElementsByTagName("value")->item(0)->nodeValue;
        if (!$idDocument) {
            return;
        }
        
        $idDocument = explode("workspace://SpacesStore/", $idDocument);
        
        return $idDocument[1];
    }
    
    public function getVersions($nodeId)
    {
        $url =  "{$this->getBaseUrl()}/{$this->_pathCmis}/i/{$nodeId}/versions";
        $result = $this->_doAuthenticatedGetAtomRequest($url);
        
        return $this->_getEntryCmisXml($result);
    }
    
    public function revertVersion($postData)
    {
        $url =  "{$this->getBaseUrl()}/api/revert";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }

    /**
     * Retorna a URL de download de um arquivo
     *
     * @param array $hash. formato:
     *     $hash['id']   (obrigatório)
     *     $hash['nome'] (opcional)
     * @return string
     */
    public function getArquivoDownloadUrl($arquivoInfos, $addAlfTicket = true, $baseUrl = false)
    {
        $url = ($baseUrl ? $baseUrl : $this->getBaseUrl()) . "/api/node/workspace/SpacesStore/{$arquivoInfos['id']}/content/{$arquivoInfos['nome']}";

        if ($addAlfTicket) {
            $url = $this->addAlfTicketUrl($url);
        }

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
     * OFÍCIO:
     * Dado o nodeRef de um assunto, retorna o uuid de seu arquivo de
     * modelo de ofício.
     */
    public function getOficioUuid($assuntoNodeRef)
    {
        $urlService = $this->getBaseUrl() . "/spu/assunto/"
            . substr($assuntoNodeRef, 24) . "/oficio";

        return trim($this->_doAuthenticatedGetStringRequest($urlService));
    }

    /**
     * Dado um nodeRef de um assunto, retorna seu arquivo de
     * modelo de ofício.
     *
     * @param string $assuntoUuid
     * @return string
     */
    public function getOficioModelo($assuntoUuid)
    {
        $oficioUuid = $this->getOficioUuid($assuntoUuid);

        $urlArquivo = $this->getArquivoDownloadUrl(array('id' => $oficioUuid), false);

        return $this->getContentFromUrl($urlArquivo);
    }

    /**
     * DIÁRIO:
     * Dado o nodeRef de um assunto, retorna o uuid de seu arquivo de
     * modelo do diário oficial.
     */
    public function getDiarioUuid($assuntoNodeRef)
    {
        $urlService = $this->getBaseUrl() . "/spu/assunto/"
            . substr($assuntoNodeRef, 24) . "/diario";

        return trim($this->_doAuthenticatedGetStringRequest($urlService));
    }

    /**
     * Dado um nodeRef de um assunto, retorna seu arquivo de
     * modelo do diário oficial.
     *
     * @param string $assuntoUuid
     * @return string
     */
    public function getDiarioModelo($assuntoUuid)
    {
        $oficioUuid = $this->getDiarioUuid($assuntoUuid);

        $urlArquivo = $this->getArquivoDownloadUrl(array('id' => $oficioUuid, 'nome' => 'diario.odt'), false);

        return $this->getContentFromUrl($urlArquivo);
    }

    /**
     * COMUNICAÇÃO INTERNA:
     * Dado o nodeRef de um assunto, retorna o uuid de seu arquivo de
     * modelo de comunicação interna.
     */
    public function getComunicacaoInternaUuid($assuntoNodeRef)
    {
        $urlService = $this->getBaseUrl() . "/spu/assunto/"
            . substr($assuntoNodeRef, 24) . "/comunicacao-interna";

        return trim($this->_doAuthenticatedGetStringRequest($urlService));
    }

    /**
     * Dado um nodeRef de um assunto, retorna seu arquivo de
     * modelo de comunicação interna.
     *
     * @param string $assuntoUuid
     * @return string
     */
    public function getComunicacaoInternaModelo($assuntoUuid)
    {
        $oficioUuid = $this->getComunicacaoInternaUuid($assuntoUuid);

        $urlArquivo = $this->getArquivoDownloadUrl(array('id' => $oficioUuid), false);

        return $this->getContentFromUrl($urlArquivo);
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
     * Recebe um arquivo odt em formato de string e substitui nele variáveis.
     * O corpo do texto do arquivo odt deve conter marcadores no formato.
     *
     * {nomedomarcador}
     *
     * Esses marcadores serão substituídos de acordo com o hash informado ao
     * método.
     *
     * Retorna uma string, que é o arquivo odt modificado.
     *
     * @param string $odtString Arquivo odt
     * @param array $replaceHash
     * @return string Arquivo odt
     */
    public function substituiVariaveisEmOdt($odtString, array $replaceHash)
    {
        $fileName = tempnam(sys_get_temp_dir(), 'odtFile');

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        file_put_contents($fileName, $odtString);

        $zip = new ZipArchive;

        if (!$zip->open($fileName)) {
            throw new Exception('Erro ao abrir arquivo .odt');
        }

        $index = $zip->locateName('content.xml');
        $contentXml = $zip->getFromIndex($index);

        foreach ($replaceHash as $key => $value) {
            $contentXml = str_replace('{' . $key . '}', $value, $contentXml);
        }

        if (!$zip->deleteIndex($index)) {
            throw new Exception('Erro ao deletar arquivo de dentro do arquivo .odt');
        }

        if (!$zip->addFromString('content.xml', $contentXml)) {
            throw new Exception('Erro ao adicionar arquivo dentro do arquivo .odt');
        }

        if (!$zip->close()) {
            throw new Exception('Erro ao fechar arquivo .odt');
        }

        $odtContent = readfile($fileName);

        unlink($fileName);

        return $odtContent;
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
