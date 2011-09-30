<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/tramitacao.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/tramitacaoExterna.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/caixasentrada.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/caixassaida.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/movimentacoes.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/arquivamento.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/abrirprocesso.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/despacho.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/consulta.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/copias.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/processo/tramitacaoParalela.js">
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

function getOpcoesStatusProcesso() {
	var status = search.luceneSearch('PATH:"/cm:generalclassifiable/cm:SPU/cm:Status/*"');
	return status;
}

function getProcesso(nodeId) {
	return getNode(nodeId);
}

function getStatusTramitando() {
	var statusTramitando = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Status//cm:Tramitando\"');
	return statusTramitando[0];
}

function getStatusArquivado() {
	var statusArquivado = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Status//cm:Arquivado\"');
	return statusArquivado[0];
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

        /* Executa o Workflow de Recebimento */
        workflow = actions.create("start-workflow");
        workflow.parameters.workflowName = "jbpm$spu:receberProcesso"; 
        workflow.execute(processo);

		processos.push(processo)
	}
	return processos;
}

function getSiglaFromTipoProcessoId(tipoProcessoId) {
    var tipoProcessoNode = getNode(tipoProcessoId)
    return tipoProcessoNode.properties['spu:tipoprocesso.Letra'];
}

function getDefaultSortProcessos() {
	var sort1 = {column: "@{extension.spu}processo.Numero", ascending: true}
	return sort1
}
