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
    var paging =
    {
        maxItems: 20,
        skipCount: 0
    };
    var def =
    {
        query: '+PATH:"app:company_home/cm:SPU//*" +TYPE:"spu:protocolo"',
        language: "lucene",
        page: paging
    };
    var protocolos = search.query(def);
	//var protocolos = search.luceneSearch('+PATH:"app:company_home/cm:SPU//*" +TYPE:"spu:protocolo"');
	return protocolos
}

function getTodosProtocolosPaginado(offset, pageSize, filter) {
    filter = (filter) ? encodeForAttributeQuery(filter) : null;
    var searchQuery = '+PATH:"app:company_home/cm:SPU/cm:PMF//*" +TYPE:"spu:protocolo"';
    if (filter && filter != '') {
        searchQuery += ' AND @spu\\:protocolo.Path:"*' + filter + '*"';
    }   
    
    var paging =
    {
        maxItems: pageSize,
        skipCount: offset
    };
    var sort1 =
    {
        column: "@{extension.spu}protocolo.Path",
        ascending: true
    };
    var def =
    {
        query: searchQuery,
        language: "lucene",
        page: paging,
        sort:[sort1]
    };

    var protocolos = search.query(def);

	return protocolos
}

function getOrCreateProtocolo(parent, grupo, nome, titulo, orgao, lotacao) {
    var props = new Array()
    props['cm:name'] = nome
    props['cm:title'] = (titulo) ? titulo : nome
    props['spu:protocolo.Orgao'] = (orgao) ? orgao : null
    props['spu:protocolo.Lotacao'] = (lotacao) ? lotacao : null
        
    var protocolo = getOrCreateNode(parent, "spu:protocolo", props)
    var path = (protocolo.displayPath + '/' + protocolo.name).replace('/Company Home/SPU/PMF/','')
    protocolo.properties['spu:protocolo.Path'] = path 
    protocolo.save()
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
        caixaEntrada.setPermission('Consumer', grupo.properties.authorityName)
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
        caixaSaida.setPermission('Editor', grupo.properties.authorityName)
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
        arquivo.setPermission('Editor', grupo.properties.authorityName)
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

function getProtocolosRaiz(protocoloOrigemId, tipoProcessoId) {
    if (tipoProcessoId) {
        var tipoProcesso = getNode(tipoProcessoId)
        var abrangencia = getAbrangenciaInterna()

        if (String(tipoProcesso.properties['spu:tipoprocesso.Abrangencia'].nodeRef) == String(abrangencia.nodeRef)) {
            var protocolo = getNode(protocoloOrigemId)
            var protocoloRaiz = getProtocoloRaizFromProtocolo(protocolo)

            return [protocoloRaiz]
        }
    }

    var searchQuery = '+PATH:"app:company_home/cm:SPU/cm:PMF/*" +TYPE:"spu:protocolo"'
    var sort1 =
    {
        column: "@{extension.spu}protocolo.Path",
        ascending: true
    };
    var def =
    {
        query: searchQuery,
        language: "lucene",
        sort:[sort1]
    };

    var protocolos = search.query(def)

    return protocolos
}

function getProtocoloRaizFromProtocolo(protocolo) {
    while (protocolo.properties['spu:protocolo.Path'].match(/\//) != null) {
        protocolo = protocolo.parent
    }

    return protocolo
}

/* função para, considerando que alguns protocolos podem receber processos
   pelos seus filhos, listar todos os protocolos abaixo de um dado protocolo */
function getProtocolosUnder(protocoloRaizId, protocoloOrigemId) {
    var protocoloRaiz = getNode(protocoloRaizId)

    if (protocoloRaiz.assocs['spu:protocolo.SetorProtocolo'] != null) {
        if (protocoloOrigemId) {
            var protocoloOrigemRaiz = getProtocoloRaizFromProtocolo(getNode(protocoloOrigemId))

            if (String(protocoloRaiz.nodeRef) != String(protocoloOrigemRaiz.nodeRef)) {
                return [getNodeNodeRef(protocoloRaiz.assocs['spu:protocolo.SetorProtocolo'][0].nodeRef)]
            }
        } else {
            return [getNodeNodeRef(protocoloRaiz.assocs['spu:protocolo.SetorProtocolo'][0].nodeRef)]
        }
    }

    var protocolos = search.luceneSearch(
        'PATH:"/app:company_home/cm:SPU/cm:PMF/cm:' + protocoloRaiz.name + '//*" AND TYPE:"spu:protocolo"'
    )

    protocolos.unshift(protocoloRaiz)

    return protocolos
}

function getAbrangenciaInterna() {
    searchQuery = 'PATH:"/cm:generalclassifiable/cm:SPU/cm:Abrangencia/cm:Interna"'
    return search.luceneSearch(searchQuery)[0]
}

function getAbrangenciaExterna() {
    searchQuery = 'PATH:"/cm:generalclassifiable/cm:SPU/cm:Abrangencia/cm:Externa"'
    return search.luceneSearch(searchQuery)[0]
}

