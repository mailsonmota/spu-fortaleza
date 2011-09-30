var idTipoProcesso = url.extension;
var tiposProcesso = search.luceneSearch('ID:"workspace://SpacesStore/' + idTipoProcesso + '"');
if (tiposProcesso == undefined || tiposProcesso.length == 0)
{
	status.code = 404;
	status.message = "Tipo de Processo \"" + url.extension + "\" nao encontrado.";
	status.redirect = true;
}
var tipoProcesso = tiposProcesso[0];
model.tipoProcesso = tipoProcesso;
