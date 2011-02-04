/**********************************************************************/
/* jQuery Plugin: tableRowRadioToggle
/* Written by: Dodo http://pure-essence.net
/* Written for jQuery v1.2.3 on Feb 26, 2008
/* Version 1.0
/**********************************************************************/
// give me a list of table rows classes you want to apply this affect to
var tableRowRadioToggleClasses=new Array('odd', 'even');
// give me a class name you want to add to the table row when there are radioes checked
var tableRowRadioCheckedClass='marked';


/**********************************************************************/
/* STOP editing unless you know what you are doing :)
/**********************************************************************/
// on page load, this script goes thru all tr elements
// prepare the form when the DOM is ready

//$(document).ready(function() {
function tableRowRadioToggle() {
	// traverse all rows
	$("tr :radio").each(function(i,radio) {
		row = $(radio).parent().parent();
		
		$(row).click(function() {
			
			// toggle the checked state
			currentRow = $(this);
			$(currentRow).find(":radio").each(function(j,currentRadio) {
				checked = false;
				
				if (applicableRadio(currentRadio)) {
					/*// toggle radio states
					if (currentRadio.checked) {
						checked = false;
					} else {
						checked = true;
						$(currentRow).addClass(tableRowRadioCheckedClass);
					}*/
					if (currentRow.hasClass(tableRowRadioCheckedClass)) {
						checked = false;
					} else {
						checked = true;
						$(currentRow).addClass(tableRowRadioCheckedClass);
					}
					
					currentRadio.checked = checked;
					
					updateTable(currentRow.parent());
				}// end of if applicable radio
			});
		}); // end of click event
	});
}
//}); // end of DOM ready


function updateTable(table) {
	$(table).find("tr").each(function(i,row) {
		hasRadio = ($(row).find(':radio').size() > 0) ? true : false;
		hasCheckedRadio = ($(row).find('input[type=radio]:checked').size() > 0) ? true : false;
		if($(row).hasClass(tableRowRadioCheckedClass) && hasRadio && !hasCheckedRadio) {
			$(row).removeClass(tableRowRadioCheckedClass);
		}
	});
}

function applicableRadio(radio) {
	var applicable = true;
	// not applicable if the radio is disabled
	if(radio.disabled) {
		applicable = false;
	}
	return applicable;
}