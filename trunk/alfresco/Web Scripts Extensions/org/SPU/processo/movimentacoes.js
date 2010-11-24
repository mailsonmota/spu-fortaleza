/**
 * Movimentações
 * 
 * Métodos para gerenciar as movimentações do Sistema de Protocolo Único
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 16/11/2010
*/
function getMovimentacoes(nodeId) {
	var processo = getProcesso(nodeId)
	var versoes = processo.getVersionHistory()
	var movimentacoes = new Array()
	var movimentacao
	
    for (var i=0; i < versoes.length; i++) {
		tipoMovimentacao = getTipoMovimentacao(versoes[i], versoes[i+1])

        if (tipoMovimentacao) {
			para = (versoes[i].node.assocs['spu:processo.Destino'][0]) ? versoes[i].node.assocs['spu:processo.Destino'][0] : ""
            despacho = (versoes[i].node.properties['spu:processo.Despacho']) ? versoes[i].node.properties['spu:processo.Despacho'] : ""
			prazo = (versoes[i].node.properties['spu:processo.DataPrazo']) ? versoes[i].node.properties['spu:processo.DataPrazo'] : ""
            prioridade = (versoes[i].node.properties['spu:processo.Prioridade']) ? versoes[i].node.properties['spu:processo.Prioridade'] : ""
			data = versoes[i].createdDate
            usuario = people.getPerson(versoes[i].creator)

            /**
             * Caso a Origem e o destino sejam o mesmo da movimentação anterior, indica que é só uma atualização de despacho.
             * Para melhorar a apresentação, a origem neste caso será igual ao Destino.
            */
            if (versoes[i].node.assocs['spu:processo.Origem'][0]) {
                if (versoes[i+1] && versoes[i].node.assocs['spu:processo.Origem'][0] == versoes[i+1].node.assocs['spu:processo.Origem'][0]) {
                    de = para
                } else {
                    de = versoes[i].node.assocs['spu:processo.Origem'][0]
                }
            } else {
                de = ""
            }

			movimentacao = new Array()
			movimentacao['de'] = de
			movimentacao['para'] = para
			movimentacao['despacho'] = despacho
			movimentacao['prazo'] = prazo
			movimentacao['prioridade'] = prioridade
			movimentacao['data'] = data
            movimentacao['usuario'] = usuario
            movimentacao['tipo'] = tipoMovimentacao

			movimentacoes.push(movimentacao)
		}
	}

	return movimentacoes
}

function getTipoMovimentacao(versao, versaoAnterior) {
    var tipoMovimentacao = ''

    if (isMovimentacaoAbertura(versao, versaoAnterior)) {
        tipoMovimentacao = 'ABERTURA';
    } else if (isMovimentacaoCancelamentoEnvio(versao, versaoAnterior)) {
        tipoMovimentacao = 'CANCELAMENTOENVIO';
    } else if (isMovimentacaoEncaminhamento(versao, versaoAnterior)) {
        tipoMovimentacao = 'ENCAMINHAMENTO';
    } else if (isMovimentacaoRecebimento(versao, versaoAnterior)) {
        tipoMovimentacao = 'RECEBIMENTO';
    } else if (isMovimentacaoDespacho(versao, versaoAnterior)) {
        tipoMovimentacao = 'DESPACHO';
    } 

    return tipoMovimentacao
}

function isMovimentacaoAbertura(versao, versaoAnterior) {
    return (!versaoAnterior) ? true : false
}

function isMovimentacaoEncaminhamento(versao, versaoAnterior) {
    var destinoVersao = versao.node.assocs['spu:processo.Destino'][0]
    var destinoVersaoAnterior = versaoAnterior.node.assocs['spu:processo.Destino'][0]
    return (versaoAnterior && destinoVersao.properties['sys:node-uuid'] != destinoVersaoAnterior.properties['sys:node-uuid']) ? true : false
}

function isMovimentacaoCancelamentoEnvio(versao, versaoAnterior) {
    var destinoVersao = versao.node.assocs['spu:processo.Destino'][0]
    var destinoVersaoAnterior = versaoAnterior.node.assocs['spu:processo.Destino'][0]
    
    var origemVersao = versao.node.assocs['spu:processo.Origem'][0]
    var origemVersaoAnterior = versaoAnterior.node.assocs['spu:processo.Origem'][0]

    var emAnaliseVersao = versao.node.properties['spu:processo.EmAnalise']
    var emAnaliseVersaoAnterior = versaoAnterior.node.properties['spu:processo.EmAnalise']

    return (versaoAnterior && 
            destinoVersao.properties['sys:node-uuid'] == origemVersaoAnterior.properties['sys:node-uuid'] && 
            origemVersao.properties['sys:node-uuid'] == destinoVersaoAnterior.properties['sys:node-uuid'] && 
            emAnaliseVersaoAnterior == false && 
            emAnaliseVersao == true) ? true : false
}

function isMovimentacaoRecebimento(versao, versaoAnterior) {
    var destinoVersao = versao.node.assocs['spu:processo.Destino'][0]
    var destinoVersaoAnterior = versaoAnterior.node.assocs['spu:processo.Destino'][0]
    var emAnaliseVersao = versao.node.properties['spu:processo.EmAnalise']
    var emAnaliseVersaoAnterior = versaoAnterior.node.properties['spu:processo.EmAnalise']
    return (versaoAnterior && 
            destinoVersao.properties['sys:node-uuid'] == destinoVersaoAnterior.properties['sys:node-uuid'] && 
            emAnaliseVersao != emAnaliseVersaoAnterior) ? true : false
}

function isMovimentacaoDespacho(versao, versaoAnterior) {
    var destinoVersao = versao.node.assocs['spu:processo.Destino'][0]
    var destinoVersaoAnterior = versaoAnterior.node.assocs['spu:processo.Destino'][0]
    var emAnaliseVersao = versao.node.properties['spu:processo.EmAnalise']
    var emAnaliseVersaoAnterior = versaoAnterior.node.properties['spu:processo.EmAnalise']
    var despachoVersao = versao.node.properties['spu:processo.Despacho']
    var despachoVersaoAnterior = versaoAnterior.node.properties['spu:processo.Despacho']
    return (versaoAnterior && 
            destinoVersao.properties['sys:node-uuid'] == destinoVersaoAnterior.properties['sys:node-uuid'] && 
            emAnaliseVersao == emAnaliseVersaoAnterior && 
            despachoVersao != despachoVersaoAnterior) ? true : false
}
