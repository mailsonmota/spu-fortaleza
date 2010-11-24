<import resource="/Company Home/Data Dictionary/Scripts/SPU/criarcategories.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/criartiposprocesso.js">
<import resource="/Company Home/Data Dictionary/Scripts/SPU/criarspacetemplates.js">

var mensagens = new Array

result = criarTodasAsCategories();
mensagens.push((result) ? "Categorias criadas com sucesso." : "Ocorreu um erro ao criar as categories.");

result = criarTiposProcessoIniciais();
mensagens.push((result) ? "Tipos de Processo criadas com sucesso." : "Ocorreu um erro ao criar os Tipos de Processo.");

result = criarSpaceTemplates();
mensagens.push((result) ? "Space Templates criados com sucesso." : "Ocorreu um erro ao criar os Space Templates.");

model.mensagens = mensagens
