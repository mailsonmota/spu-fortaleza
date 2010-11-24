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
		processosCaixaEntrada = search.luceneSearch("workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo" AND NOT (@spu\\:processo.EmAnalise:true)')
		for (j = 0; j < processosCaixaEntrada.length; j++) {
			processos.push(processosCaixaEntrada[j])
		}
	}

	return processos
}

function getCaixaAnalise() {
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processosCaixaEntrada
	var processos = new Array()
	var path
	var j
	for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		path = caixaEntrada.getQnamePath()
		processosCaixaEntrada = search.luceneSearch(
			"workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo" AND @spu\\:processo.EmAnalise:true AND NOT (@spu\\:processo.Externo:true)'
		)
		for (j = 0; j < processosCaixaEntrada.length; j++) {
			processos.push(processosCaixaEntrada[j])
		}
	}

	return processos
}

function getCaixaExternos() {
	var caixasEntrada = getCaixasEntrada()
	var caixaEntrada
	var processosCaixaEntrada
	var processos = new Array()
	var path
	var j
	for (var i=0; i < caixasEntrada.length;i++) {
		caixaEntrada = caixasEntrada[i]
		path = caixaEntrada.getQnamePath()
		processosCaixaEntrada = search.luceneSearch(
			"workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"spu:Processo" AND @spu\\:processo.EmAnalise:true AND @spu\\:processo.Externo:true'
		)
		for (j = 0; j < processosCaixaEntrada.length; j++) {
			processos.push(processosCaixaEntrada[j])
		}
	}

	return processos
}
