{
"Manifestante":{
<#compress>
    "${manifestante.properties['spu:manifestante.Cpf']}":{
        <#assign processo = manifestante>
	    <#include "../../snippet/snippet_processoManifestante.get.json.ftl" />
    }
</#compress>
}
}
