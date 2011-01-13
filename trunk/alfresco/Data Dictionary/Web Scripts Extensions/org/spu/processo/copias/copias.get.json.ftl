{
<#compress>
	"Copias":[{
	<#list copias as copia>
        "${copia.name}":[{        
            "noderef":"${copia.nodeRef}",
            "Processo":[{
                <#assign processo = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + copia.properties['spu:linkprocesso.Processo']][0]>
			    <#include "../../snippet/snippet_processo.get.json.ftl" />
            }]
        }]
		<#if copia_index+1 < copias?size>,</#if>
	</#list>
	}]
</#compress>
}
