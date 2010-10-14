<import resource="/Company Home/Data Dictionary/Scripts/SPU/criarcategories.js">
result = criarTodasAsCategories();
model.mensagem = (result) ? "Categorias criadas com sucesso." : "Ocorreu um erro ao criar as categories.";
