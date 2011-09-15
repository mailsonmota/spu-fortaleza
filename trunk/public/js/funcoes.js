jQuery(document).ready(function() {
		
	createUncheckedSelector();
	triggerEnterButton();
	
	//Máscaras
	$('.mascaraData').setMask('date');
	$('.mascaraHora').setMask('time');
	$('.mascaraCep').setMask('cep');
	$('.fone').setMask('phone');
	
	//DatePicker
	$.dpText = {
		TEXT_PREV_YEAR		:	'Ano anterior',
		TEXT_PREV_MONTH		:	'Mês anterior',
		TEXT_NEXT_YEAR		:	'Ano seguinte',
		TEXT_NEXT_MONTH		:	'Mês seguinte',
		TEXT_CLOSE			:	'Fechar',
		TEXT_CHOOSE_DATE	:	'Escolher'
	};
	$('.mascaraData').datePicker({startDate: '01-01-2000'}).trigger('change');
	
	//Modal
	$('a[rel=modal]').click(function() {
		$($(this).attr('href')).modal();
	});
	
	//Textarea
	$('textarea').elastic();
	
	//Todos as table com classe .grid dentre de #article que não sejam um .relatorio
	/*$('table:not(.no-datatable).grid').dataTable({
		oLanguage: {
			"sUrl": baseUrl + "/js/plugins/dataTables/jquery.dataTables.pt-br.txt"
		},
		iDisplayLength: 50, 
		sPaginationType: "text_only", 
		bLengthChange: false, 
		bSort: false, 
	});
	$('.grid tfoot').hide();*/
	
	//Form Validation
	$('#article form').validate();
	$.extend($.validator.messages, {
        	required: "Este campo é requerido.",
        	email: "E-mail inválido."
        }
	);
	
	//Autocomplete
	$('.autocomplete').val('Digite para buscar...').addClass('autocomplete-wait');
	$('.autocomplete').focus(function() {$(this).val('').removeClass('autocomplete-wait');});
	$('.autocomplete').blur(function() {
		$(this).val('Digite para buscar...').addClass('autocomplete-wait');
	});
	
	//Retrocompatibilidade
	$('a[href=http://spu.fortaleza.ce.gov.br/spu/estrutura/index.php]').click(function() {
		window.open($(this).attr('href'), '', 'toolbar=no,location=no,status=yes,menubar=no,scrollbars=no,resizable=yes,width=785,height=520,left=0,top=0');
		return false;
	});
	
	//TableRowCheckboxToggle
	$('tbody tr').live('click', function(event) {
		$(this).toggleClass('marked')
		if (event.target.type !== 'checkbox') {
			checked = $(this).hasClass('marked')
			$(':checkbox', this).attr('checked', checked)
		}
	});
	
	//Check all das tables
	enableTableCheckAll();
});

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
    return false;
}

function removeListAndSelectItem(listItem, selectId, itemValue) {
    $(listItem).parent().remove();
    $("#" + selectId + " option[value='" + itemValue + "']").remove();
    return false;
}

function enableTableCheckAll() {
	$("#checkbox_checkAll").click(function() {
        checked = $(this).attr("checked");
        $(this).parent().parent().parent().parent().find('tbody tr td input').each(function() {
            $(this).attr("checked", checked)
            if (checked) {
                $(this).parent().parent().addClass("marked")
            } else {
                $(this).parent().parent().removeClass("marked")
            }
        });
    });
}