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
function getCopias() {
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processosCaixaEntrada
	var processos = new Array()
	var path
	var j
	for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		path = caixaEntrada.getQnamePath()
		processosCaixaEntrada = search.luceneSearch("workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:copiaprocesso"')
		for (j = 0; j < processosCaixaEntrada.length; j++) {
			processos.push(processosCaixaEntrada[j])
		}
	}

	return processos
}


/**
 * Cria uma cópia de vários processos
*/
function criarCopias(processosId, protocolosId) {
    var processos = new Array()
    var protocolos = new Array()
    processosId = eval('(' + processosId + ')')
    protocolosId = eval('(' + protocolosId + ')')

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
