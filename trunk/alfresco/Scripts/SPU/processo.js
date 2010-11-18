<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/tramitacao.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/tramitacaoExterna.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/caixasentrada.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/caixassaida.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/movimentacoes.js">

/**
 * Processo
 * 
 * Métodos para gerenciar os Processos do Sistema de Protocolo Único.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 19/10/2010
*/
function getPrioridades() {
	var prioridades = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Prioridade//*\"');
	return prioridades;
}

function getProcesso(nodeId) {
	return getNode(nodeId);
}

function getStatusTramitando() {
	var statusTramitando = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Status//cm:Tramitando\"');
	return statusTramitando[0];
}

function getProcessos() {
	var processos = search.luceneSearch("workspace://SpacesStore", 'TYPE:"spu:Processo"');
	return processos;
}

function receberVarios(processosParaReceber) {
	var processos = new Array()
	var processo
	processosParaReceber = eval('(' + processosParaReceber + ')');
	for (i=0; i < processosParaReceber.length; i++) {
		processo = getProcesso(processosParaReceber[i])
		processo.properties['spu:processo.EmAnalise'] = true
		processo.save()
		processos.push(processo)
	}
	return processos;
}
