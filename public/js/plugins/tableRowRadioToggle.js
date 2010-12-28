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
// by default, this script will apply to radioes within the rows specified
// HOWEVER, you may manually exclude certain radioes based on their name, id or class
// specify below
var excludeRadiosWithNames=new Array('testName');
var excludeRadiosWithIds=new Array('checkme100', 'checkme101');
var excludeRadiosWithClasses=new Array('testClass');



/**********************************************************************/
/* STOP editing unless you know what you are doing :)
/**********************************************************************/
// on page load, this script goes thru all tr elements
// prepare the form when the DOM is ready

//$(document).ready(function() {
function tableRowRadioToggle() {
	// traverse all rows
	$("tr").each(function(i,row) {
		// do something to the rows with the particular classes specified
		$.each(tableRowRadioToggleClasses, function(j,tableRowRadioToggleClass) {
			if($(row).hasClass(tableRowRadioToggleClass)) {
				hasChecked = false;

				$("tr:eq("+i+")").click(function() {
					// toggle the checked state
					$("tr:eq("+i+") :radio").each(function(j,radio) {
						checked = false;
						
						if(applicableRadio(radio)) {
							uniqueId = '' + i + j;
							// toggle radio states
							if (radio.checked) {
								checked = false;
							} else {
								checked = true;
								$(row).addClass(tableRowRadioCheckedClass);
							}
							
							radio.checked = checked;
							
							updateTable($(row).parent());
						}// end of if applicable radio
					});
				}); // end of click event
			} // end of if the tr has the applicable class
		});
	});
}
//}); // end of DOM ready

function updateTable(table) {
	$(table).find("tr").each(function(i,row) {
		if($(row).hasClass(tableRowRadioCheckedClass) && $(row).find('input[type=radio]:checked').size() == 0) {
			$(row).removeClass(tableRowRadioCheckedClass);
		}
	});
}

function applicableRadio(radio) {
	var applicable = true;
	// not applicable if the radio is disabled
	if(radio.disabled) {
		applicable = false;
	} else {
		$.each(excludeRadiosWithNames, function(a,excludeRadiosWithName) {
			if(jQuery.trim(radio.name) == jQuery.trim(excludeRadiosWithName)) {
				applicable = false;
			}
		});
		$.each(excludeRadiosWithIds, function(b,excludeRadiosWithId) {
			if(jQuery.trim(radio.id) == jQuery.trim(excludeRadiosWithId)) {
				applicable = false;
			}
		});
		$.each(excludeRadiosWithClasses, function(c,excludeRadiosWithClass) {
			if($(radio).hasClass(jQuery.trim(excludeRadiosWithClass))) {
				applicable = false;
			}
		});
	}
	return applicable;
}