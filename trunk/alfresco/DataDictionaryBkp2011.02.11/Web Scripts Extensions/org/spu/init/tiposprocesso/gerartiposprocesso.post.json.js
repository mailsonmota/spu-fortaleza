<import resource="/Company Home/Data Dictionary/Scripts/SPU/criartiposprocesso.js">

var tipoProcesso = json.get("tipoProcesso")
var letra = json.get("letra")
var assunto = json.get("assunto")
var formName = json.get("formName")
var conteudo = json.get("conteudo")

criarTiposProcessoAssuntoFormulario(tipoProcesso, letra, assunto, formName, conteudo)

model.mensagem = "Sucesso" 
