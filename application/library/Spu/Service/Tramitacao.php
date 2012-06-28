<?php

/**
 * Classe para acessar os serviços de tramitação de processo do SPU
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Processo
 */
class Spu_Service_Tramitacao extends Spu_Service_Processo
{

    /**
     * Retorna os processos nas caixas de entrada do usuário
     *
     * @param integer $offset
     * @param integer $pageSize
     * @param string filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaEntrada($offset, $pageSize, $filter, $assuntoId = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl
            . "/entrada/$offset/$pageSize/" . str_replace(array('/', ' '), array('_', ''), str_replace(" ", "+", $filter));

        if ($assuntoId) {
            $url .= "?assunto-id=$assuntoId";
        }

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }
    
    public function getCaixaEntradaCmis($idCaidxaEntrada, $skipCount, $maxItems)
    {
        $url = $this->getBaseUrl() . "/cmis/query?q=";
        $query = "SELECT F.cmis:name FROM cmis:folder F WHERE IN_FOLDER ('workspace://SpacesStore/$idCaidxaEntrada') ORDER BY F.cmis:creationDate DESC";
        $url .= urlencode($query);
        $url .= "&skipCount=$skipCount&maxItems=$maxItems";
        
        $result = $this->_doAuthenticatedGetAtomRequest($url);
        
        $total = $result->getElementsByTagName("numItems")->item(0)->nodeValue;
        
        echo '<pre>';
        var_dump(array("itens" => $this->_getEntryCmisXml($result),"totalItens" => $total));
        echo '</pre>';
        die("---- DIE ----");
    }

    /**
     * Retorna os processos que foram enviados pelo usuário, e ainda não foram recebidos pelo destinatário
     *
     * @param integer $offset
     * @param integer $pageSize
     * @param string filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaSaida($offset, $pageSize, $filter, $assuntoId = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl
            . "/saida/$offset/$pageSize/" . str_replace(array('/', ' '), array('_', ''), str_replace(" ", "+", $filter));

        if ($assuntoId) {
            $url .= "?assunto-id=$assuntoId";
        }

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Retorna os processos que estão nas caixas de análise do usuário
     *
     * @param integer $offset
     * @param integer $pageSize
     * @param string filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaAnalise($offset, $pageSize, $filter, $assuntoId = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl
            . "/analise/$offset/$pageSize/" . str_replace(array('/', ' '), array('_', ''), str_replace(" ", "+", str_replace(" ", "+", $filter)));

        if ($assuntoId) {
            $url .= "?assunto-id=$assuntoId";
        }

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Retorna os processos que foram enviados pelo usuário e recebidos pelo destinatário
     *
     * @param integer $offset
     * @param integer $pageSize
     * @param string filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaEnviados($offset, $pageSize, $filter, $assuntoId = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl
            . "/enviados/$offset/$pageSize/" . str_replace(array('/', ' '), array('_', ''), str_replace(" ", "+", $filter));

        if ($assuntoId) {
            $url .= "?assunto-id=$assuntoId";
        }

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Retorna os processos que estão na caixa de análise do usuário, porém marcados como "Externos"
     *
     * @param integer $offset
     * @param integer $pageSize
     * @param string filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaExternos($offset = 0, $pageSize = 20, $filter = '', $assuntoId = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl
            . "/externos/$offset/$pageSize/" . str_replace(array('/', ' '), array('_', ''), str_replace(" ", "+", $filter));


        if ($assuntoId) {
            $url .= "?assunto-id=$assuntoId";
        }

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Retorna os processos que estão nas caixas de arquivo do usuário
     *
     * @param integer $offset
     * @param integer $pageSize
     * @param string filter
     * @return Spu_Entity_Processo[]
     */
    public function getCaixaArquivo($offset, $pageSize, $filter, $assuntoId = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl
            . "/arquivo/$offset/$pageSize/" . str_replace(array('/', ' '), array('_', ''), str_replace(" ", "+", $filter));

        if ($assuntoId) {
            $url .= "?assunto-id=$assuntoId";
        }

        return $this->_loadManyFromHash($this->_getProcessosFromUrl($url));
    }

    /**
     * Tramita um processo para um outro protocolo
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function tramitar($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Tramita vários processos para um outro protocolo
     *
     * @param array $postData
     * @return array parametros podem ser conferidos no webscript
     */
    public function tramitarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarProcessos";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Recebe vários processos e os coloca na caixa de análise
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function receberVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/receber";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Marca vários processos como "Externos"
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function tramitarExternos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/tramitarExternos";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Retorna vários processos da situação de "Externos"
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function retornarExternos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/retornarExternos";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Cancela o envio de processos nas caixas de saída do usuário e os retorna pra caixa de entrada
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function cancelarEnvios($postData)
    {
        $this->_cancelarRespostasFormularioAtualizados($postData["processos"]);

        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/cancelarEnvios";

        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    private function _cancelarRespostasFormularioAtualizados($dataIdFolders)
    {
        $dataIdDocument = $this->_getIdsRespostasFormulario($dataIdFolders);
        
        if ($dataIdDocument)
            $this->_reverterVersaoRespostasFormularioAtualizados($dataIdDocument);

        return false;
    }

    private function _reverterVersaoRespostasFormularioAtualizados($dataIdDocument)
    {
        $arquivoService = new Spu_Service_Arquivo($this->getTicket());

        foreach ($dataIdDocument as $idDocument) {
            $versions = $arquivoService->getVersions($idDocument);
            
            $quantidadeVersoes = count($versions);
            
            if ($quantidadeVersoes == 0)
                continue;
            
            $numeroVersao = $quantidadeVersoes == 1 ? 0 : 1;
            
            if ($versions[$numeroVersao]["cmis:isImmutable"] == "false")
                continue;
            
            $dados["nodeRef"] = $versions[$numeroVersao]["cmis:versionSeriesId"];
            $dados["version"] = $versions[$numeroVersao]["cmis:versionLabel"];
            $dados["majorVersion"] = "";
            $dados["description"] = "";
            
            $arquivoService->revertVersion($dados);
        }
    }

    private function _getIdsRespostasFormulario($dataIdProcesso)
    {
        $arquivoService = new Spu_Service_Arquivo($this->getTicket());
        $idDocuments = array();

        foreach ($dataIdProcesso as $idProcesso) {
            $idDocument = $arquivoService->getIdRespostasFormulario($idProcesso);

            if ($idDocument)
                $idDocuments[] = $idDocument;
        }

        return $idDocuments;
    }

    /**
     * Arquiva vários processos
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function arquivarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Reabre vários processos e os coloca de volta na caixa de análise
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function reabrirVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/reabrir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

    /**
     * Adiciona um novo despacho em vários processos
     *
     * @param array $postData parametros podem ser conferidos no webscript
     * @return array
     */
    public function comentarVarios($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/comentar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);

        return $result;
    }

}

