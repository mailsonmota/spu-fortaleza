function fnDataTablesPipeline (sSource, aoData, fnCallback) {
	$.ajax({
		async: true, 
		cache: true, 
		url: sSource,
		dataType: 'json',
		data: aoData,
		beforeSend: function() {
			$('.dataTables_processing').html('<span>' + $('.dataTables_processing').text() + '</span>');
		},
		success: function(json) {
			fnCallback(json);
		}, 
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			$('.dataTables_processing').css('visibility', 'hidden');
			$('#section').prepend('<div class="message error">' + errorThrown + '</div>');
		}, 
		complete: function(jqXHR, textStatus) {
			//tableRowCheckboxToggle();
			//tableRowRadioToggle();
		}
	});
}