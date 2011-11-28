var processo = search.findNode('workspace://SpacesStore/' + url.templateArgs['uuid']);

var incorporados = search.luceneSearch(
    'PRIMARYPARENT:"' + processo.nodeRef + '" AND TYPE:"spu:processo"'
);

model.processos = incorporados;

