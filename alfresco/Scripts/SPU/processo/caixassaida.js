function getCaixaSaida(offset, pageSize, filter, assuntoId) {
    var statusTramitando = getStatusTramitando()
	searchQuery = 'TYPE:"spu:Processo" AND @spu\\:processo.Status:"' + statusTramitando.nodeRef + '"' + getCriterioCaixasSaidaEnviados() + ' AND NOT (@spu\\:processo.EmAnalise:true)';
    if (filter && filter != '') {
        searchQuery += ' AND ALL:"*' + filter + '*"';
    }

    if (assuntoId) {
        searchQuery += ' AND @spu\\:processo.Assunto:"' + assuntoId + '"'
    }

	var paging = {maxItems: pageSize, skipCount: offset};
    var sort1 = getDefaultSortProcessos()
    var def = {query: searchQuery, language: "lucene", page: paging, sort:[sort1]};
    var processos = search.query(def);

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
	
	criterio = criterioOrigem + '' + criterioDestino

	return criterio
}

function getCaixaEnviados(offset, pageSize, filter, assuntoId) {
    var statusTramitando = getStatusTramitando()
	searchQuery = 'TYPE:"spu:Processo" AND @spu\\:processo.Status:"' + statusTramitando.nodeRef + '"' + getCriterioCaixasSaidaEnviados() + ' AND @spu\\:processo.EmAnalise:true';
    if (filter && filter != '') {
        searchQuery += ' AND ALL:"*' + filter + '*"';
    }

    if (assuntoId) {
        searchQuery += ' AND @spu\\:processo.Assunto:"' + assuntoId + '"'
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

	return processos;
}
