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
	var protocolo = getNode(destinoId)
	var caixasEntrada = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + protocolo.getQnamePath() + '/*" AND TYPE:"spu:CaixaEntrada"'
	);
	var caixaEntrada = caixasEntrada[0]
	
	/* Status */
	var statusTramitando = getStatusTramitando();

	/* Properties */
	processo.properties['spu:processo.EmAnalise'] = false
	processo.properties['spu:processo.Status'] = 'workspace://SpacesStore/' + statusTramitando.properties['sys:node-uuid']
	processo.properties['spu:processo.Origem'] = origemId
	processo.properties['spu:processo.DataPrazo'] = getDataFormatadaAlfresco(prazo)
	processo.properties['spu:processo.Despacho'] = despacho
	processo.properties['spu:processo.Prioridade'] = 'workspace://SpacesStore/' + prioridadeId
	processo.properties['spu:processo.Destino'] = destinoId
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
