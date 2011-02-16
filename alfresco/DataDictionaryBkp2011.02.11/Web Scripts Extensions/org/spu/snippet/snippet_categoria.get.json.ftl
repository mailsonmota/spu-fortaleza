<#if opcao!="">
"noderef":"${opcao.nodeRef}", 
"nome":"${opcao.name}",
<#if opcao.properties.description?exists>
"descricao":"${opcao.properties.description}"
<#else>
"descricao":"${opcao.name}"
</#if>
<#else>
"noderef":""
,"nome":""
,"descricao":""
</#if>
