function getProcessosParalelos(processoId) {
    var processo = getProcesso(processoId)
    var numero = processo.properties['spu:processo.Numero']
    var processoId = processo.nodeRef
    var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:processo" AND @spu\\:processo.Numero:"' + numero + '" AND NOT(ID:"' + processoId + '")')
	return processos
}
