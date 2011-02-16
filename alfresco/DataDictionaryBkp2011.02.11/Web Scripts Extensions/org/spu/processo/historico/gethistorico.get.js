<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo.js">
var idProcesso = url.extension
var processo = getProcesso(idProcesso)
if (processo == undefined || processo.length == 0)
{
	status.code = 404;
	status.message = "Processo \"" + url.extension + "\" nao encontrado.";
	status.redirect = true;
}
model.processo = processo;
model.movimentacoes = getMovimentacoes(idProcesso);
