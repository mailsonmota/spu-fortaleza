<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">

function getProtocolos() {
	var caixasEntrada = getCaixasEntrada()
	var protocolos = new Array()
	var protocolo
	for (var i=0; i < caixasEntrada.length;i++) {
		protocolo = caixasEntrada[i].parent
		protocolos.push(protocolo)
	}
	return protocolos;
}

function getTodosProtocolos() {
	var protocolos = search.luceneSearch('+PATH:"/app:company_home/cm:SPU//*" +TYPE:"spu:Protocolo"');
	return protocolos
}
