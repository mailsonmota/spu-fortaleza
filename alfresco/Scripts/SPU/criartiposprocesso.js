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
    var aposentadoria = getOrCreateTipoProcesso(pastaRaiz, 'Aposentadoria')
        getOrCreateAssunto(aposentadoria, "Aposentadoria por Invalidez")
        getOrCreateAssunto(aposentadoria, "Aposentadoria Proporcional por Idade")
        getOrCreateAssunto(aposentadoria, "Aposentadoria Compulsoria", "Aposentadoria Compulsória")

    /* Assistência Social */
    var assistenciaSocial = getOrCreateTipoProcesso(pastaRaiz, 'Assistencia Social', 'Assistência Social')
        getOrCreateAssunto(assistenciaSocial, "Atestado de Funcionamento")
        getOrCreateAssunto(assistenciaSocial, "Cadastro Unico", "Cadastro Único")
        getOrCreateAssunto(assistenciaSocial, "Cadastro de Violacao de Direitos de Crianca e Adolescente", "Cadastro de Violacao de Direitos de Criança e Adolescente")
        getOrCreateAssunto(assistenciaSocial, "Morador de Rua")

	return true
}

function getDefaultTiposManifestante() {
	var tiposManifestante = search.luceneSearch('PATH:\"/cm:generalclassifiable//cm:SPU//cm:Tipo_x0020_de_x0020_Manifestante//*\"');
	return tiposManifestante;
}
