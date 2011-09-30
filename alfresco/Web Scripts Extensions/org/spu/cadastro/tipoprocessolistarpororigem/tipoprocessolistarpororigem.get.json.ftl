{
<#compress>
"Tipos de Processo":[{
	<#list tiposProcesso as rootnode>
		"${rootnode.name}":[{
			"noderef":"${rootnode.nodeRef}",
			"nome":"${rootnode.name}"
			<#if rootnode.properties.title != "">
			,"titulo":"${rootnode.properties.title}"
			<#else>
			,"titulo":"${rootnode.name}"
			</#if>
		}]<#if rootnode_index+1 < tiposProcesso?size>,</#if>
	</#list>
}]
</#compress>
}
