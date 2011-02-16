"cpfCnpj":"${processo.properties['spu:manifestante.Cpf']}"
,"nome":"${processo.properties['spu:manifestante.Nome']}" 
<#if processo.properties['spu:manifestante.Sexo']?exists>
,"sexo":"${processo.properties['spu:manifestante.Sexo']}"
<#else>
,"sexo":""
</#if>
<#if processo.properties['spu:manifestante.Logradouro']?exists>
,"logradouro":"${processo.properties['spu:manifestante.Logradouro']}"
<#else>
,"logradouro":""
</#if>
<#if processo.properties['spu:manifestante.Numero']?exists>
,"numero":"${processo.properties['spu:manifestante.Numero']}"
<#else>
,"numero":""
</#if>
<#if processo.properties['spu:manifestante.Cep']?exists>
,"cep":"${processo.properties['spu:manifestante.Cep']}"
<#else>
,"cep":""
</#if>
<#assign opcao = processo.properties['spu:manifestante.Bairro']>
,"bairro":[{<#include "snippet_categoria.get.json.ftl" />}]
<#if processo.properties['spu:manifestante.Cidade']?exists>
,"cidade":"${processo.properties['spu:manifestante.Cidade']}"
<#else>
,"cidade":""
</#if>
<#if processo.properties['spu:manifestante.Uf']?exists>
,"uf":"${processo.properties['spu:manifestante.Uf']}"
<#else>
,"uf":""
</#if>
<#if processo.properties['spu:manifestante.FoneResidencial']?exists>
,"telefone":"${processo.properties['spu:manifestante.FoneResidencial']}"
<#else>
,"telefone":""
</#if>
<#if processo.properties['spu:manifestante.FoneComercial']?exists>
,"telefoneComercial":"${processo.properties['spu:manifestante.FoneComercial']}"
<#else>
,"telefoneComercial":""
</#if>
<#if processo.properties['spu:manifestante.Celular']?exists>
,"celular":"${processo.properties['spu:manifestante.Celular']}"
<#else>
,"celular":""
</#if>
<#if processo.properties['spu:manifestante.Obs']?exists>
,"observacao":"${processo.properties['spu:manifestante.Obs']}"
<#else>
,"observacao":""
</#if>
