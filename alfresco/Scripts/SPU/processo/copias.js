/**
 * Cópias
 * 
 * Métodos para gerenciar os despachos do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 13/12/2010
*/

/**
 * Retorna as copias que o usuário tem acesso
*/
function getCopias(offset, pageSize, filter) {
    filter = (filter) ? encodeForAttributeQuery(filter) : null;
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processosCaixaEntrada
	var processos = new Array()
	var path

    searchQuery = '+TYPE:"spu:copiaprocesso" AND (';
    for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		path = caixaEntrada.getQnamePath()

        if (i != 0) { 
            searchQuery += ' OR ' 
        }
        searchQuery += 'PATH:"' + path + '/*"'
	}
    searchQuery += ')'
    if (filter) {
        searchQuery += ' +ALL:"*' + filter + '*"';
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

	return processos
}

/**
 * Cria uma cópia de vários processos
*/
function criarCopias(processosId, protocolosId) {
    var processos = new Array()
    var protocolos = new Array()

    /* Caso usuário passe diretamente um array */
    if (processosId.toString().indexOf('[') == 0) {
        processosId = eval('(' + processosId + ')')
    }
    
    /* Caso usuário passe diretamente um array */
    if (protocolosId.toString().indexOf('[') == 0) {
        protocolosId = eval('(' + protocolosId + ')')
    }
    
	for (i=0; i < processosId.length; i++) {
		processo = getNode(processosId[i])
        
        for (j=0; j < protocolosId.length; j++) {
            protocolo = getNode(protocolosId[j])
            criarCopia(processo, protocolo)
        }
    }
}

/**
 * Cria uma Cópia de um processo
*/
function criarCopia(processo, protocolo) {
    var numero = processo.properties['spu:processo.Numero']
    var numeroAjustado = numero.replace("/", "_");

    var props = new Array()
    props['cm:title'] = numero
    props['cm:name'] = numeroAjustado + '_' + protocolo.properties['cm:name']
    props['spu:linkprocesso.Processo'] = processo.properties['sys:node-uuid']
    props['spu:copiaprocesso.Protocolo'] = protocolo.properties['sys:node-uuid']
    var copia = userhome.createNode(null, "spu:copiaprocesso", props)

    /* Executa o Workflow */
    var workflow = actions.create("start-workflow");
    workflow.parameters.workflowName = "jbpm$spu:criarCopia";
    workflow.execute(copia);
}

/**
 * Exclui várias cópias
*/
function excluirCopias(copiasId) {
    copiasId = eval('(' + copiasId + ')')

	for (i=0; i < copiasId.length; i++) {
		copia = getNode(copiasId[i])
        copia.remove()
    }
}
