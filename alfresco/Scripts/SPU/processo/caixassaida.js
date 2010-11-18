function getCaixaSaida() {
	criterio = getCriterioCaixasSaidaEnviados() + 'AND NOT (@spu\\:processo.EmAnalise:true)';
	
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo"' + criterio);
	return processos;
}

function getCriterioCaixasSaidaEnviados() {
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processos = new Array
	var protocoloId
	var j
	var criterioOrigem = ''
	var criterioDestino = ''
	var criterio = ''

	for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		protocoloId = caixaEntrada.parent.properties['sys:node-uuid']

		if (criterioOrigem != '') {
			criterioOrigem += ' OR '		
		}
		criterioOrigem += '@spu\\:processo.Origem:"' + protocoloId + '"';

		if (criterioDestino != '') {
			criterioDestino += ' OR '		
		}
		criterioDestino += '@spu\\:processo.Destino:"' + protocoloId + '"';
	}
	if (criterioOrigem) {
		criterioOrigem = ' AND (' + criterioOrigem + ')';
	}
	if (criterioDestino) {
		criterioDestino = ' AND NOT (' + criterioDestino + ')';
	}
	
	criterio = criterioOrigem + '' + criterioDestino + ' AND NOT (@spu\\:processo.Externo:true)'

	return criterio
}

function getCaixaEnviados() {
	criterio = getCriterioCaixasSaidaEnviados() + 'AND @spu\\:processo.EmAnalise:true'
	
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo"' + criterio);
	return processos;
}
