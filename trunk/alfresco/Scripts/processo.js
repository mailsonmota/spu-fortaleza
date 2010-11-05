/**
 * Processo
 * 
 * Métodos para gerenciar os Processos do Sistema de Protocolo Único.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 19/10/2010
*/
function getNode(nodeId) {
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	return node;
}

function getPrioridades() {
	var prioridades = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Prioridade//*\"');
	return prioridades;
}

function getProcesso(nodeId) {
	return getNode(nodeId);
}

function getMovimentacoes(nodeId) {
	var processo = getProcesso(nodeId)
	var versoes = processo.getVersionHistory()
	var isMovimentacao
	var de
	var para
	var despacho
	var prazo
	var prioridade
	var dataCriacao
	var movimentacoes = new Array()
	var movimentacao
	for (var i=0; i < versoes.length; i++) {
		isMovimentacao = true
		
		if (versoes[i+1]) {
			if (versoes[i].node.properties['spu:processo.Destino']) {
				if (versoes[i+1].node.properties['spu:processo.Destino'] == versoes[i].node.properties['spu:processo.Destino']) {
					isMovimentacao = false				
				}
			} else {
				isMovimentacao = false
			}
		} else {
			if (!versoes[i].node.properties['spu:processo.Origem'] || !versoes[i].node.properties['spu:processo.Destino']) {
				isMovimentacao = false			
			}
		}

		if (isMovimentacao) {
			if (versoes[i].node.properties['spu:processo.Origem']) {
				de = getNode(versoes[i].node.properties['spu:processo.Origem'])
			} else {
				de = ""
			}
			if (versoes[i].node.properties['spu:processo.Destino']) {
				para = getNode(versoes[i].node.properties['spu:processo.Destino'])
			} else {
				para = ""
			}
			if (versoes[i].node.properties['spu:processo.Despacho']) {
				despacho = versoes[i].node.properties['spu:processo.Despacho']
			} else {
				despacho = ""
			}
			if (versoes[i].node.properties['spu:processo.DataPrazo']) {
				prazo = versoes[i].node.properties['spu:processo.DataPrazo']
			} else {
				prazo = ""
			}
			if (versoes[i].node.properties['spu:processo.Prioridade']) {
				prioridade = versoes[i].node.properties['spu:processo.Prioridade']
			} else {
				prioridade = ""
			}
			data = versoes[i].createdDate

			movimentacao = new Array()
			movimentacao['de'] = de
			movimentacao['para'] = para
			movimentacao['despacho'] = despacho
			movimentacao['prazo'] = prazo
			movimentacao['prioridade'] = prioridade
			movimentacao['data'] = data

			movimentacoes.push(movimentacao)
		}
	}

	return movimentacoes
}

function tramitar(nodeId, protocoloId, prioridadeId, prazo, despacho) {
	var processo = getNode(nodeId)
	var protocolo = getNode(protocoloId)
	var caixasEntrada = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocolo.getQnamePath() + '/*" AND TYPE:"spu:CaixaEntrada"'
	);
	var caixaEntrada = caixasEntrada[0]
	var origem = processo.parent.parent.properties['sys:node-uuid']

	/* Properties */
	processo.properties['spu:processo.Origem'] = origem
	processo.properties['spu:processo.DataPrazo'] = getDataFormatadaAlfresco(prazo)
	processo.properties['spu:processo.Despacho'] = despacho
	processo.properties['spu:processo.Prioridade'] = 'workspace://SpacesStore/' + prioridadeId
	processo.properties['spu:processo.Destino'] = protocoloId
	processo.save()
	
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
	
	processo = getNode(nodeId)
	processo.move(caixaEntrada)
	return processo
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

function getDataFormatadaAlfresco(dataEmPortugues) {
	var dataDia = dataEmPortugues.substring(0,2)
	var dataMes = dataEmPortugues.substring(3,5)
	var dataAno = dataEmPortugues.substring(6,10)

	return new Date(dataAno, dataMes, dataDia)
}

function getProcessos() {
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo"');
	return processos;
}

function getCaixasEntrada() {
	var caixasEntrada = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:CaixaEntrada"');
	return caixasEntrada;
}

function getCaixaEntrada() {
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processosCaixaEntrada
	var processos = new Array()
	var path
	var j
	for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		path = caixaEntrada.getQnamePath()
		processosCaixaEntrada = search.luceneSearch("workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo"')
		for (j = 0; j < processosCaixaEntrada.length; j++) {
			processos.push(processosCaixaEntrada[j])
		}
	}

	return processos
}

function getCaixaSaida() {
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processos = new Array
	var protocoloId
	var j
	var stringOrigens = ''
	for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		protocoloId = caixaEntrada.parent.properties['sys:node-uuid']
		if (stringOrigens != '') {
			stringOrigens += ' OR '		
		}
		stringOrigens += '@spu\\:processo.Origem:"' + protocoloId + '"';
	}
	if (stringOrigens) {
		stringOrigens = ' AND (' + stringOrigens + ')';
	}
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo"' + stringOrigens);
	return processos;
}
