<import resource="/Company Home/Data Dictionary/Scripts/SPU/assunto.js">
var idAssunto = url.extension;
var assunto = getAssunto(idAssunto)
if (assunto == undefined || assunto.length == 0)
{
	status.code = 404;
	status.message = "Assunto \"" + url.extension + "\" nao encontrado.";
	status.redirect = true;
}
model.assunto = assunto
