<import resource="/Company Home/Data Dictionary/Scripts/SPU/criarprotocolos.js">
result = criarProtocolos();
model.mensagem = (result) ? "Protocolos criados com sucesso." : "Ocorreu um erro ao criar os Protocolos.";
