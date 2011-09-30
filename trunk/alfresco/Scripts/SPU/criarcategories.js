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

	/* Status de Processo */
	var status = getOrCreateSubCategory(spu, "Status")
	getOrCreateSubCategory(status, "Tramitando")
	getOrCreateSubCategory(status, "Excluido")	
	getOrCreateSubCategory(status, "Incorporado")
   	var statusArquivado = getOrCreateSubCategory(status, "Arquivado")
    getOrCreateSubCategory(statusArquivado, "Concluído - Executado ou Deferido")
    getOrCreateSubCategory(statusArquivado, "Concluído - Não Executado ou Indeferido")
    getOrCreateSubCategory(statusArquivado, "Não Concluído")

	/* Tipos de Manifestante */
	var tipoManifestante = getOrCreateSubCategory(spu, "Tipo de Manifestante")
	getOrCreateSubCategory(tipoManifestante, "Órgão da PMF")
	getOrCreateSubCategory(tipoManifestante, "Outros (Estagiario. Terceirizado)")
	getOrCreateSubCategory(tipoManifestante, "Pessoa Fisica (Sem Ser Servidor)")
	getOrCreateSubCategory(tipoManifestante, "Pessoa Juridica (Sem Ser Orgao)")
	getOrCreateSubCategory(tipoManifestante, "Servidor Comissionado")
	getOrCreateSubCategory(tipoManifestante, "Servidor Efetivo")

	/* Tipos Malotes */
	var tipoMalote = getOrCreateSubCategory(spu, "Tipo de Malote");
	getOrCreateSubCategory(tipoMalote, "Pela Central")
	getOrCreateSubCategory(tipoMalote, "Envio Direto")
	return true
}
