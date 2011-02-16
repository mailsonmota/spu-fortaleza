{
<#list arquivos as arquivo>
    "${arquivo.name}" : {
        "id" : "${arquivo.id}",
        "nome" : "${arquivo.name}",
        "mimetype" : "${arquivo.getMimetype()}",
        "download" : "${arquivo.downloadUrl}"
    }<#if arquivo_index+1 < arquivos?size>,</#if>
</#list>
}
