<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/protocolo.js">
/**
 * Criar Space Templates
 * 
 * Métodos para inicializar os tipos de processo do SPU.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 22/11/2010
*/
function criarSpaceTemplates() {
    var spaceTemplates = companyhome.childByNamePath('Data Dictionary/Space Templates')
    
    /* Template "Protocolo" */
    var protocolo = getOrCreateProtocolo(spaceTemplates, null, 'Protocolo')

	return true
}
