resultado_busca_termo = search.luceneSearch(
    'workspace://SpacesStore',
    'PATH:"/cm:generalclassifiable/cm:SPU/cm:Tipo_x0020_de_x0020_Documento//*"'
    + 'AND @cm\\:name:"' + url.templateArgs['termo'] + '*"'
);

/*nodes_to_exclude = search.luceneSearch('workspace://SpacesStore',
    'PATH:"/cm:generalclassifiable/cm:SPU/cm:Tipo_x0020_de_x0020_Documento/*"'
);

nodes_to_exclude_children = nodes_to_exclude.children // como pegar children?

throw nodes_to_exclude_children //

for (var i in nodes_to_exclude_children) {
    nodes_to_exclude.push(nodes_to_exclude_children[i])
}*/

//throw nodes_to_exclude[1].name

model.opcoes = resultado_busca_termo
