function tramitarExterno(nodeId, despacho) {
	/* Processo */	
	var processo = getNode(nodeId)

	/* Status */
	var statusTramitando = getStatusTramitando();

	var origemId = processo.properties['spu:processo.Destino']

	/* Properties */
	processo.properties['spu:processo.EmAnalise'] = true
	processo.properties['spu:processo.Status'] = 'workspace://SpacesStore/' + statusTramitando.properties['sys:node-uuid']
	processo.properties['spu:processo.Despacho'] = despacho
	processo.properties['spu:processo.Externo'] = true
	processo.save()

	processo = getNode(nodeId)
	return processo
}

function tramitarExternos(processosId, despacho) {
	var processos = new Array()
	var processo
	processosId = eval('(' + processosId + ')');
	for (var i=0; i < processosId.length; i++) {
		processo = tramitarExterno(processosId[i], despacho)
		processos.push(processo)
	}
	return processos
}
