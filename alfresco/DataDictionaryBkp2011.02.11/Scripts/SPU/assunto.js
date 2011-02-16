<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
/**
 * Assunto
 * 
 * Métodos para gerenciar os Assuntos do Sistema de Protocolo Único.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 19/10/2010
*/

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

function alterarAssunto(assuntoId, props) {
	var assunto = getAssunto(assuntoId);
	
	assunto.properties.name = props['cm:title']
	assunto.properties.title = props['cm:title']
	assunto.properties["spu:assunto.Corpo"] = props['spu:assunto.Corpo'];
	assunto.properties["spu:assunto.NotificarNaAbertura"] = props['spu:assunto.NotificarNaAbertura'];
	assunto.save()
		
	assunto = getAssunto(assuntoId);
	return assunto;
}

function inserirAssunto(tipoProcessoId, props) {
	var tipoProcesso = getNode(tipoProcessoId);
	return tipoProcesso.createNode(props['cm:title'], "spu:assunto", props)
}

function removerAssunto(assuntoIdArray) {
	for (i=0; i < assuntoIdArray.length; i++){
		removeNode(assuntoIdArray[i]);
	}
}
