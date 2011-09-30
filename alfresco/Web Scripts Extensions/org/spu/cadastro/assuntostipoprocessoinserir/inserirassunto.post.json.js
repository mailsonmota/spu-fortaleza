<import resource="/Company Home/Data Dictionary/Scripts/SPU/assunto.js">

var tipoProcessoId = json.get("tipoProcessoId")
var nome = json.get("nome")
var corpo = json.get("corpo")
var notificarNaAbertura = (json.has("notificarNaAbertura")) ? json.get("notificarNaAbertura") : ''

var props = new Array()
props['cm:title'] = nome
props['spu:assunto.Corpo'] = corpo
props['spu:assunto.NotificarNaAbertura'] = (notificarNaAbertura == 'on') ? true : false;

assunto = inserirAssunto(tipoProcessoId, props)

model.assunto = assunto	