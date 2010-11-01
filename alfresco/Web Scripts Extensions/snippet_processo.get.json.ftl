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
	<#if processo.properties['spu:processo.NumeroOrigem']?exists>
	,"numeroOrigem":"${processo.properties['spu:processo.NumeroOrigem']}"
	<#else>
	,"numeroOrigem":""
	</#if>
	<#if processo.properties['spu:processo.Corpo']?exists>
	,"corpo":"${processo.properties['spu:processo.Corpo']}"
	<#else>
	,"corpo":""
	</#if>
	<#if processo.properties['spu:processo.DataPrazo']?exists>
	,"dataPrazo":"${processo.properties['spu:processo.DataPrazo']?string('dd/MM/yyyy')}}"
	<#else>
	,"dataPrazo":""
	</#if>
	,"localAtual":<#include "snippet_localatualprocesso.get.json.ftl" />
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
			<#if movimentacao['de'] != "">
			,"de":"${movimentacao['de'].name}"
			<#else>
			,"de":"${movimentacao['de']}"
			</#if>
			<#if movimentacao['para'] != "">
			,"para":"${movimentacao['para'].name}"
			<#else>
			,"para":"${movimentacao['para']}"
			</#if>
			,"despacho":"${movimentacao['despacho']}"
			,"prazo":"${movimentacao['prazo']}"
			<#assign opcao=movimentacao['prioridade']>
			,"prioridade":[{<#include "snippet_categoria.get.json.ftl" />}]
		}]<#if movimentacao_has_next>,</#if>
		</#list>
	}]
	</#if>
}]
