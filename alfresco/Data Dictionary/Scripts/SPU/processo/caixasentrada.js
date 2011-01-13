function getCaixaEntradaOld() {
	//função original, funciona bem, mas não performatica
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

function getCaixaEntrada() {
	//levemente mais performatico que o original, mas ainda pode comprometer a performance quando houver muitos processos
	var caixasEntrada = getCaixasEntrada()
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo" AND NOT (@spu\\:processo.EmAnalise:true)')
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

function getCaixaAnalise() {
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
