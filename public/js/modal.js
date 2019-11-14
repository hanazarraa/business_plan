jQuery(document).ready( function () {
	$("body").on('click','.open-modal', function(ev) {
		ev.preventDefault();
	    var target = $(this).attr("href");
	    var title = $(this).attr('data-title');
		var size = $(this).attr('data-modal-size');
		if(size != 'lg' && size !='md' && size !='sm') {
			size = 'lg';
		}



	    $("#dynamicmodal-"+size+" .modal-content .dynamic-container").empty();
	    // load the url and show modal on success
	    $('#dynamicmodal').modal({

    	});

	    $("#dynamicmodal-"+size+" .modal-content .dynamic-container").load(target, function() {
	         $("#dynamicmodal-"+size).modal("show");
	         $('#dynamicmodal-'+size+' .modal-title').html(title);

			 if($('.datepicker').length) {
		 		$(".datepicker").datepicker({ dateFormat: 'dd/mm/yy', changeYear:true });
		 	}

			/* Taille des inputs adaptée à leur contenu */
			$('td input').each(function(e) {
				var input_length = $(this).val().length;
				if(input_length == 0) {
					input_length = 4;
				}
				$(this).attr('size', input_length);
			});
	    });
	});
});
