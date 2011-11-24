opcao = search.findNode(url.templateArgs['noderef'])
parent = opcao.parent
parentRaiz = parent.parent

model.opcao = opcao
model.parent = parent
model.parentRaiz = parentRaiz

