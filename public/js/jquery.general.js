jQuery(document).ready( function () {


//	if(jQuery('.tool-tip').length) {
//		jQuery('.tool-tip').each(function(e) {
//			jQuery(this).qtip({
//				   content: jQuery(this).attr('alt'),
//				   show: 'mouseover',
//				   hide: 'mouseout',
//				   style: {
//				      name: 'blue' // Inherit from preset style
//				   },
//				   position: {
//				      corner: {
//				         target: 'bottomRight',
//				         tooltip: 'topLeft'
//				      }
//				   }
//			});
//		});
//	}


	if(jQuery('.jquery-lightbox').length) {
		jQuery('.jquery-lightbox').lightBox({
			imageLoading: base_url+'images/lightbox-ico-loading.gif',
			imageBtnClose: base_url+'images/lightbox-btn-close.gif',
			imageBtnPrev: base_url+'images/lightbox-btn-prev.gif',
			imageBtnNext: base_url+'images/lightbox-btn-next.gif',
			containerResizeSpeed: 350,
			txtImage: 'Image',
			txtOf: 'de'
		});
	}

	if(jQuery('.jquery-zoom').length) {
		var options = {
	            zoomType: 'standard',
	            lens:true,
	            preloadImages: true,
	            alwaysOn:false,
	            zoomWidth: 500,
	            zoomHeight: 350,
	    };
		jQuery('.jquery-zoom').jqzoom(options);
	}

	if(jQuery('.jquery-hide').length) {
		jQuery('.jquery-hide').hide();
	}

	if(jQuery('.datepicker').length) {
		jQuery(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
	}

	$('body').on('click', '.demande-confirmation', function(e) {
	//jQuery('.demande-confirmation').live('click',function(e) {
		var message = 'Êtes-vous sûr de vouloir faire cela ?';
		/* if(jQuery(this).attr('title') != undefined) {
			message = jQuery(this).attr('title');
		} */
		if(!confirm(message)) {
			e.preventDefault();
		}
	});

//	if(jQuery('.tool-tip').length) {
//		jQuery('.tool-tip').each(function(e) {
//			jQuery(this).qtip({
//				   content: jQuery(this).attr('alt'),
//				   style: {
//				   	   classes: 'ui-tooltip-blue ui-tooltip-shadow ui-tooltip-rounded'
//				   },
//				   position : {
//					   my: 'right bottom',
//					   at: 'top left'
//				   }
//			});
//		});
//	}

// Tooltips
$('html').on('mouseover','body', function(e) {
	$('.tool-tip').tooltip({
		placement: "top"
	});

	$('.tool-tip-bottom').tooltip({
		placement: "bottom"
	});

	$('.tool-tip-left').tooltip({
		placement: "left"
	});

	$('.tool-tip-right').tooltip({
		placement: "right"
	});
});

	$(".onglet_table h2").click(function(e)
	{
		if($(this).parent(".onglet_table").children(".bloc_masque").is(":hidden"))
		{
			$(this).parent(".onglet_table").children(".bloc_masque").show();
			$(this).addClass("active");
		}
		else if($(this).parent(".onglet_table").children(".bloc_masque").is(":visible"))
		{
			$(this).parent(".onglet_table").children(".bloc_masque").hide();
			$(this).removeClass("active");
		}
	});

	$(".bloc_masque").hide();

	$("#popup_immo").slideDown();
	$("#popup_immo a").click(function(e)
	{
		$("#popup_immo").slideUp();
		if($('#popup').is(':checked'))
		{
			var popup;
			if($(this).attr('id') == 'oui')
			{
				popup = 1;
			}
			else
			{
				popup = 0;
			}
			jQuery.ajax({
			       type: "POST",
			       url: base_url+"espace_client/compte/accepter_popup",
			       data: 'popup='+popup,
			       dataType: "json",
			       success: function(data){
						if(data.result == 'ok') {
						}else {
						}
			       }
			});
		}
	});

	$('.slide-sous-menu-bp').hide();
	$('#lien-menu-business-plan').click(function(e) {
		e.preventDefault();

		if($('.slide-sous-menu-bp').is(':visible')) {
			$('.slide-sous-menu-bp').slideUp();
			$(this).children('.fleche').removeClass('open');
		}else {
			$('.slide-sous-menu-bp').slideDown();
			$(this).children('.fleche').addClass('open');
		}
	});


	var timeout;

	$('.menu-login-absolute').bind('mouseenter', function(e) {
		clearTimeout(timeout);
		$('.menu-login-absolute').show();
	});

	$('.bloc-user-login').bind('mouseenter', function(e) {
		clearTimeout(timeout);
		$('.menu-login-absolute').show();
	});

	$('.display-menu').bind('mouseleave', function(e) {
		clearTimeout(timeout);
		timeout = setTimeout(function() {
			$('.menu-login-absolute').hide();
		},1000);
	});

	$('.menu-compte-absolute').bind('mouseenter', function(e) {
		clearTimeout(timeout);
		$('.menu-compte-absolute').show();
	});

	$('.block-system-user-menu').bind('mouseenter', function(e) {
		clearTimeout(timeout);
		$('.menu-compte-absolute').show();
	});

	$('.display-menu2').bind('mouseleave', function(e) {
		clearTimeout(timeout);
		timeout = setTimeout(function() {
			$('.menu-compte-absolute').hide();
		},1000);
	});

	$('select.jump_list').change(function() {
		if (!this.value) {
			return;
		}

		if(!confirm('Des données ont été saisies sans avoir été sauvegardées. Pensez à cliquer sur "Valider". Voulez vous tout de même quitter cette page ?')) {
			e.preventDefault();
		}

		var url, url_len, last_char;

		if (this.value.match('://')) {
			window.top.location = this.value;
		}
		else {
			url = window.location.toString();
			url_len = url.length;
			last_char = url.substring(url_len - 1, url_len);

			if (last_char === 'index.html') {
				window.top.location = url + this.value;
			}
			else
			{
				window.top.location = url + '/' + this.value;
			}
		}
	});


	$('.commentremplir').click(function(e) {
		e.preventDefault();

		id = $(this).attr('id').split('-')[1];

		jQuery.ajax({
	        type: "POST",
	        url: base_url+'espace_client/espace_client/ajax_commentremplir/',
	        data: {'id' : id},
	        success: function(res){
		        $('#commentremplir .content').html(res.html);

		        $('#commentremplir').animate({
				    left: '0'
				}, 400, function() {

				});

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
	        }
		});
	});

	$('#hide-commentremplir').click(function(e) {
		e.preventDefault();

		$('#commentremplir').animate({
		    left: '-50%'
		}, 400, function() {

		});
	});

	var timeout_commentremplir;
	$('.commentremplir').not('.home').bind('mouseenter', function(e) {
		clearTimeout(timeout_commentremplir);
		$(this).animate({
		    width: '100',
		    height: '100'
		}, 150, function() {
			$(this).find('.content-2').hide();
			$(this).find('.content-1').show();
		});
	});

	$('.commentremplir').not('.home').bind('mouseleave', function(e) {
		timeout_commentremplir = setTimeout(function() {
			$('.commentremplir').find('.content-2').show();
			$('.commentremplir').find('.content-1').hide();
			$('.commentremplir').css('width','45px');
			$('.commentremplir').css('height','45px');
		},200);
	});

	$('.import-bp').click(function(e) {
		e.preventDefault();
		$('.import-bp-form').hide();
		$('#import-'+$(this).attr('id')).slideDown();
	});

	$('.close-import-bp'). click(function(e) {
		e.preventDefault();
		$('.import-bp-form').hide();
	});

	$('.hors_ligne').click(function(e) {
		e.preventDefault();
		$('.hors_ligne-popup').hide();
		$('.hors_ligne-popup').slideDown();
	});

	$('.close-hors_ligne-popup'). click(function(e) {
		e.preventDefault();
		$('.hors_ligne-popup').hide();
	});


	$('.export-bp').click(function(e) {
		e.preventDefault();
		$('.export-bp-form').hide();
		$('#export-'+$(this).attr('id')).slideDown();
	});

	$('.close-export-bp'). click(function(e) {
		e.preventDefault();
		$('.export-bp-form').hide();
	});
});
