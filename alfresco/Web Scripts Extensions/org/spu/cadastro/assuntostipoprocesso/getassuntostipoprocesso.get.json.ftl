{
"assuntos":[
<#compress>
	<#list assuntos as assunto>
		{
			"noderef":"${assunto.nodeRef}",
			"nome":"${assunto.properties.name}", 
            <#if assunto.properties.description?exists>
			"descricao":"${assunto.properties.description}"
            <#else>
            "descricao":"${assunto.properties.title}"
            </#if>
		}<#if assunto_index+1 < assuntos?size>,</#if>
	</#list>
</#compress>
]
}
