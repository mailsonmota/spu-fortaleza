"${processo.name}":[{
	"noderef":"${processo.nodeRef}",
	"nome":"${processo.name}"
	<#if processo.properties['spu:processo.Data']?exists>
	,"data":"${processo.properties['spu:processo.Data']?string('dd/MM/yyyy')}"
	<#else>
	,"data":""
	</#if>
	,"manifestante":[{
        <#include "snippet_processoManifestante.get.json.ftl" />
	}]
	<#assign opcao = processo.properties['spu:manifestante.Tipo']>
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
		<#if processo.properties['spu:processo.Proprietario']?exists>
			<#assign protocolo = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + processo.properties['spu:processo.Proprietario']][0]>
		<#else>
			<#assign protocolo = "">
		</#if>
		<#include "snippet_protocolo.get.json.ftl" />
	}]	
	,"assunto":[{
		<#if processo.properties['spu:processo.Assunto']?exists>
        <#assign assunto = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + processo.properties['spu:processo.Assunto']][0]>
		"noderef":"${assunto.nodeRef}", 
		"nome":"${assunto.name}"
		</#if>
	}]
	,"tipoProcesso":[{
		<#if processo.properties['spu:processo.Assunto']?exists>
		"noderef":"${assunto.parent.nodeRef}", 
		"nome":"${assunto.parent.name}"
		</#if>
	}]
    ,"arquivamento":[{
        <#if processo.properties['spu:arquivamento.Status']?exists>
            <#assign opcao = processo.properties['spu:arquivamento.Status']>
        <#else>
            <#assign opcao = ''>
        </#if>        
	    "status":[{<#include "snippet_categoria.get.json.ftl" />}]
        <#if processo.properties['spu:arquivamento.Motivo']?exists>
        ,"motivo":"${processo.properties['spu:arquivamento.Motivo']}"
        <#else>
        ,"motivo":""
        </#if>
        <#if processo.properties['spu:arquivamento.Local']?exists>
        ,"local":"${processo.properties['spu:arquivamento.Local']}"
        <#else>
        ,"local":""
        </#if>
        <#if processo.properties['spu:arquivamento.Pasta']?exists>
        ,"pasta":"${processo.properties['spu:arquivamento.Pasta']}"
        <#else>
        ,"pasta":""
        </#if>
    }]
}]
