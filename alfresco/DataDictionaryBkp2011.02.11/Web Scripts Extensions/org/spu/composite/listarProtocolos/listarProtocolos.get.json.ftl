{
    "sEcho": 0,
    "iTotalRecords": 1000,
    "iTotalDisplayRecords": 1000,
    "aaData": [
    <#list protocolos as protocolo>
        [
            <#if protocolo.properties['spu:protocolo.Path']?exists>
                "${protocolo.properties['spu:protocolo.Path']}"
            <#else>
                "${protocolo.name}"
            </#if>
            ,"<a href='/spu2/public/protocolos/editar/id/${protocolo.properties['sys:node-uuid']}'>Detalhes</a>"
        ]
        <#if protocolo_index+1 < protocolos?size>,</#if>
    </#list>
    ]
}
