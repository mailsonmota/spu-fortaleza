jQuery(document).ready(function() {
	
	/* Folhas e Volumes */
	var html_adicionar_volume = '<a href="#" class="adicionar-volume">Adicionar Volume</a>';
	var html_volume_input_list = '<dl>';
	html_volume_input_list += '<dt>Nome:</dt><dd><input type="text" name="folhas_volumes_nome[]" /></dd>';
	html_volume_input_list += '<dt>Início:</dt><dd><input type="text" name="folhas_volumes_inicio[]" /></dd>';
	html_volume_input_list += '<dt>Fim:</dt><dd><input type="text" name="folhas_volumes_fim[]" /></dd>';
    html_volume_input_list += '<dt>Descrição:</dt><dd><input type="text" name="folhas_volumes_descricao[]" /></dd>';
	html_volume_input_list += '</dl>';
	
	var html_volume_remover = '<a href="#" class="remover-volume">Remover este volume</a>'
	
	var html_volume = '<dt>Volume:</dt><dd>' + html_volume_input_list + html_volume_remover + '</dd>';
	
	/* folhas_volume_quantidade
	 * folhas_volume_nome
	 * folhas_volume_descricao */
	$('#folhas').parent().append(html_adicionar_volume);
	$('.adicionar-volume').click(function() {
		$(this).parent().parent().append(html_volume);
		$('.remover-volume').click(function() {
			$(this).parent().prev().remove();
			$(this).parent().remove();
			return false;
		});
		return false; // Impede o recarregamento da página
	});
	
	createUncheckedSelector();
	triggerEnterButton();
	
	$('.mascaraData').setMask('date');
	$('.mascaraHora').setMask('time');
	$('.mascaraCep').setMask('cep');
	$('.fone').setMask('phone');
	
	$.dpText = {
		TEXT_PREV_YEAR		:	'Ano anterior',
		TEXT_PREV_MONTH		:	'Mês anterior',
		TEXT_NEXT_YEAR		:	'Ano seguinte',
		TEXT_NEXT_MONTH		:	'Mês seguinte',
		TEXT_CLOSE			:	'Fechar',
		TEXT_CHOOSE_DATE	:	'Escolher'
	};
	$('.mascaraData').datePicker().trigger('change');
	
	//$('a[rel*=facebox]').facebox();
	
	tableRowCheckboxToggle();
	//tableRowRadioToggle();
	
	$.modal();
	$('textarea').elastic();
	
	// Todos as table com classe .grid dentre de #article que não sejam um .relatorio
	$('table:not(.no-datatable).grid').dataTable({
		"oLanguage": {
			"sUrl": baseUrl + "/js/plugins/dataTables/jquery.dataTables.pt-br.txt"
		},
		iDisplayLength: 50, 
		sPaginationType: "full_numbers", 
		"bLengthChange": false, 
		"bSort": false/*, 
		"fnDrawCallback": function() {
			updateTable($(this));
		}*/
	});
	$('.grid tfoot').hide();
	
	$('#article form').validate();
	$.extend($.validator.messages, {
        	required: "Este campo é requerido.",
        	email: "E-mail inválido."
        }
	);
	
	$('.autocomplete').val('Digite para buscar...').addClass('autocomplete-wait');
	$('.autocomplete').focus(function() {$(this).val('').removeClass('autocomplete-wait');});
	$('.autocomplete').blur(function() {
		if ($(this).val() == '') $(this).val('Digite para buscar...').addClass('autocomplete-wait');
	});
});

/*function updateTable(table) {
	$(table).find("tr").each(function(i,row) {
		hasRadio = ($(row).find(':radio').size() > 0) ? true : false;
		hasCheckedRadio = ($(row).find('input[type=radio]:checked').size() > 0) ? true : false;
		if($(row).hasClass(tableRowRadioCheckedClass) && hasRadio && !hasCheckedRadio) {
			$(row).removeClass(tableRowRadioCheckedClass);
		}
	});
}*/

function triggerEnterButton() {
	// Botão Default - Enter
    $('form input, form select').live('keypress', function (e) {
        if ($(this).parents('form').find('button[type=submit].default, input[type=submit].default').length <= 0) {
            return true;
        }
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $(this).parents('form').find('button[type=submit].default, input[type=submit].default').click();
            return false;
        } else {
            return true;
        }
    });
}

function checkedAll(id, checked) {
	var selector = (checked == true) ? ':unchecked' : ':checked';
	$('input[name=' + id + ']' + selector).click();
}

function createUncheckedSelector() {
	jQuery.extend(jQuery.expr[':'], {
		unchecked: function(element) {
			return ('checkbox' === element.type || 'radio' === element.type) && !element.checked;
		}
	});
}

function escolherModal(modalId, selectId) {
	var allVals = [];
	$('#' + modalId + ' input[type=checkbox]:checked, #' + modalId + ' input[type=radio]:checked').each(function() {
		option = new Array();
		option['id'] = $(this).val();
		option['title'] = $(this).parent().next().text();
		
		allVals.push(option);
    });
	
	$('#' + selectId + ' option').remove();
	
	for (var i in allVals) {
		$('#' + selectId).append('<option value="' + allVals[i]['id'] + '">' + allVals[i]['title'] + '</option>');
	}
	
	$('#' + selectId).val(allVals);
	
	$.modal.close('#' + modalId);
}

function addListAndInputItem(selectId, listId, itemId, itemLabel) {
    $("#" + listId + " li").remove();
    $("#" + listId).append('<li>' + itemLabel + ' (<a href=\"#\" onClick=\"removeListAndInputItem(this, \'' + selectId + '\', \'' + itemId + '\')\">Remover</a>)</li>');
    $("#" + selectId).val(itemId);
}

function removeListAndInputItem(listItem, selectId, itemValue) {
	$(listItem).parent().remove();
    $("#" + selectId).val('');
}

function removeListAndSelectItem(listItem, selectId, itemValue) {
    $(listItem).parent().remove();
    $("#" + selectId + " option[value='" + itemValue + "']").remove();
}