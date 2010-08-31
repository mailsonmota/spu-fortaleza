jQuery(document).ready(function() {
	$('a[rel*=facebox]').facebox()
	triggerEnterButton();
	//adjustCheckboxColumnGrid();
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
    var el = document.getElementsByName(id);
    for (var i = 0; i <el.length; i++) { 
    	el[i].checked = checked;
	}
    
    if (checked == true ) {
        if ($('.grid tbody tr.selected').removeClass('selected')) {
            $('.grid tbody tr').addClass('selected');
        }
    } else {
        $('.grid tbody tr.selected').removeClass('selected');
    }
}

/**
 * Increase the clickable area of the checkboxes on the grid to the column they're on.
 * @return void
 */
function adjustCheckboxColumnGrid()
{
	$('.grid td.colunaFixa').click(function() {
		var element = $(this).children('input[type=checkbox]');
		if (element.is(':checked')) {
			element.attr('checked', false);
		} else {
			element.attr('checked', true);
		}
	});
}