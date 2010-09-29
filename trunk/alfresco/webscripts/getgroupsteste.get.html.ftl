<pre>

resultado: ${resultado123.properties.authorityName}

<b>Listagem: objeto <i>grupos</i></b>
  --
<#list grupos as grupo>
  grupo: ${grupo}
  grupo.properties.authorityDisplayName: <b>${grupo.properties.authorityName}</b>
  grupo.nodeRef: ${grupo.nodeRef}
  grupo.parent.nodeRef: ${grupo.parent.nodeRef}
  
  parent.children {
  <#list grupo.parent.children as child>
    ${child.properties.authorityName}
  </#list>
  }

  children {
  <#list grupo.children as child>
    ${child.properties.authorityName!"nao"}
  </#list>
  }
  --
</#list>

--

${grupos[3].parent.nodeRef}
${grupos[3].parent.parent.nodeRef}

<#list grupos[2].parent?keys as key>
  ${key}
</#list>

--

resultadoGetParents

<#list resultadoGetParents as resultado>
  ${resultado}
</#list>

--

resultadoGetParents2 - print array

<#list resultadoGetParents2 as resultado>
  ${resultado[0]}
  ${resultado[1][0]}
  ${resultado[1][1][0]}
  ${resultado[1][1][1][0]}
</#list>

--

resultadoGetParents3 - luceneSearch

<#list resultadoGetParents3 as value>
  ${value.primaryParentAssoc}
  <#list value?keys as key>
    ${key}
  </#list>
</#list>

<!-- fazer macro -->
