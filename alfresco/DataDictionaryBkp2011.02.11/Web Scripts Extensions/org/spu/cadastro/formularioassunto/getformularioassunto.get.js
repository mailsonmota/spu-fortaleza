<import resource="/Company Home/Data Dictionary/Scripts/SPU/assunto.js">
var idAssunto = url.extension;
var assunto = getAssunto(idAssunto)
if (assunto == undefined || assunto.length == 0)
{
	status.code = 404;
	status.message = "Assunto \"" + url.extension + "\" nao encontrado.";
	status.redirect = true;
}
if (assunto.children.length < 1)
{
	status.code = 404;
	status.message = "Nenhum Formulario encontrado.";
	status.redirect = true;
}
model.content = assunto.children[0].content
