{
"Manifestantes":[{
<#compress>
<#list manifestantes as manifestante>
    "${manifestante['cpfCnpj']}":[{
	    <#include "../../snippet/snippet_manifestante.get.json.ftl" />
    }]<#if manifestante_index+1 < manifestantes?size>,</#if>
</#list>
</#compress>
}]
}
