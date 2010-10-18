function getAssuntos() {
	var assuntos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:assunto"');

	return assuntos;
}

function getAssunto(nodeId) {
	var assunto = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	if (assunto != undefined && assunto.length > 0) {
		assunto = assunto[0];
	}
	return assunto;
}
