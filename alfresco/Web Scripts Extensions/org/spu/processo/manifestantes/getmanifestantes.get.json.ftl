{
"Manifestantes":[{
<#compress>
<#list manifestantes as manifestante>
	<#include "../../snippet/snippet_manifestante.get.json.ftl" />
	<#if manifestante_index+1 < manifestantes?size>,</#if>
</#list>
</#compress>
}]
}
