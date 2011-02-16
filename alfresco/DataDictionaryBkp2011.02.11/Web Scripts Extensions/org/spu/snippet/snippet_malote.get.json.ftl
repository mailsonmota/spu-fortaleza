"${malote.name}":[{
	"noderef":"${malote.nodeRef}",
	"nome":"${malote.name}"
	<#if malote.properties['spu:malote.Despacho']?exists>
		,"despacho":"${malote.properties['spu:malote.Despacho']}"
	<#else>
		,"despacho":""
	</#if>
	<#if malote.properties['spu:malote.DataEnvio']?exists>
		,"dataEnvio":"${malote.properties['spu:malote.DataEnvio']?string('dd/MM/yyyy')}"
	<#else>
		,"dataEnvio":""
	</#if>
	<#if malote.properties['spu:malote.DataRecebimento']?exists>
		,"dataRecebimento":"${malote.properties['spu:malote.DataRecebimento']?string('dd/MM/yyyy')}"
	<#else>
		,"dataRecebimento":""
	</#if>
	<#assign opcao = malote.properties['spu:malote.TipoMalote']>
	,"tipoMalote":[{<#include "snippet_categoria.get.json.ftl" />}]
}]
