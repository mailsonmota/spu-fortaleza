/**
 * getChildCategoryByName
 * busca pela categoria dentro de um array de categories
**/
function getChildCategoryByName(categories, categoryName) {
	for (var i = 0; i < categories.length; i++) {
		if (categories[i].name == categoryName) {
			return categories[i]
		}
	}
	return false;
}

/**
 * getOrCreateSubCategory
 * busca por uma categoria entre os filhos imediatos de uma category informada. Caso não encontre, 
 * a cria como filho da category informada.
**/
function getOrCreateSubCategory(categoryFather, categoryName) {
	var existingCategory = getChildCategoryByName(categoryFather.immediateSubCategories, categoryName);
	var desiredCategory
	if (!existingCategory) {
		desiredCategory = categoryFather.createSubCategory(categoryName)
	} else {
		desiredCategory = existingCategory
	}
	return desiredCategory
}

/**
 * criarTodasAsCategories
 * Cria as categories basicas necessarias para o SPU
**/
function criarTodasAsCategories() {
	/* SPU */
	var spu
	var busca
	busca = getChildCategoryByName(classification.getRootCategories("cm:generalclassifiable"), "SPU");
	spu = (!busca) ? classification.createRootCategory("cm:generalclassifiable", "SPU") : busca;

	/* Bairros */
	var bairros = getOrCreateSubCategory(spu, "Bairros");
	getOrCreateSubCategory(bairros, "Meireles")
	getOrCreateSubCategory(bairros, "Aldeota")
	getOrCreateSubCategory(bairros, "Centro")

	/* Tramitacao */
	var tramitacao = getOrCreateSubCategory(spu, "Tramitacao")
	getOrCreateSubCategory(tramitacao, "Paralelo")
	getOrCreateSubCategory(tramitacao, "Série")

	/* Abrangencia */
	var abrangencia = getOrCreateSubCategory(spu, "Abrangencia")
	getOrCreateSubCategory(abrangencia, "Externa")
	getOrCreateSubCategory(abrangencia, "Interna")

	/* Prioridade */
	var prioridade = getOrCreateSubCategory(spu, "Prioridade")
	getOrCreateSubCategory(prioridade, "Ordinário (Normal)")
	getOrCreateSubCategory(prioridade, "Prioritário")
	getOrCreateSubCategory(prioridade, "Urgente")

	return true
}
