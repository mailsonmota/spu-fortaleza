{
<#compress>
"Tipos de Processo":[{
	<#list tiposProcesso as rootnode>
		"${rootnode.name}":[{
			"noderef":"${rootnode.nodeRef}",
			"nome":"${rootnode.name}", 
			"titulo":"${rootnode.properties.title}"
		}]<#if rootnode_index+1 < tiposProcesso?size>,</#if>
	</#list>
}]
</#compress>
}
