/**
 * Tramitação
 * 
 * Métodos para gerenciar as tramitações do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 16/11/2010
*/
function tramitar(nodeId, destinoId, prioridadeId, prazo, despacho) {
    if (destinoId.toString().indexOf('[') == -1) { //Tramitacao Simples
        return tramitarProcesso(nodeId, destinoId, prioridadeId, prazo, despacho)
    } else {
        destino = eval('(' + destinoId + ')')

        var processoOriginal = getNode(nodeId)
        despacho = (!despacho) ? null : despacho

        for (i=0; i < destino.length; i++) {
            if (i == 0) {
                processo = processoOriginal
            } else {
                processo = processoOriginal.copy(userhome, true)
            }
            tramitarProcesso(processo.properties['sys:node-uuid'], destino[i], prioridadeId, prazo, despacho)
        }

        return processoOriginal
    }
}

function tramitarProcesso(nodeId, destinoId, prioridadeId, prazo, despacho) {
    var processo = getNode(nodeId)

    despacho = (!despacho) ? null : despacho    

    /* Origem */
    var origemId;
    if (processo.properties['spu:processo.Destino']) {
        origemId = processo.properties['spu:processo.Destino']
    } else {
        if (processo.properties['spu:processo.Origem']) {
            origemId = processo.properties['spu:processo.Origem']
        } else {
            origemId = null;
        }
    }

    /* Properties */
    if (prazo) {
        processo.properties['spu:processo.DataPrazo'] = getDataFormatadaAlfresco(prazo)
    }
    if (prioridadeId) {
        processo.properties['spu:processo.Prioridade'] = 'workspace://SpacesStore/' + prioridadeId
    }
    processo.properties['spu:processo.Origem'] = origemId
    processo.properties['spu:processo.Destino'] = destinoId
    processo.properties['spu:processo.Despacho'] = despacho

    /* Salvar */
    processo.save()

    /* Executa o Workflow */
    var workflow = actions.create("start-workflow");
    workflow.parameters.workflowName = "jbpm$spu:moveFiles"; 
    workflow.execute(processo);
    
	return processo
}

function getCaixaSaidaOrigem(processoId) {
    var processo = getNode(processoId)
    var protocoloOrigem = getNode(processo.properties['spu:processo.Origem'])

    if (protocoloOrigem == undefined) {
	    return false
    }

    var caixasSaida = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocoloOrigem.getQnamePath() + '/*" AND TYPE:"spu:caixasaida"'
	);
	var caixaSaida = caixasSaida[0]

    return caixaSaida
}

/*function moverParaDestino(processo) {
    adicionarPermissoesDestino(processo)	

    var caixaEntrada = getCaixaEntradaDestino(destinoId)
	processo.move(caixaEntrada)
}

function adicionarPermissoesDestino(processo) {
    // Permissões - Adiciona as permissoes da Cx. Entrada origem ao processo
	var protocoloOrigem = getNode(processo.properties['spu:processo.Origem'])
	var caixasEntradaOrigem = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocoloOrigem.getQnamePath() + '/*" AND TYPE:"spu:caixaentrada"'
	);
	var caixaEntradaOrigem = caixasEntradaOrigem[0]
	var permissoesCaixaEntradaOrigem = caixaEntradaOrigem.getPermissions()
	for (var i=0; i < permissoesCaixaEntradaOrigem.length; i++) {
		permissao = getPermissaoComoHash(permissoesCaixaEntradaOrigem[i])
		role = permissao['role']
		group = permissao['group']
		processo.setPermission(role, group)
	}

    return processo
}*/

function getCaixaEntradaDestino(destinoId) {
    var protocolo = getNode(destinoId)
    var caixasEntrada = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocolo.getQnamePath() + '/*" AND TYPE:"spu:CaixaEntrada"'
	);
	var caixaEntrada = caixasEntrada[0]

    return caixaEntrada
}

function getPermissaoComoHash(permissionString) {
	var hash = new Array
	var allow = permissionString.substring(0, permissionString.indexOf(';'))
	var group = permissionString.substring(permissionString.indexOf(';')+1, permissionString.lastIndexOf(';'))
	var role = permissionString.substring(permissionString.lastIndexOf(';')+1)
	hash['allow'] = allow
	hash['group'] = group
	hash['role'] = role
	return hash
}

function tramitarProcessos(processosId, destinoId, despacho) {
	var processos = new Array()
	var processo, origemId, prioridadeId, prazo
	processosId = eval('(' + processosId + ')');

	for (i=0; i < processosId.length; i++) {
		processo = getNode(processosId[i])
	
		prioridadeId = processo.properties['spu:processo.Prioridade'].properties['sys:node-uuid']
		prazo = processo.properties['spu:processo.DataPrazo']

		processo = tramitar(processosId[i], destinoId, prioridadeId, prazo, despacho)
		processos.push(processo)
	}
	return processos
}

function cancelarEnvios(processosId) {
    var processos = new Array()
	processosId = eval('(' + processosId + ')');
    var versaoPenultimoLocal, origemId, destinoId, despacho, dataPrazo, prioridadeId

    for (i=0; i < processosId.length; i++) {
        processo = getNode(processosId[i])
        destinoId = processo.properties['spu:processo.Origem']
        
        tramitar(processosId[i], destinoId)
		
        /*versaoPenultimoLocal = getVersaoPenultimoLocal(processo)
        
        origemId = versaoPenultimoLocal.node.properties['spu:processo.Origem']
        destinoId = versaoPenultimoLocal.node.properties['spu:processo.Destino']
        despacho = versaoPenultimoLocal.node.properties['spu:processo.Despacho']
        dataPrazo = versaoPenultimoLocal.node.properties['spu:processo.DataPrazo']
        prioridadeId = versaoPenultimoLocal.node.properties['spu:processo.Prioridade'].properties['sys:node-uuid']

        /* Properties */
        /*processo.properties['spu:processo.Externo'] = false
	    processo.properties['spu:processo.EmAnalise'] = true
	    processo.properties['spu:processo.DataPrazo'] = dataPrazo
	    processo.properties['spu:processo.Despacho'] = despacho
	    processo.properties['spu:processo.Prioridade'] = 'workspace://SpacesStore/' + prioridadeId
        processo.properties['spu:processo.Origem'] = origemId
	    processo.properties['spu:processo.Destino'] = destinoId
	    processo.save()*/
	
        /* Permissões */
        //adicionarPermissoesDestino(processo)

        /* Move de volta para a caixa de análise */
        /*var caixaEntrada = getCaixaEntradaDestino(destinoId)
        processo.move(caixaEntrada)*/
    
        processos.push(processo)
    }
    
    return processos
}

function getVersaoPenultimoLocal(processo) {
    var versoes = processo.getVersionHistory()
    var versaoPenultimoLocal
    
    for (var i=0; i < versoes.length; i++) {
        if (versoes[i].node.properties['spu:processo.Destino'] != processo.properties['spu:processo.Destino']) {
            versaoPenultimoLocal = versoes[i]
            break  
        }            
    }
    
    return versaoPenultimoLocal
}
