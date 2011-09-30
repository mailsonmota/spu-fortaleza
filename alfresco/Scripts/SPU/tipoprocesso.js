<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
/**
 * Tipos de Processo
 * 
 * Métodos para gerenciar os Tipos de Processo do Sistema de Protocolo Único.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 29/09/2010
*/

function getTipoProcesso(id) {
	var tipoProcesso = getNode(id)
	return tipoProcesso
}

var pastaRaizTiposProcesso = "Data Dictionary/Tipos de Processo/";

function getTiposProcesso() {
	var tiposProcesso = new Array()
	var pastaRaiz = companyhome.childByNamePath(pastaRaizTiposProcesso);

	if (pastaRaiz != undefined && pastaRaiz.isContainer) {
		tiposProcesso = pastaRaiz.getChildren();
	}

    tiposProcesso.sort(sortByTitle)

	return tiposProcesso;
}

function getTipoProcessoPorNome(nomeTipoProcesso) {
	var tipoProcesso = companyhome.childByNamePath(pastaRaizTiposProcesso + nomeTipoProcesso);
	if (tipoProcesso == undefined)	{
		status.code = 404;
		status.message = "Tipo de Processo nao encontrado.";
		status.redirect = true;
	}

	return tipoProcesso;
}

function getAssuntosPorTipoProcesso(idTipoProcesso) {
	tipoProcesso = getTipoProcesso(idTipoProcesso);
	var pathTipoProcesso = tipoProcesso.getQnamePath();
	var assuntos = search.luceneSearch(
		"workspace://SpacesStore", 'PATH:"' + pathTipoProcesso + '/*" AND TYPE:"spu:assunto"'
	);
	return assuntos;
}

function getTramitacoes() {
	var assuntos = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Tramitacao//*\"');
	return assuntos;
}

function getAbrangencias() {
	var abrangencias = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Abrangencia//*\"');
	return abrangencias;
}

function getTiposManifestante() {
	var tiposManifestante = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Tipo_x0020_de_x0020_Manifestante//*\"');
	return tiposManifestante;
}

function getOrCreateTipoProcesso(parent, nome, titulo, letra) {
    var props = new Array()
    props['cm:name'] = nome
    props['cm:title'] = (titulo) ? titulo : nome
    props['spu:tipoprocesso.TipoManifestante'] = getDefaultTiposManifestante()
	//Sigla? ou Letra
    //props['spu:tipoprocesso.Sigla'] = (sigla) ? sigla : 'XX'
    props['spu:tipoprocesso.Letra'] = (letra) ? letra : 'XX'

    return getOrCreateNode(parent, "spu:tipoprocesso", props)
}

function getOrCreateAssunto(tipoProcesso, nome, titulo) {
    var props = new Array()
    props['cm:name'] = nome
    props['cm:title'] = (titulo) ? titulo : nome
    
    return getOrCreateNode(tipoProcesso, "spu:assunto", props)
}


function inserirTipoProcesso(props) {
	var pastaRaiz = companyhome.childByNamePath(pastaRaizTiposProcesso);
	var tipoProcesso = pastaRaiz.createNode(props['cm:name'], "spu:tipoprocesso", props);

	for (var i=0; i < props['spu:tipoprocesso.Protocolos'].length; i++) {
		var scriptNode = getNode(props['spu:tipoprocesso.Protocolos'][i])
		tipoProcesso.createAssociation(scriptNode, "spu:tipoprocesso.Protocolos")
	}

    return tipoProcesso;
}

function alterarTipoProcesso(tipoProcessoId, props) {
	var tipoProcesso = getTipoProcesso(tipoProcessoId);
	
	tipoProcesso.properties.name = props['cm:name']
	tipoProcesso.properties.title = props['cm:title']
	tipoProcesso.properties["spu:tipoprocesso.Letra"] = props['spu:tipoprocesso.Letra']
	tipoProcesso.properties["spu:tipoprocesso.Tramitacao"] = props['spu:tipoprocesso.Tramitacao']
	tipoProcesso.properties["spu:tipoprocesso.Abrangencia"] = props['spu:tipoprocesso.Abrangencia']
	tipoProcesso.properties["spu:tipoprocesso.Observacao"] = props['spu:tipoprocesso.Observacao']
	tipoProcesso.properties["spu:tipoprocesso.Simples"] = props['spu:tipoprocesso.Simples']
	tipoProcesso.properties["spu:tipoprocesso.EnvolvidoSigiloso"] = props['spu:tipoprocesso.EnvolvidoSigiloso']
	tipoProcesso.properties["spu:tipoprocesso.TipoManifestante"] = props['spu:tipoprocesso.TipoManifestante']
	tipoProcesso.save()

	for (var i=0; i < props['spu:tipoprocesso.Protocolos'].length; i++) {
		tipoProcesso.createAssociation(props['spu:tipoprocesso.Protocolos'][i], "spu:tipoprocesso.Protocolos")
	}
	
	tipoProcesso = getTipoProcesso(tipoProcessoId);
	return tipoProcesso;
}
