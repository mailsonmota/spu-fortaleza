var nomeTipoProcesso = url.extension;

var tipoProcesso = companyhome.childByNamePath("Data Dictionary/Tipos de Processo/" + nomeTipoProcesso);
if (tipoProcesso == undefined)
{
	status.code = 404;
	status.message = "Tipo de Processo nao encontrado.";
	status.redirect = true;
}

var categoriaAssuntos = tipoProcesso.properties["spu:CategoriaPaiDosAssuntos"];

categoriaAssuntos = categoriaAssuntos.replace(" ", "_x0020_");

var assuntos = search.luceneSearch("PATH:\"/cm:generalclassifiable/cm:Assuntos/cm:" + categoriaAssuntos + "\"");
model.luceneSearch = "PATH:\"/cm:generalclassifiable/cm:Assuntos/cm:" + categoriaAssuntos + "\"";
