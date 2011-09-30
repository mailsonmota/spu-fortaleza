{
<#compress>
	"Copias":[{
	<#list copias as copia>
        <#assign resultado = companyhome.childrenByLuceneSearch["ID:workspace\\:\\/\\/SpacesStore\\/" + copia.properties['spu:linkprocesso.Processo']]>
        <#if resultado[0]?exists>
            <#assign processo = resultado[0]>
        "${copia.name}":{        
            "noderef":"${copia.nodeRef}",
            "Processo":{
                <#include "../../snippet/snippet_processo.get.json.ftl" />
                
            }
        }
		<#if copia_index+1 < copias?size>,</#if>
        </#if>
	</#list>
	}]
</#compress>
}

