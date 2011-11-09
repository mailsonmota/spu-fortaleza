function getCaixaEntrada(offset, pageSize, filter, assuntoId) {
    //função original, funciona bem, mas não performatica
    var caixasEntrada = getCaixasEntrada()
    var caixaEntrada
    var processosCaixaEntrada
    var processos = new Array()
    var path
    var j

    searchQuery = '+TYPE:"spu:processo" AND (';

    for (var i=0; i < caixasEntrada.length; i++) {
	caixaEntrada = caixasEntrada[i]
	path = caixaEntrada.getQnamePath()

        if (i != 0) { 
            searchQuery += ' OR ' 
        }
        searchQuery += 'PATH:"' + path + '/*"'
    }

    searchQuery += ')'
    
    if (filter && filter != '') {
        searchQuery += ' AND ';

        if (filter instanceof Array && filter.length > 0) {
            searchQuery += ' (';
            for (var i = 0; i < filter.length; i++) {
                if (i > 0) searchQuery += ' OR ';
                searchQuery += ' ALL:"*' + filter[i] + '*" ';
            }
            searchQuery += ') ';
        } else {
            searchQuery += ' ALL:"*' + filter + '*" ';
        }
    }

    if (assuntoId) {
        searchQuery += ' AND @spu\\:processo.Assunto:"' + assuntoId + '"'
    }

    var paging = {maxItems: pageSize, skipCount: offset, sort:sort1};
    var sort1 = getDefaultSortProcessos()
    var def = {query: searchQuery, language: "lucene", page: paging, sort:[sort1]};
    var processos = search.query(def);

    return processos
}

function getCaixaAnalise(offset, pageSize, filter, assuntoId) {
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
        //searchQuery += ' +ALL:"*' + filter + '*"';
        searchQuery += ' AND ';

        if (filter instanceof Array && filter.length > 0) {
            searchQuery += ' (';
            for (var i = 0; i < filter.length; i++) {
                if (i > 0) searchQuery += ' OR ';
                searchQuery += ' ALL:"*' + filter[i] + '*" ';
            }
            searchQuery += ') ';
        } else {
            searchQuery += ' ALL:"*' + filter + '*" ';
        }
    }

    if (assuntoId) {
        searchQuery += ' AND @spu\\:processo.Assunto:"' + assuntoId + '"'
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

    return processos
}

function getCaixaAnaliseIncorporacao(processoId, assuntoId, manifestanteCpf, offset, pageSize, filter) {
    var caixaAnalise, searchQuery, i, paging, def, processos;
    var caixasAnalise = getCaixasAnalise();

    searchQuery = '+TYPE:"spu:processo" AND (';

    for (var i=0; i < caixasAnalise.length;i++) {
        caixaAnalise = caixasAnalise[i];
        path = caixaAnalise.getQnamePath();

        if (i != 0) { 
            searchQuery += ' OR ';
        }

        searchQuery += 'PATH:"' + path + '/*"';
    }

    searchQuery += ')';
    
    searchQuery += ' AND @spu\\:processo.Assunto:' + assuntoId;
    searchQuery += ' AND @spu\\:manifestante.Cpf:' + manifestanteCpf;

    if (filter && filter != '') {
        //searchQuery += ' +ALL:"*' + filter + '*"';
        hQuery += ' AND ';

        if (filter instanceof Array && filter.length > 0) {
            searchQuery += ' (';
            for (var i = 0; i < filter.length; i++) {
                if (i > 0) searchQuery += ' OR ';
                searchQuery += ' ALL:"*' + filter[i] + '*" ';
            }
            searchQuery += ') ';
        } else {
            searchQuery += ' ALL:"*' + filter + '*" ';
        }
    }

    //searchQuery += ' AND NOT @cm\:node-uuid:' + manifestanteCpf; // como usar node-uuid? alternativa: name?
    //var processos = search.luceneSearch(searchQuery);

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

    return processos;
}

function getCaixaExternos(offset, pageSize, filter, assuntoId) {
    var caixasAnalise = getCaixasAnalise()
	var caixaAnalise
	var processosCaixaAnalise
	var processos = new Array()
	var path
	var j

    searchQuery = '+TYPE:"spu:processo" AND @spu\\:processo.EmAnalise:true AND @spu\\:processo.Externo:true AND (';
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
        //searchQuery += ' +ALL:"*' + filter + '*"';
        searchQuery += ' AND ';

        if (filter instanceof Array && filter.length > 0) {
            searchQuery += ' (';
            for (var i = 0; i < filter.length; i++) {
                if (i > 0) searchQuery += ' OR ';
                searchQuery += ' ALL:"*' + filter[i] + '*" ';
            }
            searchQuery += ') ';
        } else {
            searchQuery += ' ALL:"*' + filter + '*" ';
        }
    }

    if (assuntoId) {
        searchQuery += ' AND @spu\\:processo.Assunto:"' + assuntoId + '"'
    }

    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var processos = search.query(def);

	return processos
}
