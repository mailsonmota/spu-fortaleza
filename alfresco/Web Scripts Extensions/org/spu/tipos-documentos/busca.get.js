model.opcoes = search.luceneSearch(
    'workspace://SpacesStore',
    'PATH:"/cm:generalclassifiable/cm:SPU/cm:Tipo_x0020_de_x0020_Documento//*"'
    + 'AND ALL:"' + url.templateArgs['termo'] + '*"'
)

