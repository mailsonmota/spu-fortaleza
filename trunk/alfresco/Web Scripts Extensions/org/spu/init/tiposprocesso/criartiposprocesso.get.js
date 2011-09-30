<import resource="/Company Home/Data Dictionary/Scripts/SPU/criartiposprocesso.js">
result = criarTiposProcessoIniciais();
model.mensagem = (result) ? "Tipos de Processo criadas com sucesso." : "Ocorreu um erro ao criar os Tipos de Processo.";
