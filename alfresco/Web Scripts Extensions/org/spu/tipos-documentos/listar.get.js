var parent_noderef = args['parent-noderef']

if (parent_noderef != undefined) {
    parent_node = search.findNode(parent_noderef)

    model.opcoes = parent_node.children
} else {
    model.opcoes = search.luceneSearch(
        'workspace://SpacesStore',
        'PATH:"/cm:generalclassifiable/cm:SPU/cm:Tipo_x0020_de_x0020_Documento/*"'
    )
}

