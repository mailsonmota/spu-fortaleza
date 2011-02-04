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
	// array used to remember the radio state for the radioes applicable
	var tableRowRadios = new Array;
	// traverse all rows
	$("tr").each(function(i,row) {
		// do something to the rows with the particular classes specified
		$.each(tableRowRadioToggleClasses, function(j,tableRowRadioToggleClass) {
			if($(row).hasClass(tableRowRadioToggleClass)) {
				hasChecked = false;

				$("tr:eq("+i+")").click(function() {
					
					$("tr:not(:eq(" + i + "))").removeClass(tableRowRadioCheckedClass);
					
					// toggle the checked state
					$(this).find(":radio").each(function(j,radio) {
						if(applicableRadio(radio)) {
							uniqueId = '' + i + j;
							
							for (var i in tableRowRadios) {
								tableRowRadios[i] = false;
							}
							
							// toggle radio states
							if (typeof(tableRowRadios[uniqueId]) == 'undefined' || !tableRowRadios[uniqueId]) {
								$(row).addClass(tableRowRadioCheckedClass);
								tableRowRadios[uniqueId] = true;
							} else {
								$(row).removeClass(tableRowRadioCheckedClass);
								tableRowRadios[uniqueId] = false;
							}
							radio.checked = tableRowRadios[uniqueId];
						}// end of if applicable radio
					});
				}); // end of click event
				
				// for initialization
				$("tr:eq("+i+") :radio").each(function(j,radio) {
					if(applicableRadio(radio) && radio.checked) {
						hasChecked = true;
						return false;
					}
				});

				// if the row contains checked applicable radioes, mark all radioes within row as checked in memory
				// and mark the row
				if(hasChecked) {
					$("tr:eq("+i+") :radio").each(function(j,radio) {
						if(applicableRadio(radio)) {
							uniqueId = '' + i + j;
							$(row).addClass(tableRowRadioCheckedClass);
							tableRowRadios[uniqueId] = true;
						}
					});
				}

			} // end of if the tr has the applicable class
		});
	});
}
//}); // end of DOM ready

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