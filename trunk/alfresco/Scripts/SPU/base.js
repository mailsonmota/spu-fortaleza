var pastaRaizProtocolos = 'SPU';
var limiteCaixas = 100;

function getNode(nodeId) {
	
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	
	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	
	return node;
}

function getNodeNodeRef(nodeRef) {
    var node = search.luceneSearch('ID:"' + nodeRef + '"');
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
	var caixasEntrada = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:caixaentrada"');
	/*if(caixasEntrada.length > limiteCaixas){
		throw ("Número de caixas excedido")
	}*/
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
    var node = (!existingNode) ? parent.createNode(nodeName, type, props) : existingNode;
    return node;

}

function sortByTitle(nodeA, nodeB) {
    return (nodeA.properties['cm:title'] < nodeB.properties['cm:title']) ? -1 : 1
}

function encodeToLuceneString(text) {
    text = text.replace(' ', "_x0020_");
    return text;
}

function removeEverythingAfterTheFirstWhiteSpace(text) {
    text = text.replaceAll("\\s([^\\s]*)", "");
    return text
}

function replaceLineBreaksForHTMLBreaks(text) {
    return text.replace(/(\r\n|[\r\n])/g, "<br />");
}

function encodeForAttributeQuery(string) {
    if (string) {    
        string = string.replace('"', '');    
        string = string.replace('/', '//');
    }
    return string;
}

function removeNode(nodeId) {
	var node = getNode(nodeId);
	node.remove();
}
