<import resource="/Company Home/Data Dictionary/Scripts/SPU/tipoprocesso.js">

var nome = json.get("nome")
var titulo = json.get("titulo")
var letra = json.get("letra")
var tramitacao = getNode(json.get("tramitacao"))
var abrangencia = getNode(json.get("abrangencia"))
var observacao = json.get("observacao")
var simples = json.get("simples")
var envolvidoSigiloso = json.get("envolvidoSigiloso")
var tiposManifestante = eval("(" + json.get("tiposManifestante") + ")")
var protocolos = eval("(" + json.get("protocolos") + ")")

//throw protocolos.length

var props = new Array()
props['cm:name'] = nome
props['cm:title'] = nome
props['spu:tipoprocesso.Letra'] = letra
props['spu:tipoprocesso.Tramitacao'] = tramitacao
props['spu:tipoprocesso.Abrangencia'] = abrangencia
props['spu:tipoprocesso.Observacao'] = observacao
props['spu:tipoprocesso.Simples'] = (simples == 'on') ? true : false;
props['spu:tipoprocesso.EnvolvidoSigiloso'] = (envolvidoSigiloso == 'on') ? true : false;


/*
var tipos = new Array()
for (t in tiposManifestante){
	tipos.push(getNode(t))
}
props['spu:tipoprocesso.TipoManifestante'] = tipos


var protArray = new Array()
for (p in protocolos) {
	protArray.push(getNode(p))
}
*/
props['spu:tipoprocesso.Protocolos'] = protocolos

var tiposManifestanteNodes = new Array()
for each (tiposManifestanteId in tiposManifestante) {
	tiposManifestanteNodes.push(getNode(tiposManifestanteId))
}
props['spu:tipoprocesso.TipoManifestante'] = tiposManifestanteNodes

tipoprocesso = inserirTipoProcesso(props)

model.tipoProcesso = tipoprocesso
