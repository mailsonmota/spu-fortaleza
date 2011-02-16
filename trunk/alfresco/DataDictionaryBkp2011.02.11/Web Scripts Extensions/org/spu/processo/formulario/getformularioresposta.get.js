<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">

var idProcesso = url.extension;
var processo = getProcesso(idProcesso)
if (processo == undefined || processo.length == 0) {
	status.code = 404;
	status.message = "Processo \"" + url.extension + "\" nao encontrado.";
	status.redirect = true;
}

var path = processo.getQnamePath()
var searchQuery = 'TYPE:"spu:respostasFormulario" AND PATH:"' + path + '/*"';
var respostas = search.luceneSearch(searchQuery)
if (respostas.length < 1) {
    status.code = 404;
	status.message = "Nenhuma resposta encontrada no processo \"" + url.extension;
	status.redirect = true;
} else {
    model.content = respostas[0].content
}
