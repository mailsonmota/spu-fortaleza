<#if processo.parent.type = '{extension.spu}caixaentrada'>
	<#if processo.parent.parent.type = '{extension.spu}protocolo'>
		<#assign protocolo = processo.parent.parent>
	</#if>
<#else>
	<#assign protocolo = "">
</#if>
<#if protocolo != "">
[{
	"noderef":"${protocolo.nodeRef}"
	,"nome":"${protocolo.name}"
	,"descricao":"${protocolo.properties.description}"
	<#if protocolo.properties['spu:protocolo.Orgao']?exists>
	,"orgao":"${protocolo.properties['spu:protocolo.Orgao']}"
	<#else>
	,"orgao":""
	</#if>
	<#if protocolo.properties['spu:protocolo.Lotacao']?exists>
	,"lotacao":"${protocolo.properties['spu:protocolo.Lotacao']}"
	<#else>
	,"lotacao":""
	</#if>
}]
<#else>
[{
	"noderef":""
	,"nome":""
	,"descricao":""
	,"orgao":""
	,"lotacao":""
}]
</#if>
