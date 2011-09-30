//<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js"> // 271

var protocoloRaizId = args['protocoloRaizId']
var tipoProcessoId = args['tipoProcessoId']
var offset = args['offset']
var pageSize = args['pageSize']
var filter = args['filter']

//throw protocoloRaizId + ',' + tipoProcessoId + ', ' + offset + ',' + pageSize + ',' + filter // TODO FIXME debug

var resultado = new resultado();
model.protocolos = listarDestinos(protocoloRaizId, tipoProcessoId, filter, offset, pageSize);

function listarDestinos(protocoloRaizId, tipoProcessoId, filter, offset, pageSize) {
    if (protocoloRaizId == null || protocoloRaizId == '') { // Checa se há protocolo raiz especificado
        throw 'Listagem de Destino: não foi informado nenhum protocolo raiz.'
    }

    if (tipoProcessoId == null || tipoProcessoId == '') { // Checa se há tipo de processo especificado
        listarDestinosConf(resultado, filter, pageSize)
        return resultado.vetor
    }

    var protocoloRaizNode = getNode(protocoloRaizId)
    var protocoloRaizNome = protocoloRaizNode.name
    //throw protocoloRaizNome // TODO FIXME debug
    var tipoProcessoNode = getNode(tipoProcessoId)

    var categoryAbrangenciaNode
    if (tipoProcessoNode.properties['spu:tipoprocesso.Abrangencia']) {
        categoryAbrangenciaNode = tipoProcessoNode.properties['spu:tipoprocesso.Abrangencia']
    } else {
        listarDestinosConf(resultado, filter, pageSize)
        return resultado.vetor
    }

    if (categoryAbrangenciaNode.name == 'Interna') {
        listarDestinosConf(resultado, filter, pageSize, protocoloRaizNome)
        return resultado.vetor
    } else {
        listarDestinosConf(resultado, filter, pageSize)
        return resultado.vetor
    }

    return resultado.vetor
}

function listarDestinosConf(resultado, filter, pageSize, protocoloRaizName) {
    if (protocoloRaizName != '' && protocoloRaizName != null && protocoloRaizName != undefined) {
        /* TODO FIXME
           Erro na linha abaixo.
           Caso o protocoloRaizName seja um protocolo de raiz, de primeiro nível, como SAM, a linha abaixo está OK.
           Caso o protocoloRaizName seja um nome de protocolo não-raiz, como DAF (SAM/GABS/DAF),
           então a var searchQuery conterá 'PATH:"/app:company_home/cm:SPU/cm:PMF/cm:DAF//* ...', o que não existe. */
        var searchQuery = 'PATH:"/app:company_home/cm:SPU/cm:PMF/cm:' + protocoloRaizName + '//*" AND TYPE:"spu:protocolo"'
    } else {
        var searchQuery = 'PATH:"/app:company_home/cm:SPU/cm:PMF//*" AND TYPE:"spu:protocolo"'
    }

    if (filter != '' && filter != null && filter != undefined) {
        searchQuery += ' AND @spu\\:protocolo.Path:"*' + filter + '*"'
    }

    /*var sort1 = {
        column: "@{extension.spu}protocolo.Path",
        ascending: true
    };*/

    var paging = {
        maxItems: pageSize,
        skipCount: 0
        //sort:sort1
    };

    var def = {
        query: searchQuery,
        //language: "lucene",
        paging: paging
        //sort:[sort1]
    };

    resultado.vetor = search.query(def);

    // TODO FIXME debug block
    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% todos os protocolos: '

    for each (pos in resultado.vetor) { resultado.debug += pos.properties['spu:protocolo.Path'] + ', ' }
    throw resultado.debug
    throw 'qwe'
    excluirProibidos(resultado)

    return resultado
}

function excluirProibidos(resultado) {
    var pathsProibidos = new Array()
    var protocolosIndicados = new Array()
    for (var i = 0; i < resultado.vetor.length; i++) {
        if (resultado.vetor[i].assocs['spu:protocolo.SetorProtocolo']) {
            pathsProibidos.push(resultado.vetor[i].properties['spu:protocolo.Path'])
            protocolosIndicados.push(resultado.vetor[i].assocs['spu:protocolo.SetorProtocolo'][0])
        }
    }

    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% protocolos proibidos: '
    for each (pos in pathsProibidos) { resultado.debug += pos + ', ' }

    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% quem excluir: '
    var posicoesParaExcluir = new Array()
    for (var i = 0; i < resultado.vetor.length; i++) {
        for (var j = 0; j < pathsProibidos.length; j++) {
            var regex = '^' + pathsProibidos[j]
            var matchPos = resultado.vetor[i].properties['spu:protocolo.Path'].search(regex)
            if (matchPos > -1) {
                posicoesParaExcluir.push(i)
                resultado.debug += resultado.vetor[i].properties['spu:protocolo.Path'] + ', '
            }
        }
    }

    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% posicoes para excluir: '
    for each (pos in posicoesParaExcluir) { resultado.debug += pos + ', ' }

    var vetorAux = new Array()
    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% flags: '
    for (var i = 0; i < resultado.vetor.length; i++) {
        var flag = 1
        for each (posicaoParaExcluir in posicoesParaExcluir) {
            if (posicaoParaExcluir == i) {
                flag = 0
            }
        }
        if (flag) vetorAux.push(resultado.vetor[i])
        resultado.debug += flag + ', '
    }

    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% todos os protocolos apos vetorAux: '
    for each (pos in resultado.vetor) { resultado.debug += pos.properties['spu:protocolo.Path'] + ', ' }

    for (var i = 0; i < protocolosIndicados.length; i++) {
        vetorAux.push(protocolosIndicados[i]);
    }

    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% todos os protocolos apos inclusao de protocolos indicados: '
    for each (pos in resultado.vetor) { resultado.debug += pos.properties['spu:protocolo.Path'] + ', ' }

    vetorAux.sort(sortByProtocoloPath)

    resultado.debug += '%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% todos os protocolos apos inclusao de protocolos indicados e apos o sort: '
    for each (pos in resultado.vetor) { resultado.debug += pos.properties['spu:protocolo.Path'] + ', ' }

    // Diminuindo o resultado para no máximo 20 resultado
    resultado.vetor = new Array()
    for (var i = 0; i < 20; i++) {
        if (vetorAux[i]) {
            resultado.vetor.push(vetorAux[i])
        }
    }
}

function resultado() {
    this.vetor = new Array()
    this.pathsProibidos = new Array()
    this.debug = ''
}

function sortByProtocoloPath(a, b) {
    var x = a.properties['spu:protocolo.Path']
    var y = b.properties['spu:protocolo.Path']
    return ((x < y) ? -1 : ( x > y ? 1 : 0))
}

// finalidade de debug
function getNode(nodeId) {
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');

	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	return node;
}
