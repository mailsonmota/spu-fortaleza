/**
 * Arquivamento
 * 
 * Métodos para gerenciar os despachos do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 06/12/2010
*/
function comentarProcesso(nodeId, despacho) {
	var processo = getNode(nodeId)
    despacho = (!despacho) ? null : despacho

    /* Salvar */
    processo.properties['spu:processo.Despacho'] = despacho
	processo.save()

    return processo
}

function comentarProcessos(processosId, despacho) {	
    var processos = new Array()
	var processo
	processosId = eval('(' + processosId + ')');

    for (i=0; i < processosId.length; i++) {
		processo = comentarProcesso(processosId[i], despacho)
		processos.push(processo)
	}
	return processos
}
