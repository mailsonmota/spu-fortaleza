//<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js"> // 271

/*
var protocoloRaizId = url.templateArgs['protocoloRaizId']
var tipoProcessoId = url.templateArgs['tipoProcessoId']
var offset = url.templateArgs['offset']
var pageSize = url.templateArgs['pageSize']
var filter = url.templateArgs['filter']
*/

///*
var protocoloRaizId = args['protocoloRaizId']
var tipoProcessoId = args['tipoProcessoId']
var offset = args['offset']
var pageSize = args['pageSize']
var filter = args['filter']
//*/

function getProtocolosWithDepthFromRoot(rootNode, resultado, filter, pageSize) {
    if (resultado.vetor.length == pageSize) {
        return
    }
    
    resultado.vetor.push(rootNode)
    
    if (rootNode.properties['spu:protocolo.RecebePelosSubsetores'] == true) {
        // throw 'true no recebe pelso sub' // DEBUG
        return
    }
    
    path = rootNode.getQnamePath()
    var searchQuery = 'PATH:"' + path + '/*" AND TYPE:"spu:protocolo"'
    if (filter != '') {
        //searchQuery += ' AND @spu\\:protocolo.Path:"*' + filter + '*"'
        searchQuery += ' AND (@cm\\:name:"*' + filter + '*" OR @spu\\:protocolo.Path:"*' + filter + '*")'
    }
    
    var rootNodeChildren = search.luceneSearch("workspace://SpacesStore", searchQuery)

    if (rootNodeChildren.length == 0) {
        return
    }
    
    for (var i = 0; i < rootNodeChildren.length; i++) {
        getProtocolosWithDepthFromRoot(rootNodeChildren[i], resultado, filter, pageSize)
    }
    
    return
}

function listarTodosDestinos(resultado, filter, pageSize) {
    var searchQuery = 'PATH:"/app:company_home/cm:SPU/cm:PMF//*" AND TYPE:"spu:protocolo"'
    if (filter != '') {
        searchQuery += ' AND @spu\\:protocolo.Path:"*' + filter + '*"'
        //searchQuery += ' AND (@cm\\:name:"*' + filter + '*" OR @spu\\:protocolo.Path:"*' + filter + '*")'
    }
    
    //throw searchQuery
    
    var paging = {maxItems: pageSize, skipCount: offset};
    var def = {query: searchQuery, language: "lucene", page: paging};
    var nosRaiz = search.query(def);
    
    //var nosRaiz = search.luceneSearch('workspace://SpacesStore', searchQuery)

    // DEBUG
    var resultDebug = ''
    for (var i = 0; i < nosRaiz.length; i++) {
        resultDebug += nosRaiz[i].properties['spu:protocolo.Path'] + ", "
    }
    resultDebug += '______________________' + searchQuery
    throw resultDebug

    for (var i = 0; i < nosRaiz.length; i++) {
        getProtocolosWithDepthFromRoot(nosRaiz[i], resultado, filter, pageSize)
    }

    // DEBUG
    //throw resultado.vetor.length
    //throw resultado.vetor[1].properties['spu:protocolo.Path'] // DEBUG
    /*var resultDebug = ''
    for (var i = 0; i < resultado.vetor.length; i++) {
        resultDebug += resultado.vetor[i].properties['spu:protocolo.Path'] + ', '
    }
    throw resultDebug*/
    
    return resultado
}

function resultado() {
    this.vetor = new Array()
}

var resultado = new resultado()
    
function listarDestinos(protocoloRaizId, tipoProcessoId, filter, offset, pageSize) {
    var protocoloRaizNode = getNode(protocoloRaizId)

    // Não há tipoProcessoId especificado
    if (tipoProcessoId == null || tipoProcessoId == '') {
        listarTodosDestinos(resultado, filter, pageSize)

        return resultado.vetor
    }
    
    tipoProcessoNode = getNode(tipoProcessoId)
    
    // tipoProcessoId tem abrangência externa
    if (tipoProcessoNode.properties['spu:tipoprocesso.Abrangencia'] == 'externa') {
        listarTodosDestinos(resultado, filter, pageSize)
    }

    // tipoProcessoId tem abrangência interna
    if (tipoProcessoNode.properties['spu:processo.Abrangencia'] == 'interna') {
        // Iterar apenas protocolos abaixo do protocolo "protocoloRaizNode"
        // protocolos = search.luceneSearch('workspace://SpacesStore', 'PATH:"' +  protocoloRaizNode.getQnamePath() + '/*" AND TYPE:"spu:protocolo" AND ALL:"*' + filter + '*"')
        getProtocolosWithDepthFromRoot(protocoloRaizNode, resultado, pageSize)
        // Tirar do array a posição 0, ou seja, o próprio protocoloRaizNode
    }

    return resultado.vetor
}

model.protocolos = listarDestinos(protocoloRaizId, tipoProcessoId, filter, offset, pageSize)

function getNode(nodeId) {
	var node = search.luceneSearch('ID:"workspace://SpacesStore/' + nodeId + '"');
	if (node != undefined && node.length > 0) {
		node = node[0];
	}
	return node;
}

