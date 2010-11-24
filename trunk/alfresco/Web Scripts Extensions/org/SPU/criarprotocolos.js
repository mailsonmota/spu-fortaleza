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
    var pastaRaiz = getOrCreateFolder(companyhome, 'SPU')

    var pmf = getOrCreateFolder(pastaRaiz, 'PMF')
    
    var grupoSpu = getOrCreateGroup(null, 'SPU')
    var grupoPmf = getOrCreateGroup(grupoSpu, 'SPU_PMF')

    /* SAM */
    var grupoSam = getOrCreateGroup(grupoPmf, 'SPU_SAM');
    getOrCreateProtocolo(pmf, 'SPU_SAM', 'SAM')

    /* SMS */
    var grupoSms = getOrCreateGroup(grupoPmf, 'SPU_SMS');
    getOrCreateProtocolo(pmf, 'SPU_SMS', 'SMS')

    /* SME */
    var grupoSme = getOrCreateGroup(grupoPmf, 'SPU_SME');
    getOrCreateProtocolo(pmf, 'SPU_SME', 'SME')    

	return true
}
