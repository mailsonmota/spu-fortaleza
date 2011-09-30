<#if protocolo != "">
    <#if protocolo.properties.description?exists && protocolo.properties.description != "">
    <#assign descricao = protocolo.properties.description>
    <#else>
    <#assign descricao = protocolo.properties.title>
    </#if>
    "noderef":"${protocolo.nodeRef}"
    ,"nome":"${protocolo.name}"
    ,"descricao":"${descricao}"
	<#if protocolo.properties['spu:protocolo.Path']?exists>
    ,"path":"${protocolo.properties['spu:protocolo.Path']}"
    </#if>
<#else>
    "noderef":""
    ,"nome":""
    ,"parentId":""
    ,"descricao":""
    ,"path":""
</#if>
