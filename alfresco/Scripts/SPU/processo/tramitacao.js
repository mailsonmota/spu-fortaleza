/**
 * Tramitação
 * 
 * Métodos para gerenciar as tramitações do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 16/11/2010
*/
function tramitar(nodeId, origemId, destinoId, prioridadeId, prazo, despacho) {
	var processo = getNode(nodeId)
    
	/* Status */
	var statusTramitando = getStatusTramitando();

	/* Properties */
    processo.properties['spu:processo.Externo'] = false
	processo.properties['spu:processo.EmAnalise'] = false
	processo.properties['spu:processo.Status'] = 'workspace://SpacesStore/' + statusTramitando.properties['sys:node-uuid']
	processo.properties['spu:processo.DataPrazo'] = getDataFormatadaAlfresco(prazo)
	processo.properties['spu:processo.Despacho'] = despacho
	processo.properties['spu:processo.Prioridade'] = 'workspace://SpacesStore/' + prioridadeId
    
    /*processo.properties['spu:processo.Origem'] = origemId
	processo.properties['spu:processo.Destino'] = destinoId*/
	
    /* Salvar */
	processo.save()

    for each (targetNode in processo.assocs["spu:processo.Origem"])
    {
       processo.removeAssociation(targetNode, "spu:processo.Origem"); 
    }
    for each (targetNode in processo.assocs["spu:processo.Destino"])
    {
       processo.removeAssociation(targetNode, "spu:processo.Destino"); 
    }

    var origem = getNode(origemId)
    if (!origem) {
        status.code = 404;
	    status.message = "Origem nao encontrada.";
	    status.redirect = true;
        return false
    }

    var destino = getNode(destinoId)
    if (!destino) {
        status.code = 404;
	    status.message = "Destino nao encontrada.";
	    status.redirect = true;
        return false
    }

    processo.createAssociation(getNode(origemId), 'spu:processo.Origem')
    processo.createAssociation(getNode(destinoId), 'spu:processo.Destino')

    /* Move para a caixa de saida da origem */	
	var caixaSaida = getCaixaSaidaOrigem(nodeId)

    if (!caixaSaida) {
        status.code = 404;
	    status.message = "Caixa de Saida nao encontrada.";
	    status.redirect = true;
    }

	processo.move(caixaSaida)
    
	return processo
}

function getCaixaSaidaOrigem(processoId) {
    var processo = getNode(processoId)
    var protocoloOrigem = processo.assocs['spu:processo.Origem'][0]

    if (protocoloOrigem == undefined) {
	    return false
    }

    var caixasSaida = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocoloOrigem.getQnamePath() + '/*" AND TYPE:"spu:caixasaida"'
	);
	var caixaSaida = caixasSaida[0]

    return caixaSaida
}

function moverParaDestino(processo) {
    /* Permissões */
    adicionarPermissoesDestino(processo)	

    var caixaEntrada = getCaixaEntradaDestino(destinoId)
	processo.move(caixaEntrada)
}

function adicionarPermissoesDestino(processo) {
    /* Permissões - Adiciona as permissoes da Cx. Entrada origem ao processo */
	var protocoloOrigem = getNode(processo.properties['spu:processo.Origem'])
	var caixasEntradaOrigem = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocoloOrigem.getQnamePath() + '/*" AND TYPE:"spu:CaixaEntrada"'
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
}

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
		
		if (destinoId == processo.properties['spu:processo.Destino']) {
			origemId = processo.properties['spu:processo.Origem']
		} else {
			origemId = processo.properties['spu:processo.Destino']
		}
	
		prioridadeId = processo.properties['spu:processo.Prioridade'].properties['sys:node-uuid']
		prazo = processo.properties['spu:processo.DataPrazo']

		processo = tramitar(processosId[i], origemId, destinoId, prioridadeId, prazo, despacho)
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
        versaoPenultimoLocal = getVersaoPenultimoLocal(processo)
        
        origemId = versaoPenultimoLocal.node.properties['spu:processo.Origem']
        destinoId = versaoPenultimoLocal.node.properties['spu:processo.Destino']
        despacho = versaoPenultimoLocal.node.properties['spu:processo.Despacho']
        dataPrazo = versaoPenultimoLocal.node.properties['spu:processo.DataPrazo']
        prioridadeId = versaoPenultimoLocal.node.properties['spu:processo.Prioridade'].properties['sys:node-uuid']

        /* Properties */
        processo.properties['spu:processo.Externo'] = false
	    processo.properties['spu:processo.EmAnalise'] = true
	    processo.properties['spu:processo.Origem'] = origemId
	    processo.properties['spu:processo.DataPrazo'] = dataPrazo
	    processo.properties['spu:processo.Despacho'] = despacho
	    processo.properties['spu:processo.Prioridade'] = 'workspace://SpacesStore/' + prioridadeId
	    processo.properties['spu:processo.Destino'] = destinoId
	    processo.save()
	
        /* Permissões */
        adicionarPermissoesDestino(processo)

        /* Move de volta para a caixa de análise */
        var caixaEntrada = getCaixaEntradaDestino(destinoId)
        processo.move(caixaEntrada)
    
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
