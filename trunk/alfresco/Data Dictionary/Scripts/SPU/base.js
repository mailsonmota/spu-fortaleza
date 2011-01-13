var pastaRaizProtocolos = 'SPU';
var limiteCaixas = 100;

function getNode(nodeId) {
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	return node;
}

function getDataFormatadaAlfresco(dataEmPortugues) {
	var dataFormatada = dataEmPortugues
	
	if (!isDate(dataFormatada)) {
		var dataDia = dataFormatada.substring(0,2)
		var dataMes = dataFormatada.substring(3,5)
		var dataAno = dataFormatada.substring(6,10)

		//dataFormatada = new Date(dataAno, dataMes, dataDia)
		dataFormatada = new Date(dataMes + "/" + dataDia + "/" + dataAno);
	}

	return dataFormatada
}

function isDate (x) { 
	return (null != x) && !isNaN(x) && ("undefined" !== typeof x.getDate); 
}

function getCaixasEntrada() {
	var caixasEntrada = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:CaixaEntrada"');
	if(caixasEntrada.length > limiteCaixas){
		//throw "Número de caixas excedido"
	}
	return caixasEntrada;
}

function getCaixasAnalise() {
	var caixasAnalise = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:caixaanalise"');
	if(caixasAnalise.length > limiteCaixas){
		//throw "Número de caixas excedido"
	}
	return caixasAnalise;
}

function getCaixasArquivo() {
	var caixasArquivo = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:caixaarquivo"');
	if(caixasArquivo.length > limiteCaixas){
		//throw "Número de caixas excedido"
	}
	return caixasArquivo;
}

function getOrCreateFolder(parent, folderName, folderTitle) {
    var props = new Array();
    props["cm:name"] = folderName;
    props["cm:title"] = (folderTitle) ? folderTitle : folderName;

    return getOrCreateNode(parent, 'cm:folder', props);
}

function getOrCreateNode(parent, type, props) {
    var nodeName = props['cm:name'];
    var existingNode = parent.childByNamePath(nodeName);
    var node = (!existingNode) ? parent.createNode(null, type, props) : existingNode;
    return node;
}
