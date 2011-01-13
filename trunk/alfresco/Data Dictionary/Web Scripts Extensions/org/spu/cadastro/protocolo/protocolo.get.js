<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">
var idProtocolo = url.extension
var protocolo = getProtocolo(idProtocolo)
if (protocolo == undefined || protocolo.length == 0)
{
	status.code = 404;
	status.message = "Protocolo \"" + url.extension + "\" nao encontrado.";
	status.redirect = true;
}
model.protocolo = protocolo;
