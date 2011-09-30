"${processo.nodeRef}":[{
	"noderef":"${processo.nodeRef}",
	"nome":"${processo.name}"
	<#if processo.properties['spu:processo.Data']?exists>
	,"data":"${processo.properties['spu:processo.Data']?string('dd/MM/yyyy')}"
	<#else>
	,"data":""
	</#if>
	,"manifestante":{
        <#include "snippet_processoManifestante.get.json.ftl" />
	}
	<#assign opcao = processo.properties['spu:manifestante.Tipo']>
	,"tipoManifestante":{<#include "snippet_categoria.get.json.ftl" />}
	<#assign opcao = processo.properties['spu:processo.Prioridade']>
	,"prioridade":{<#include "snippet_categoria.get.json.ftl" />}
	<#if processo.properties['spu:processo.Status']?exists>
		<#assign opcao = processo.properties['spu:processo.Status']>
	<#else>
		<#assign opcao = "">
	</#if>
	,"status":{<#include "snippet_categoria.get.json.ftl" />}
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
	,"corpo":<#escape x as jsonUtils.encodeJSONString(x)>"${processo.properties['spu:processo.Corpo']}"</#escape>
	<#else>
	,"corpo":""
	</#if>
	<#if processo.properties['spu:processo.DataPrazo']?exists>
	,"dataPrazo":"${processo.properties['spu:processo.DataPrazo']?string('dd/MM/yyyy')}"
	<#else>
	,"dataPrazo":""
	</#if>
	,"localAtual":{
		<#if processo.properties['spu:processo.Destino']?exists>
			<#assign protocolo = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + processo.properties['spu:processo.Destino']][0]>
		<#else>
			<#assign protocolo = "">
		</#if>
		<#include "snippet_protocolo.get.json.ftl" />
	}
	,"proprietario":{
		<#if processo.properties['spu:processo.Proprietario']?exists>
			<#assign protocolos = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + processo.properties['spu:processo.Proprietario']]>
		    <#if protocolos[0]?exists>
		        <#assign protocolo = protocolos[0]>
		    <#else>
		        <#assign protocolo = "">
		    </#if>
	    <#else>
		    <#assign protocolo = "">
		</#if>
		<#include "snippet_protocolo.get.json.ftl" />
	}
	,"assunto":{
		<#if processo.properties['spu:processo.Assunto']?exists>
        <#assign assuntos = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + processo.properties['spu:processo.Assunto']]>
        <#if assuntos[0]?exists>
            <#assign assunto = assuntos[0]>
		    "noderef":"${assunto.nodeRef}", 
		    "nome":"${assunto.name}"
        <#else>
            "noderef":"", 
		    "nome":""
		</#if>
        <#else>
            "noderef":"", 
		    "nome":""
        </#if>
	}
    ,"tipoProcesso":{
        <#if assunto?exists>
		"noderef":"${assunto.parent.nodeRef}", 
		"nome":"${assunto.parent.name}"
		</#if>
	}
    ,"arquivamento":{
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
    }
    ,"ultimaMovimentacao":[{
        "${processo.properties.modified?string('dd/MM/yyyy HH:mm:ss')}":{
            "data":"${processo.properties.modified?string('dd/MM/yyyy')}"
            ,"hora":"${processo.properties.modified?string('HH:mm')}"
            <#if processo.properties['spu:processo.Despacho']?exists>
            ,"despacho":"${processo.properties['spu:processo.Despacho']}"
            <#else>
            ,"despacho":""
            </#if>
            <#if processo.properties['spu:processo.DataPrazo']?exists && processo.properties['spu:processo.DataPrazo']?is_date>
                <#assign prazo = processo.properties['spu:processo.DataPrazo']?string('dd/MM/yyyy')>
            <#else>
                <#assign prazo = "">
            </#if>
            ,"prazo":"${prazo}"
            <#assign opcao = processo.properties['spu:processo.Prioridade']>
            ,"prioridade":{<#include "snippet_categoria.get.json.ftl" />}
        }
    }]
    <#if processo.properties['spu:folhas.Quantidade']?exists>
    ,"folhas" : {
        "quantidade" : "${processo.properties['spu:folhas.Quantidade']}"
        <#if processo.properties['spu:folhas.Volumes']?exists>
        ,"volumes" : ${processo.properties['spu:folhas.Volumes']}
        </#if>
    }
    </#if>
}]
