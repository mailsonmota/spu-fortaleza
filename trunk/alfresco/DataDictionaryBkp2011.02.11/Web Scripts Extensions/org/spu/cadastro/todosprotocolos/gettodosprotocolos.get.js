<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">

var protocolos = getTodosProtocolos()
var map = new Array();
var lista = new Array();

for(var i = 0; i < protocolos.length; i++){
	var id = protocolos[i].webdavUrl.replace('/webdav/SPU/PMF/','')
	var aux = id.split('/')	
	var pai = (aux.length >= 2)?(aux[aux.length - 2]):''
	map[id] = [protocolos[i], pai, aux.length]
	lista.push(id)
}

lista.sort()
model.hierarquias = map
model.chaves = lista
