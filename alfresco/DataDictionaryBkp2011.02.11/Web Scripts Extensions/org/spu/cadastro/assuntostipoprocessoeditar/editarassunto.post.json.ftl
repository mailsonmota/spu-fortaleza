{
<#compress>
"Assunto":[{
	"${assunto.properties.name}":[{
		"noderef":"${assunto.nodeRef}"
		,"nome":"${assunto.properties.name}"
        <#if assunto.properties.description?exists>
		,"descricao":"${assunto.properties.description}"
        <#else>
        ,"descricao":"${assunto.properties.name}"
        </#if>
		<#if assunto.properties['spu:assunto.Corpo']?exists> 
		,"corpo":"${assunto.properties['spu:assunto.Corpo']}"
		<#else>
		,"corpo":""
		</#if>
		<#if assunto.properties['spu:assunto.NotificarNaAbertura']?exists> 
		,"notificarNaAbertura":"${assunto.properties['spu:assunto.NotificarNaAbertura']?string(1, 0)}"
		<#else>
		,"notificarNaAbertura":""
		</#if>
		<#if assunto.parent.nodeRef?exists> 
		,"tipoProcesso":[{
			"noderef":"${assunto.parent.nodeRef}",
			"title":"${assunto.parent.properties.title}"
		}]
		<#else>
		,"tipoProcesso":[{
			"noderef":"",
			"title":""
		}]
		</#if>
	}]
}]
</#compress>
}
