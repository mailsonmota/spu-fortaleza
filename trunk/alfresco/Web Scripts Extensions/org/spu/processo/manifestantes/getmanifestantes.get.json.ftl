{
"Manifestantes":[{
<#compress>
<#list manifestantes as manifestante>
	<#include "../../snippets/snippet_manifestante.get.json.ftl" />
	<#if manifestante_index+1 < manifestantes?size>,</#if>
</#list>
</#compress>
}]
}
