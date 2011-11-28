resultado_busca_termo = search.luceneSearch(
    'workspace://SpacesStore',
    'PATH:"/cm:generalclassifiable/cm:SPU/cm:Tipo_x0020_de_x0020_Documento//*//*//*"'
    + 'AND @cm\\:name:"' + url.templateArgs['termo'] + '*"'
);

model.opcoes = resultado_busca_termo
