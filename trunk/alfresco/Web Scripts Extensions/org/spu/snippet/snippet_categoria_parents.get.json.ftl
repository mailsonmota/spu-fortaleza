<#if opcao != "">
    "noderef":"${opcao.nodeRef}", 
    "nome":"${opcao.name}"

    <#if opcao.properties.description?exists>
        ,"descricao":"${opcao.properties.description}"
    <#else>
        ,"descricao":"${opcao.name}"
    </#if>

    <#if opcao.properties.description?exists>
        ,"descricao":"${opcao.properties.description}"
    <#else>
        ,"descricao":"${opcao.name}"
    </#if>

    <#if parent?exists>
        ,"parent":"${parent.name}"
    </#if>

    <#if parentRaiz?exists>
        ,"parentRaiz":"${parentRaiz.name}"
    </#if>
<#else>
    "noderef":""
    ,"nome":""
    ,"descricao":""
</#if>

