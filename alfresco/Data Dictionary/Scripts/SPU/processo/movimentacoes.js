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
			para = (versoes[i].node.properties['spu:processo.Destino']) ? getNode(versoes[i].node.properties['spu:processo.Destino']) : ""
            despacho = (versoes[i].node.properties['spu:processo.Despacho']) ? versoes[i].node.properties['spu:processo.Despacho'] : ""
			prazo = (versoes[i].node.properties['spu:processo.DataPrazo']) ? versoes[i].node.properties['spu:processo.DataPrazo'] : ""
            prioridade = (versoes[i].node.properties['spu:processo.Prioridade']) ? versoes[i].node.properties['spu:processo.Prioridade'] : ""
			data = versoes[i].createdDate
            usuario = people.getPerson(versoes[i].creator)

            /**
             * Caso a Origem e o destino sejam o mesmo da movimentação anterior, indica que é só uma atualização de despacho.
             * Para melhorar a apresentação, a origem neste caso será igual ao Destino.
            */
            if (versoes[i].node.properties['spu:processo.Origem']) {
                if (versoes[i+1] && versoes[i].node.properties['spu:processo.Origem'] == versoes[i+1].node.properties['spu:processo.Origem'] && 
                    versoes[i+1] && versoes[i].node.properties['spu:processo.Destino'] == versoes[i+1].node.properties['spu:processo.Destino']) {
                    de = para
                } else {
                    de = getNode(versoes[i].node.properties['spu:processo.Origem'])
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
    } else if (isMovimentacaoArquivamento(versao, versaoAnterior)) {
        tipoMovimentacao = 'ARQUIVAMENTO';
    } else if (isMovimentacaoReabertura(versao, versaoAnterior)) {
        tipoMovimentacao = 'REABERTURA';
    } else if (isMovimentacaoDespacho(versao, versaoAnterior)) {
        tipoMovimentacao = 'DESPACHO';
    } 

    return tipoMovimentacao
}

function isMovimentacaoAbertura(versao, versaoAnterior) {
    return (!versaoAnterior) ? true : false
}

function isMovimentacaoEncaminhamento(versao, versaoAnterior) {
    var destinoVersao = versao.node.properties['spu:processo.Destino']
    var destinoVersaoAnterior = versaoAnterior.node.properties['spu:processo.Destino']
    return (versaoAnterior && destinoVersao != destinoVersaoAnterior) ? true : false
}

function isMovimentacaoCancelamentoEnvio(versao, versaoAnterior) {
    var destinoVersao = versao.node.properties['spu:processo.Destino']
    var destinoVersaoAnterior = versaoAnterior.node.properties['spu:processo.Destino']
    
    var origemVersao = versao.node.properties['spu:processo.Origem']
    var origemVersaoAnterior = versaoAnterior.node.properties['spu:processo.Origem']

    var emAnaliseVersao = versao.node.properties['spu:processo.EmAnalise']
    var emAnaliseVersaoAnterior = versaoAnterior.node.properties['spu:processo.EmAnalise']

    return (versaoAnterior && 
            destinoVersao == origemVersaoAnterior && 
            origemVersao == destinoVersaoAnterior && 
            emAnaliseVersaoAnterior == false && 
            emAnaliseVersao == false) ? true : false
}

function isMovimentacaoRecebimento(versao, versaoAnterior) {
    var destinoVersao = versao.node.properties['spu:processo.Destino']
    var destinoVersaoAnterior = versaoAnterior.node.properties['spu:processo.Destino']
    var emAnaliseVersao = versao.node.properties['spu:processo.EmAnalise']
    var emAnaliseVersaoAnterior = versaoAnterior.node.properties['spu:processo.EmAnalise']
    return (versaoAnterior && 
            destinoVersao == destinoVersaoAnterior && 
            emAnaliseVersao != emAnaliseVersaoAnterior) ? true : false
}

function isMovimentacaoArquivamento(versao, versaoAnterior) {
    var statusVersao = versao.node.properties['spu:processo.Status']
    var statusVersaoAnterior = versaoAnterior.node.properties['spu:processo.Status']
    var statusArquivado = getStatusArquivado();
    return (statusVersaoAnterior && 
            statusVersao != statusVersaoAnterior && 
            statusVersao.properties['sys:node-uuid'] == statusArquivado.properties['sys:node-uuid']) ? true : false
}

function isMovimentacaoReabertura(versao, versaoAnterior) {
    var statusVersao = versao.node.properties['spu:processo.Status']
    var statusVersaoAnterior = versaoAnterior.node.properties['spu:processo.Status']
    var statusArquivado = getStatusArquivado();
    var statusTramitando = getStatusTramitando();
    return (statusVersaoAnterior && 
            statusVersao != statusVersaoAnterior && 
            statusVersaoAnterior.properties['sys:node-uuid'] == statusArquivado.properties['sys:node-uuid'] &&
            statusVersao.properties['sys:node-uuid'] == statusTramitando.properties['sys:node-uuid']) ? true : false
}

function isMovimentacaoDespacho(versao, versaoAnterior) {
    var destinoVersao = versao.node.properties['spu:processo.Destino']
    var destinoVersaoAnterior = versaoAnterior.node.properties['spu:processo.Destino']
    var emAnaliseVersao = versao.node.properties['spu:processo.EmAnalise']
    var emAnaliseVersaoAnterior = versaoAnterior.node.properties['spu:processo.EmAnalise']
    var despachoVersao = versao.node.properties['spu:processo.Despacho']
    var despachoVersaoAnterior = versaoAnterior.node.properties['spu:processo.Despacho']
    return (versaoAnterior && 
            destinoVersao == destinoVersaoAnterior && 
            emAnaliseVersao == emAnaliseVersaoAnterior && 
            despachoVersao != despachoVersaoAnterior) ? true : false
}
