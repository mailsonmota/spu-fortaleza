<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

var nome = json.get("nome")
var parentId = json.get("parentId")
var descricao = json.get("descricao")
var orgao = json.get("orgao")
var lotacao = json.get("lotacao")
var recebePelosSubsetores = json.get("recebePelosSubsetores")
var recebeMalotes = json.get("recebeMalotes")

var props = new Array()
props['cm:title'] = nome
props['cm:description'] = descricao
props['spu:protocolo.Orgao'] = orgao
props['spu:protocolo.Lotacao'] = lotacao
props['spu:protocolo.RecebePelosSubsetores'] = (recebePelosSubsetores == 'on') ? true : false;
props['spu:protocolo.RecebeMalotes'] = (recebeMalotes == 'on') ? true : false;

var protocoloId = url.extension

protocolo = alterarProtocolo(protocoloId, props, parentId)

model.protocolo = protocolo

