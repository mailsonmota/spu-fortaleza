<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">

function getProtocolo(id) {
	var protocolo = getNode(id)
	return protocolo
}

function getProtocolos() {
	var caixasEntrada = getCaixasEntrada()
	var protocolos = new Array()
	var protocolo
	for (var i=0; i < caixasEntrada.length;i++) {
		protocolo = caixasEntrada[i].parent
		protocolos.push(protocolo)
	}
	return protocolos;
}

function getTodosProtocolos() {
	var protocolos = search.luceneSearch('+PATH:"app:company_home/cm:SPU//*" +TYPE:"spu:Protocolo"');
	return protocolos
}

function getOrCreateProtocolo(parent, grupo, nome, titulo, orgao, lotacao) {
    var props = new Array()
    props['cm:name'] = nome
    props['cm:title'] = (titulo) ? titulo : nome
    props['spu:protocolo.Orgao'] = (orgao) ? orgao : null
    props['spu:protocolo.Lotacao'] = (lotacao) ? lotacao : null
    
    var protocolo = getOrCreateNode(parent, "spu:protocolo", props)

    getOrCreateCaixaEntrada(protocolo, grupo)
    getOrCreateCaixaAnalise(protocolo, grupo)
    getOrCreateArquivo(protocolo, grupo)

    return protocolo
}

function getOrCreateCaixaEntrada(protocolo, grupo) {
    var props = new Array()
    props['cm:name'] = 'Caixa de Entrada'
    props['cm:title'] = props['cm:name']
    
    var caixaEntrada = getOrCreateNode(protocolo, "spu:caixaentrada", props)
    caixaEntrada.setInheritsPermissions(false)
    
    if (grupo) {
        caixaEntrada.setPermission('Consumer', grupo)
    }

    return caixaEntrada
}

function getOrCreateCaixaAnalise(protocolo, grupo) {
    var props = new Array()
    props['cm:name'] = 'Caixa de Analise'
    props['cm:title'] = 'Caixa de Análise'
    
    var caixaSaida = getOrCreateNode(protocolo, "spu:caixaanalise", props)
    caixaSaida.setInheritsPermissions(false)

    if (grupo) {
        caixaSaida.setPermission('Editor', grupo)
    }

    return caixaSaida
}

function getOrCreateArquivo(protocolo, grupo) {
    var props = new Array()
    props['cm:name'] = 'Arquivo'
    props['cm:title'] = props['cm:name']

    var arquivo = getOrCreateNode(protocolo, "spu:caixaarquivo", props)
    arquivo.setInheritsPermissions(false)

    if (grupo) {
        arquivo.setPermission('Editor', grupo)
    }

    return arquivo
}
function getPastaRaizProtocolos() {
	var pastaRaiz = companyhome.childByNamePath("SPU/PMF")
	return pastaRaiz
}

function alterarProtocolo(protocoloId, props, parentId) {
	var protocolo = getProtocolo(protocoloId);
	var parentAtualId = protocolo.parent.properties["sys:node-uuid"]
	
	if (parentId == 0) {
		pastaRaiz = getPastaRaizProtocolos()
		parentId = pastaRaiz.properties["sys:node-uuid"]
	}
	
	protocolo.properties.title = props['cm:title']
	protocolo.properties.description = props['cm:description'];
	protocolo.properties["spu:protocolo.Orgao"] = props['spu:protocolo.Orgao'];
	protocolo.properties["spu:protocolo.Lotacao"] = props['spu:protocolo.Lotacao'];
	protocolo.properties["spu:protocolo.RecebePelosSubsetores"] = props['spu:protocolo.RecebePelosSubsetores'];
	protocolo.properties["spu:protocolo.RecebeMalotes"] = props['spu:protocolo.RecebeMalotes'];
	protocolo.save()
	
	//Se houver mudança de parent do nó
	if (parentAtualId != parentId) {
		var protocoloDestino = getNode(parentId)
		protocolo.move(protocoloDestino)
	}	
	
	protocolo = getProtocolo(protocoloId);
	return protocolo;
}