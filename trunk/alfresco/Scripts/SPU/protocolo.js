<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">

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
	var protocolos = search.luceneSearch('+PATH:"/app:company_home/cm:SPU//*" +TYPE:"spu:Protocolo"');
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
    getOrCreateCaixaSaida(protocolo, grupo)
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
        caixaEntrada.setPermission('Coordinator', grupo)
    }

    return caixaEntrada
}

function getOrCreateCaixaSaida(protocolo, grupo) {
    var props = new Array()
    props['cm:name'] = 'Caixa de Saida'
    props['cm:title'] = 'Caixa de Saída'
    
    var caixaSaida = getOrCreateNode(protocolo, "spu:caixasaida", props)
    caixaSaida.setInheritsPermissions(false)

    if (grupo) {
        caixaSaida.setPermission('Consumer', grupo)
    }

    return caixaSaida
}

function getOrCreateArquivo(protocolo, grupo) {
    var props = new Array()
    props['cm:name'] = 'Arquivo'
    props['cm:title'] = props['cm:name']

    var arquivo = getOrCreateNode(protocolo, "spu:arquivo", props)
    arquivo.setInheritsPermissions(false)

    if (grupo) {
        arquivo.setPermission('Coordinator', grupo)
    }

    return arquivo
}
