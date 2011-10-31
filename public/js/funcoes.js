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
    $('.mascaraData').datePicker({
        startDate: '01-01-2000'
    }).trigger('change');

    //Modal
    $('a[rel=modal]').click(function() {
        $($(this).attr('href')).modal();
        return false;
    });

    //Textarea
    $('textarea').elastic();

    //Form Validation
    $('#article form').validate();
    $.extend($.validator.messages, {
        required: "Este campo é requerido.",
        email: "E-mail inválido."
    }
    );

    //Autocomplete
    $('.autocomplete').val('Digite para buscar...').addClass('autocomplete-wait');
    $('.autocomplete').focus(function() {
        $(this).val('').removeClass('autocomplete-wait');
    });
    $('.autocomplete').blur(function() {
        $(this).val('Digite para buscar...').addClass('autocomplete-wait');
    });

    //Retrocompatibilidade
    $('a[href=http://spu.fortaleza.ce.gov.br/spu/estrutura/index.php]').click(function() {
        window.open($(this).attr('href'),
            '',
            'toolbar=no,location=no,status=yes,menubar=no,scrollbars=no,resizable=yes,width=785,height=520,left=0,top=0'
            );
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

// função para verificar o cpf
function valida_cpf(cpf)
{
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
        return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}

function valida_cnpj(cnpj)
{
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    if (cnpj.length < 14 && cnpj.length < 15)
        return false;
    for (i = 0; i < cnpj.length - 1; i++)
        if (cnpj.charAt(i) != cnpj.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}

$(function(){
    ord_tipo = ['CNPJ','CPF', 'CPF', 'CPF', 'CPF', 'CNPJ'];
    tipo = ord_tipo[0];
    $("dd select#mani-tipo option").each(function(e){
        if (e == 0) {
            $("dd #manifestanteCpfCnpj").setMask({
                mask:"99.999.999/9999-99",
                autoTab: false
            }).parent().prev().find('label').html(ord_tipo[e]);
        }
        $(this).attr('tipo', ord_tipo[e]);
    });

    $("dd select#mani-tipo").live("change", function(){
        tipo = $(this).find("option:selected").attr('tipo');
        $("dd #manifestanteCpfCnpj").parent().prev().find('label').html(tipo);

        if(tipo == ord_tipo[1]) {
            $("dd #manifestanteCpfCnpj").val("").setMask({
                mask:"999.999.999-99",
                autoTab: false
            });
        } else {
            $("dd #manifestanteCpfCnpj").val("").setMask({
                mask:"99.999.999/9999-99",
                autoTab: false
            });
        }
    });

    //023.845.193-38
    $("dd #manifestanteCpfCnpj").focusout(function(){
        var str = $(this).val().replace(/[^\w\s]/g, "");

        if(str !== "") {
            if(tipo == ord_tipo[1]) {
                if(!valida_cpf(str)){
                    alert("CPF inválido")
                    $(this).val("")
                }
            } else {
                if(!valida_cnpj(str)){
                    alert("CNPJ inválido")
                    $(this).val("")
                }
            }
        }
    });
});