jQuery(document).ready( function () {
	/* POPUP */

	$('.open-popup-produit').click(function(e) {
		e.preventDefault();

		var id = 0;
		if($(this).hasClass('edit-produit')) {
			id = $(this).attr('id').split('-')[1];
		}

		jQuery.ajax({
	        type: "POST",
	        url: base_url+'espace_client/produit/ajax_popup_produit/',
	        data: {'id' : id},
	        success: function(res){
		        if(res.result == 'ok') {
		        	$('.form-produit-container').html(res.html);
		        	$('.form-produit-container').append('<input type="hidden" id="scrolltop" name="scrolltop" value="'+$(document).scrollTop()+'" />');
		        	$('#action').val($(location).attr('href'));

		        	$('.tool-tip').tooltip(
		        		{
		                    show: null,
		                    position: {
		                        my: "left bottom",
		                        at: "right top"
		                    },
		                    open: function( event, ui ) {
		                        //ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
		                    }
		                }
		        	);

		        	$('td input').each(function(e) {
		        		$(this).attr('size', $(this).val().length);
		        	});

		        	if(jQuery('#permanent').length) {
			        	var permanent = function()
			        	{
			        		if(jQuery('#permanent').attr('checked'))
			        		{
			        			jQuery('#occurrences').fadeOut();
			        		}
			        		else
			        		{
			        			jQuery('#occurrences').fadeIn();
			        		}
			        	}

			        	//Appel à l'affichage
			        	permanent();

			        	jQuery('#permanent').bind('change', function(e) {
			        		permanent();
			        	});
		        	}

		        	$('#popup-salarie').animate({
		    		    left: '0'
		    		}, 400, function() {

		    		});
		        }
	        }
		});
	});

	$('#hide-salarie').click(function(e) {
		e.preventDefault();

		$('#popup-salarie').animate({
		    left: '-50%'
		}, 400, function() {

		});
	});

	$(document).bind('scroll', function(e) {
		$('#scrolltop').val($(document).scrollTop());
	});
});
