{
	<#list assuntos as rootnode>
	"${rootnode.name}"<#if rootnode_index+1 < assuntos?size>,</#if>
	</#list>
}