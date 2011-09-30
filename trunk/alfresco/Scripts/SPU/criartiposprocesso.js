<import resource="/Company Home/Data Dictionary/Scripts/SPU/base.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/tipoprocesso.js">
/**
 * Criar Tipos de Processo
 * 
 * Métodos para inicializar os tipos de processo do SPU.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 22/11/2010
*/
function criarTiposProcessoIniciais() {
    var dataDictionary = companyhome.childByNamePath('Data Dictionary')
    
    /* Pasta "Tipos de Processo" */
    var pastaRaiz = getOrCreateFolder(dataDictionary, 'Tipos de Processo')

    /* Aposentadoria */
    var aposentadoria = getOrCreateTipoProcesso(pastaRaiz, 'Aposentadoria', 'Aposentadoria', 'AP')
        getOrCreateAssunto(aposentadoria, "Aposentadoria por Invalidez")
        getOrCreateAssunto(aposentadoria, "Aposentadoria Proporcional por Idade")
        getOrCreateAssunto(aposentadoria, "Aposentadoria Compulsoria", "Aposentadoria Compulsória")

    /* Assistência Social */
    var assistenciaSocial = getOrCreateTipoProcesso(pastaRaiz, 'Assistencia Social', 'Assistência Social', 'AS')
        getOrCreateAssunto(assistenciaSocial, "Atestado de Funcionamento")
        getOrCreateAssunto(assistenciaSocial, "Cadastro Unico", "Cadastro Único")
        getOrCreateAssunto(assistenciaSocial, "Cadastro de Violacao de Direitos de Crianca e Adolescente", "Cadastro de Violacao de Direitos de Criança e Adolescente")
        getOrCreateAssunto(assistenciaSocial, "Morador de Rua")

	return true
}

/**
 * Criar Tipos de Processo
 * 
 * Métodos para criar os tipos de processo do SPU.
 * 
 * @package Sistema de Protocolo Único
 * @author Prefeitura Municipal de Fortaleza <http://www.fortaleza.ce.gov.br>
 * @since 22/11/2010
*/
function criarTiposProcessoAssuntoFormulario(tipoProcesso, letra, assunto, formulario, conteudo, envolvidos, tramitacao, abrangencia) {

    var result = criarTiposProcessoAssunto(tipoProcesso, letra, assunto, envolvidos, tramitacao, abrangencia)
    var props = new Array()
    props['cm:name'] = formulario
    result = getOrCreateNode(result, 'spu:formulario', props)
    result.content = conteudo
    result.save()
    return result.parent.parent
}

function criarTiposProcessoAssunto(tipoProcesso, letra, assunto, envolvidos, tramitacao, abrangencia) {
    var dataDictionary = companyhome.childByNamePath('Data Dictionary')
    
    /* Pasta "Tipos de Processo" */
    var pastaRaiz = getOrCreateFolder(dataDictionary, 'Tipos de Processo')
    
    var processoName = tipoProcesso.replace("/", "-").replace(":", "-") 
    var result = getOrCreateTipoProcesso(pastaRaiz, processoName, tipoProcesso, letra)

    var env = new Array()
    for(var i = 0; i < envolvidos.length(); i++){
        //TYPE:"cm:category" AND (@cm\:description:"Pessoa Juridica (Sem Ser Orgao)" OR @cm\:name:"Pessoa Juridica (Sem Ser Orgao)")
        var query = 'TYPE:"cm:category" AND (@cm\\:description:"' + envolvidos.get(i) + '" OR @cm\\:name:"' + envolvidos.get(i) + '")'
        env.push(search.luceneSearch(query)[0])
    }
    var tramit = search.luceneSearch('TYPE:"cm:category" AND (@cm\\:description:"' + tramitacao + '" OR @cm\\:name:"' + tramitacao + '")')[0]
    var abran = search.luceneSearch('TYPE:"cm:category" AND (@cm\\:description:"' + abrangencia + '" OR @cm\\:name:"' + abrangencia + '")')[0]
    result.properties['spu:tipoprocesso.TipoManifestante'] = env
    result.properties['spu:tipoprocesso.Tramitacao'] = tramit
    result.properties['spu:tipoprocesso.Abrangencia'] = abran
    result.properties['spu:tipoprocesso.Simples'] = false
    result.save()
    var assuntoName = assunto.replaceAll("/", "-").replace(":", "-")
    result = getOrCreateAssunto(result, assuntoName, assunto)
    return result
}

function getDefaultTiposManifestante() {
	var tiposManifestante = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Tipo_x0020_de_x0020_Manifestante//*\"');
	return tiposManifestante;
}
