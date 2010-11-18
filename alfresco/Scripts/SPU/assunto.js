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
