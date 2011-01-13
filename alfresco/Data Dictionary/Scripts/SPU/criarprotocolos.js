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
	var grupo = getRootGroup('SPU')
	var pastaRaiz = getOrCreateFolder(companyhome, 'SPU', 'SPU')
	var aux = ''
	for(var i = 0; i < estrutura.length(); i++){
		var protocolo = estrutura.get(i)
		var parent = (protocolo.get('parent').length() > 0)?pastaRaiz.childByNamePath(protocolo.get('parent')):pastaRaiz;
		//var parent = (protocolo.get('parent').length() > 0)?protocolo.get('parent'):'premero';
		var node = getOrCreateProtocolo(parent, grupo, protocolo.get('name'), protocolo.get('desc'))
		aux += parent + '; '
	}

    	return 'sucesso'
}

/*function anyParentWithName(node, parentName){
	if(node.parent.properties['name'].equals(parentName)){
		return node.parent
	} else {
		anyParentWithName(node.parent, parentName)
	}
}*/
