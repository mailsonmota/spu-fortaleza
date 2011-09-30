<import resource="/Company Home/Data Dictionary/Scripts/SPU/criartiposprocesso.js">

var tipoProcesso = json.get("tipoProcesso")
var letra = json.get("letra")
var assunto = json.get("assunto")
var formName = json.get("formName")
var conteudo = json.get("conteudo")
var envolvidos = json.get("envolvidos")
var tramitacao = json.get("tramitacao")
var abrangencia = json.get("abrangencia")

if(conteudo.length() == 0){
    criarTiposProcessoAssunto(tipoProcesso, letra, assunto, envolvidos, tramitacao, abrangencia)
}else{
    criarTiposProcessoAssuntoFormulario(tipoProcesso, letra, assunto, formName, conteudo, envolvidos, tramitacao, abrangencia)
}


model.mensagem = "Sucesso"
