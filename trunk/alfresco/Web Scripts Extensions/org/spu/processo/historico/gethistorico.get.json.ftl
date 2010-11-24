{
<#compress>
	"Processo":[{
        "${processo.name}":[{
	        "noderef":"${processo.nodeRef}",
        	"nome":"${processo.name}"
		    <#if movimentacoes?exists>
	        ,"movimentacoes":[{
		        <#list movimentacoes as movimentacao>		
		        "${movimentacao['data']?string('dd/MM/yyyy HH:MM:ss')}":[{
			        "data":"${movimentacao['data']?string('dd/MM/yyyy')}"
                    ,"hora":"${movimentacao['data']?string('HH:MM')}"
			        <#assign protocolo=movimentacao['de']>
			        ,"de":[{<#include "../../snippet/snippet_protocolo.get.json.ftl" />}]
			        <#assign protocolo=movimentacao['para']>
			        ,"para":[{<#include "../../snippet/snippet_protocolo.get.json.ftl" />}]
			        ,"despacho":"${movimentacao['despacho']}"
			        <#if movimentacao['prazo']?is_date>
				        <#assign prazo = movimentacao['prazo']?string('dd/MM/yyyy')>
			        <#else>
				        <#assign prazo = "">
			        </#if>
			        ,"prazo":"${prazo}"
			        <#assign opcao=movimentacao['prioridade']>
			        ,"prioridade":[{<#include "../../snippet/snippet_categoria.get.json.ftl" />}]
                    <#assign usuario=movimentacao['usuario']>
                    ,"usuario":[{<#include "../../snippet/snippet_usuario.get.json.ftl" />}]
                    ,"tipo":"${movimentacao['tipo']}"
		        }]<#if movimentacao_has_next>,</#if>
		        </#list>
	        }]
	        </#if>
        }]
	}]
</#compress>
}
