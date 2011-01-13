function getNode(nodeId) {
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	return node;
}

function getArquivos(processoId) {
    var processo = getNode(processoId);
    var arquivos = new Array();
    var path = processo.getQnamePath();
    var arquivos_aux = search.luceneSearch("workspace://SpacesStore", 'PATH:"' + path + '/*" AND TYPE:"cm:content"');
    if (arquivos_aux.length > 0) {
        for (i = 0; i < arquivos_aux.length; i++) {
	        arquivos.push(arquivos_aux[i]);
        }
    }
    return arquivos;
}

var processoId = url.extension;
var arquivos = getArquivos(processoId);
model.arquivos = arquivos


