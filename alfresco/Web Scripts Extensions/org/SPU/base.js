var pastaRaizProtocolos = 'SPU';

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

		dataFormatada = new Date(dataAno, dataMes, dataDia)
	}

	return dataFormatada
}

function isDate (x) { 
	return (null != x) && !isNaN(x) && ("undefined" !== typeof x.getDate); 
}

function getCaixasEntrada() {
	var caixasEntrada = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:CaixaEntrada"');
	return caixasEntrada;
}

function getOrCreateFolder(parent, folderName, folderTitle) {
    var props = new Array()
    props["cm:name"] = folderName
    props["cm:title"] = (folderTitle) ? folderTitle : folderName

    return getOrCreateNode(parent, 'cm:folder', props)
}

function getOrCreateNode(parent, type, props) {
    var nodeName = props['cm:name']
    var existingNode = parent.childByNamePath(nodeName)
    var node = (!existingNode) ? parent.createNode(null, type, props) : existingNode;
    return node
}
