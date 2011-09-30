{
<#compress>
	"Anexos":[{
		<#list anexos as anexo>
			"${anexo.nodeRef}":{
	            "noderef":"${anexo.nodeRef}",
	            "nome":"${anexo.name}", 
	            "processo": {
                    <#assign processo = anexo.parent>
                    <#include "../../snippet/snippet_processo.get.json.ftl" />
                }
            }
			<#if anexo_index+1 < anexos?size>,</#if>
		</#list>
	}]
</#compress>
}
