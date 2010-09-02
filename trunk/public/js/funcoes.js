jQuery(document).ready(function() {
	createUncheckedSelector();
	$('a[rel*=facebox]').facebox()
	triggerEnterButton();
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

function createUncheckedSelector()
{
	jQuery.extend(jQuery.expr[':'], {
		unchecked: function(element) {
			return ('checkbox' === element.type || 'radio' === element.type) && !element.checked;
		}
	})
}