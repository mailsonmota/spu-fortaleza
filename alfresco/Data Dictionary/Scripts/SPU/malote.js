<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">

function criarMalote(descMalote, tipo){
	var props = new Array()
	props['spu:malote.TipoMalote'] = 'workspace://SpacesStore/' + tipo
	return userhome.createNode(descMalote,"spu:malote", props)
}

function adicionarCopia(malote, copia){
	//TODO
	var props = new Array()
	props['spu:malote.TipoMalote'] = 'workspace://SpacesStore/' + tipo
	return malote.createNode(descMalote,"spu:malote", props)
}

function getTipoMalotes() {
	var prioridades = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Tipo_x0020_de_x0020_Malote//*\"');
	return prioridades;
}
