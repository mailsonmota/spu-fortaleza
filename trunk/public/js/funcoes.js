jQuery(document).ready(function() {
	createUncheckedSelector();
	triggerEnterButton();
	
	$('.mascaraData').setMask('date');
	$('.mascaraHora').setMask('time');
	$('.mascaraCep').setMask('cep');
	$('.fone').setMask('phone');
	
	$("form").validate();

	$.extend($.validator.messages, {
        	required: "Este campo é requerido.",
        	email: "E-mail inválido."
        }
	);
	
	$.dpText = {
		TEXT_PREV_YEAR		:	'Ano anterior',
		TEXT_PREV_MONTH		:	'Mês anterior',
		TEXT_NEXT_YEAR		:	'Ano seguinte',
		TEXT_NEXT_MONTH		:	'Mês seguinte',
		TEXT_CLOSE			:	'Fechar',
		TEXT_CHOOSE_DATE	:	'Escolher'
	};
	$('.mascaraData').datePicker().val(new Date().asString()).trigger('change');
	
	//$('a[rel*=facebox]').facebox();
	
	tableRowCheckboxToggle();
	tableRowRadioToggle();
	
	$.modal();
	$('textarea').elastic();
	
	// Todos as table com classe .grid dentre de #article que não sejam um .relatorio
	$('table:not(.no-datatable).grid').dataTable({
		"oLanguage": {
			"sProcessing":   "Processando...",
			"sLengthMenu":   "Exibir _MENU_ registros",
			"sZeroRecords":  "Não foram encontrados resultados",
			"sInfo":         "Exibindo de _START_ a _END_ de _TOTAL_ registros",
			"sInfoEmpty":    "Exibindo de 0 a 0 de 0 registros",
			"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
			"sInfoPostFix":  "",
			"sSearch":       "Busca Rápida:",
			"sUrl":          "",
			"oPaginate": {
				"sFirst":    "&laquo;&laquo;  Primeiro",
				"sPrevious": "&laquo; Anterior",
				"sNext":     "Seguinte &raquo;",
				"sLast":     "Último &raquo;&raquo;"
			}
		},
		iDisplayLength: 20, 
		sPaginationType: "full_numbers", 
		"bLengthChange": false, 
		"bSort": false, 
		"fnDrawCallback": function() {
			updateTable($(this));
		}
	});
	$('.grid tfoot').hide();
});

function updateTable(table) {
	$(table).find("tr").each(function(i,row) {
		hasRadio = ($(row).find(':radio').size() > 0) ? true : false;
		hasCheckedRadio = ($(row).find('input[type=radio]:checked').size() > 0) ? true : false;
		if($(row).hasClass(tableRowRadioCheckedClass) && hasRadio && !hasCheckedRadio) {
			$(row).removeClass(tableRowRadioCheckedClass);
		}
	});
}

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

function removeListAndInputItem(listItem, selectId, itemValue) {
	$(listItem).parent().remove();
    $("#" + selectId).val('');
}

function removeListAndSelectItem(listItem, selectId, itemValue) {
    $(listItem).parent().remove();
    $("#" + selectId + " option[value='" + itemValue + "']").remove();
}