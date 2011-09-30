<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/grupo.js">
/**
 * Criar Protocolos Iniciais
 * 
 * Métodos para inicializar os tipos de processo do SPU.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 22/11/2010
*/
function criarProtocolos() {
    var pastaRaiz = getOrCreateFolder(companyhome, 'SPU', 'SPU')
    var pmf = getOrCreateFolder(pastaRaiz, 'PMF', 'PMF')
    
    return true
}

/**
 * Criar Protocolos Iniciais
 * 
 * Método para gerar protocolos a partir de determinada estrutura.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 22/11/2010
*/
function gerarProtocolos(estrutura) {
	
	var rootGroup = getOrCreateGroup(null, 'SPU_PROTOCOLOS')
	
	var pastaRaiz = getOrCreateFolder(companyhome, 'SPU', 'SPU')
    
    var protocolosReceptores = new Array()
	for(var i = 0; i < estrutura.length(); i++){
		var protocolo = estrutura.get(i)
        var receptor = protocolo.get('recebe')
        var grupo = getOrCreateGroup(rootGroup, protocolo.get('grupo'))
        try {
        	var parent = (protocolo.get('parent').length() > 0)?pastaRaiz.childByNamePath(protocolo.get('parent')):pastaRaiz;
            var node = getOrCreateProtocolo(parent, grupo, protocolo.get('name'), protocolo.get('desc'))
            if (receptor.length() > 0) {
            	protocolosReceptores.push({"protocolo":protocolo,"node":node})
        	}
        } catch (e) {
        	throw e + ';' + protocolo.get('name') + ';' + protocolo.get('parent')
        }
        
	}

    for(var i = 0; i < protocolosReceptores.length; i++){
		var receptor = protocolosReceptores[i]
        var path = receptor.protocolo.get('recebe').replace('PMF/','')
        var query = 'TYPE:"spu:protocolo" AND @spu\\:protocolo.Path:"'+ path +'"'
        var receptorNode = search.luceneSearch("workspace://SpacesStore", query)[0]
        var protocolo = receptor.node
        protocolo.createAssociation(receptorNode, 'spu:protocolo.SetorProtocolo')
        protocolo.save()
	}

	return 'sucesso: '
}
