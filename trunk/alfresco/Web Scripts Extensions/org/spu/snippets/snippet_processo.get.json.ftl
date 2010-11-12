"${processo.name}":[{
	"noderef":"${processo.nodeRef}",
	"nome":"${processo.name}"
	<#if processo.properties['spu:processo.Data']?exists>
	,"data":"${processo.properties['spu:processo.Data']?string('dd/MM/yyyy')}"
	<#else>
	,"data":""
	</#if>
	,"manifestante":[{
		<#if processo.properties['spu:processo.ManifestanteNome']?exists>
		"nome":"${processo.properties['spu:processo.ManifestanteNome']}"
		,"cpfCnpj":"${processo.properties['spu:processo.ManifestanteCpf']}"
		<#assign opcao = processo.properties['spu:processo.ManifestanteBairro']>
		,"bairro":[{<#include "snippet_categoria.get.json.ftl" />}]
		</#if>
	}]
	<#assign opcao = processo.properties['spu:processo.ManifestanteTipo']>
	,"tipoManifestante":[{<#include "snippet_categoria.get.json.ftl" />}]
	<#assign opcao = processo.properties['spu:processo.Prioridade']>
	,"prioridade":[{<#include "snippet_categoria.get.json.ftl" />}]
	<#if processo.properties['spu:processo.Status']?exists>
		<#assign opcao = processo.properties['spu:processo.Status']>
	<#else>
		<#assign opcao = "">
	</#if>
	,"status":[{<#include "snippet_categoria.get.json.ftl" />}]
	<#if processo.properties['spu:processo.NumeroOrigem']?exists>
	,"numeroOrigem":"${processo.properties['spu:processo.NumeroOrigem']}"
	<#else>
	,"numeroOrigem":""
	</#if>
	<#if processo.properties['spu:processo.Observacao']?exists>
	,"observacao":"${processo.properties['spu:processo.Observacao']}"
	<#else>
	,"observacao":""
	</#if>
	<#if processo.properties['spu:processo.Corpo']?exists>
	,"corpo":"${processo.properties['spu:processo.Corpo']}"
	<#else>
	,"corpo":""
	</#if>
	<#if processo.properties['spu:processo.DataPrazo']?exists>
	,"dataPrazo":"${processo.properties['spu:processo.DataPrazo']?string('dd/MM/yyyy')}"
	<#else>
	,"dataPrazo":""
	</#if>
	,"localAtual":[{
		<#if processo.properties['spu:processo.Destino']?exists>
			<#assign protocolo = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + processo.properties['spu:processo.Destino']][0]>
		<#else>
			<#assign protocolo = "">
		</#if>
		<#include "snippet_protocolo.get.json.ftl" />
	}]	
	,"proprietario":[{
		<#if processo.assocs['spu:processo.Proprietario']?exists>
			<#assign protocolo = processo.assocs['spu:processo.Proprietario'][0]>
		<#else>
			<#assign protocolo = "">
		</#if>
		<#include "snippet_protocolo.get.json.ftl" />
	}]	
	,"assunto":[{
		<#if processo.assocs['spu:processo.Assunto']?exists>
		"noderef":"${processo.assocs['spu:processo.Assunto'][0].nodeRef}", 
		"nome":"${processo.assocs['spu:processo.Assunto'][0].name}"
		</#if>
	}]
	,"tipoProcesso":[{
		<#if processo.assocs['spu:processo.Assunto']?exists>
		"noderef":"${processo.assocs['spu:processo.Assunto'][0].parent.nodeRef}", 
		"nome":"${processo.assocs['spu:processo.Assunto'][0].parent.name}"
		</#if>
	}]
	<#if movimentacoes?exists>
	,"movimentacoes":[{
		<#list movimentacoes as movimentacao>		
		"${movimentacao['data']?string('dd/MM/yyyy HH:MM:ss')}":[{
			"data":"${movimentacao['data']?string('dd/MM/yyyy HH:MM:ss')}"
			<#assign protocolo=movimentacao['de']>
			,"de":[{<#include "snippet_protocolo.get.json.ftl" />}]
			<#assign protocolo=movimentacao['para']>
			,"para":[{<#include "snippet_protocolo.get.json.ftl" />}]
			,"despacho":"${movimentacao['despacho']}"
			<#if movimentacao['prazo']?is_date>
				<#assign prazo = movimentacao['prazo']?string('dd/MM/yyyy')>
			<#else>
				<#assign prazo = "">
			</#if>
			,"prazo":"${prazo}"
			<#assign opcao=movimentacao['prioridade']>
			,"prioridade":[{<#include "snippet_categoria.get.json.ftl" />}]
		}]<#if movimentacao_has_next>,</#if>
		</#list>
	}]
	</#if>
}]