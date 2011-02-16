function getCaixaEntrada(offset, pageSize, filter) {
	//função original, funciona bem, mas não performatica
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processosCaixaEntrada
	var processos = new Array()
	var path
	var j

    searchQuery = '+TYPE:"spu:processo" AND (';
    for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		path = caixaEntrada.getQnamePath()

        if (i != 0) { 
            searchQuery += ' OR ' 
        }
        searchQuery += 'PATH:"' + path + '/*"'
	}
    searchQuery += ')'
    if (filter && filter != '') {
        searchQuery += ' +ALL:"*' + filter + '*"';
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

	return processos
}

function getCaixaEntradaSemPaginacao() {
	//levemente mais performatico que o original, mas ainda pode comprometer a performance quando houver muitos processos
    var statusTramitando = getStatusTramitando()
	var caixasEntrada = getCaixasEntrada()
	var processos = search.luceneSearch("workspace://SpacesStore", 
                                        'TYPE:"spu:Processo" AND ' + 
                                        'NOT (@spu\\:processo.EmAnalise:true) AND ' + 
                                        '@spu\\:processo.Status:"' + statusTramitando.nodeRef + '"')
	var processosCaixaEntrada = new Array()
	var caixaEntrada
	var processo
	for (var i=0; i < caixasEntrada.length; i++) {
		caixaEntrada = caixasEntrada[i]
		for (var j=0; j < processos.length; j++) {
			processo = processos[j]
			if(caixaEntrada.nodeRef.equals(processo.parent.nodeRef)){
				processosCaixaEntrada.push(processo)
				break; //para o 'for' interno
			}
		}
	}
	return processosCaixaEntrada
}

function getCaixaAnalise(offset, pageSize, filter) {
    var caixasAnalise = getCaixasAnalise()
	var caixaAnalise
	var processosCaixaAnalise
	var processos = new Array()
	var path
	var j

    searchQuery = '+TYPE:"spu:processo" AND @spu\\:processo.Externo:false AND (';
    for (var i=0; i < caixasAnalise.length;i++) {
		caixaAnalise = caixasAnalise[i]
		path = caixaAnalise.getQnamePath()

        if (i != 0) { 
            searchQuery += ' OR ' 
        }
        searchQuery += 'PATH:"' + path + '/*"'
	}
    searchQuery += ')'
    if (filter && filter != '') {
        searchQuery += ' +ALL:"*' + filter + '*"';
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

	return processos
}

function getCaixaAnaliseIncorporacao(processoId, assuntoId, manifestanteCpf) {
    var caixaAnalise, searchQuery, i, paging, def, processos;
    var caixasAnalise = getCaixasAnalise()

    searchQuery = '+TYPE:"spu:processo" AND (';
    for (var i=0; i < caixasAnalise.length;i++) {
		caixaAnalise = caixasAnalise[i]
		path = caixaAnalise.getQnamePath()

        if (i != 0) { 
            searchQuery += ' OR ' 
        }
        searchQuery += 'PATH:"' + path + '/*"'
	}
    searchQuery += ')'
    
    searchQuery += ' AND @spu\\:processo.Assunto:' + assuntoId;
    searchQuery += ' AND @spu\\:manifestante.Cpf:' + manifestanteCpf;
    //searchQuery += ' AND NOT @cm\:node-uuid:' + manifestanteCpf; // como usar node-uuid? alternativa: name?
    //throw searchQuery;
    var processos = search.luceneSearch(searchQuery);

	return processos
}

function getCaixaAnaliseSemPaginacao() {
	//função original, funciona bem, mas não performatica
	var caixasAnalise = getCaixasAnalise()
	var caixaAnalise
	var processosCaixaAnalise
	var processos = new Array()
	var path
	var j
	for (var i=0; i < caixasAnalise.length;i++) {
		caixaAnalise = caixasAnalise[i]
		path = caixaAnalise.getQnamePath()
		processosCaixaAnalise = search.luceneSearch(
			"workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo" AND @spu\\:processo.EmAnalise:true AND NOT (@spu\\:processo.Externo:true)'
		)
		for (j = 0; j < processosCaixaAnalise.length; j++) {
			processos.push(processosCaixaAnalise[j])
		}
	}

	return processos
}

function getCaixaAnalisePaleativo() {
	//levemente mais performatico que o original, mas ainda pode comprometer a performance quando houver muitos processos
	var caixasAnalise = getCaixasAnalise()
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo" AND @spu\\:processo.EmAnalise:true AND NOT (@spu\\:processo.Externo:true)')
	var processosCaixaAnalise = new Array()
	var caixaAnalise
	var processo
	for (var i=0; i < caixasAnalise.length; i++) {
		caixaAnalise = caixasAnalise[i]
		for (var j = 0; j < processos.length; j++) {
			processo = processos[j]
			if(caixaAnalise.nodeRef.equals(processo.parent.nodeRef)){
				processosCaixaAnalise.push(processo)
				break; //para o 'for' interno
			}
		}
	}

	return processosCaixaAnalise
}

function getCaixaExternos() {
	//função original, funciona bem, mas não performatica
	var caixasAnalise = getCaixasAnalise()
	var caixaAnalise
	var processosCaixaAnalise
	var processos = new Array()
	var path
	var j
	for (var i=0; i < caixasAnalise.length;i++) {
		caixaAnalise = caixasAnalise[i]
		path = caixaAnalise.getQnamePath()
		processosCaixaAnalise = search.luceneSearch(
			"workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo" AND @spu\\:processo.EmAnalise:true AND @spu\\:processo.Externo:true'
		)
		for (j = 0; j < processosCaixaAnalise.length; j++) {
			processos.push(processosCaixaAnalise[j])
		}
	}

	return processos
}

function getCaixaExternosPaleativo() {
	//levemente mais performatico que o original, mas ainda pode comprometer a performance quando houver muitos processos
	var caixasAnalise = getCaixasAnalise()
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo" AND @spu\\:processo.EmAnalise:true AND @spu\\:processo.Externo:true')
	var processosCaixaAnalise = new Array()
	var caixaAnalise
	var processo
	for (var i=0; i < caixasAnalise.length; i++) {
		caixaAnalise = caixasAnalise[i]
		for (var j = 0; j < processos.length; j++) {
			processo = processos[j]
			if(caixaAnalise.nodeRef.equals(processo.parent.nodeRef)){
				processosCaixaAnalise.push(processo)
				break; //para o 'for' interno
			}
		}
	}

	return processosCaixaAnalise
}
