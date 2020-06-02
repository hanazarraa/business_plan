jQuery(document).ready( function () {

	var annee_embauche = jQuery('#annee_embauche').val();
	var valeur_salaire = new Array();
	var temp = 0;

	for(var i = 0; i <= parseInt(jQuery('#nb_periodes').val())-1; i++) {
		valeur_salaire[temp] = 0;
		temp++;
	}

	temp = 0;
	for(var i = annee_embauche; i <= parseInt(jQuery('#nb_periodes').val())-1; i++) {
		valeur_salaire[temp] = jQuery('#valeur-'+i).val();
		temp++;
	}

	jQuery('#annee_embauche').bind('change', function(e) {
		annee_embauche = jQuery(this).val();

		temp = 0;
		for(var i = annee_embauche; i <= parseInt(jQuery('#nb_periodes').val())-1; i++) {
			jQuery('#valeur-'+i).val(valeur_salaire[temp]);
			temp++;
		}

		affiche_salaire();
	});

	var affiche_salaire = function() {
		jQuery('.annee-embauche').hide();
		for(var i = annee_embauche; i <= parseInt(jQuery('#nb_periodes').val())-1; i++) {
			jQuery('#annee-embauche-'+i).show();
		}
	}

	affiche_salaire();


	var commission = function()
	{
		if(jQuery('#type_commission').val() == 'ca')
		{
			jQuery('#choix_commission_produit').hide();
			jQuery('#choix_commission').show();
		}
		else if (jQuery('#type_commission').val() == 'produit')
		{
			jQuery('#choix_commission').hide();
			jQuery('#choix_commission_produit').show();
		}
		else
		{
			jQuery('#choix_commission_produit').hide();
			jQuery('#choix_commission').hide();
		}
	}

	//Appel �  l'affichage
	commission();


	var charge_patronales = function()
	{
		if(jQuery('#tns:checked').val() == true || jQuery('#jei:checked').val() == true || jQuery('#gratification:checked').val() == true)
		{
			jQuery('#charges_patronales_block').hide();
			jQuery('#commissions_block').hide();
		}
		else
		{
			jQuery('#charges_patronales_block').show();
			jQuery('#commissions_block').show();
		}
	}

	charge_patronales();

	$('body').on('change', '#type_commission', function(e) {
	//jQuery('#type_commission').live('change', function(e) {
		commission();
	});

	$('body').on('change', '#tns', function(e) {
	//jQuery('#tns').live('change', function(e) {
		charge_patronales();
	});

	$('body').on('change', '#jei', function(e) {
	//jQuery('#jei').live('change', function(e) {
		charge_patronales();
	});

	$('body').on('change', '#gratification', function(e) {
	//jQuery('#gratification').live('change', function(e) {
		charge_patronales();
	});

	// autocompletion produit
	$("#produit_commission_ca").autocomplete({
		  source: function(request, response) {
		                $.ajax({ url: base_url+"espace_client/produit/autocomplete/"+$("#produit_commission_ca").val()+"/"+$("#id_salarie").val(),

		                dataType: "json",
		                type: "POST",
		                 success: function(data){
		                		response(data);
		                 }
		                })
		               },
		minLength: 1
	});

	$('body').on('click', '#ajouter_produit_pourcentage_commission_ca', function(e) {
	//$("#ajouter_produit_pourcentage_commission_ca").live('click',function() {

		jQuery.ajax({
	        type: "POST",
	        url: base_url+'espace_client/salarie/ajout_commission_produit/',
	        dataType: "json",
	        data: {'id_produit' : $('#produit_commission_ca_2').val(),'nom_produit' :  $('#produit_commission_ca').val(), 'pourcentage' :  $('#produit_pourcentage_commission_ca').val(), 'id_salarie': $('#id_salarie').val()},
	        success: function(data){

	        	if(data.resultat == 'ok') {

	        	nom_produit = $('#produit_commission_ca').val();
	        	pourcentage = $('#produit_pourcentage_commission_ca').val();

	        	$("#liste_commision_produits").append('<li id="commission-'+data.id_produit+'"><strong>'+data.nom_produit+'</strong> avec un pourcentage de <strong>'+pourcentage+'</strong><img src="'+base_url+'/images/icones/cancel.png" alt="Supprimer" class="tool-tip suppression_commission" /></li>');

	        	 $('#produit_commission_ca').val('');
	        	 $('#produit_pourcentage_commission_ca').val('');

	          jQuery.gritter.add({
		           title: 'Notification',
		           text: 'Ajout avec succès'
	          });
	         }else {
	          jQuery.gritter.add({
	           title: 'Notification',
	           text: 'Erreur de l\'ajout'
	          });
	         }
	        },
	        error: function(){
		          jQuery.gritter.add({
			           title: 'Notification',
			           text: 'Erreur de l\'ajout'
			          });
	        },
	     });

	});

	$('body').on('click', '.suppression_commission', function(e) {
	//$(document).on('click',".suppression_commission", function() {

		if(!confirm("Êtes-vous sûr de vouloir faire cela ?")) {
			e.preventDefault();
		}
		else
		{
			li =  $(this).parent();
			temp = li.attr('id').split('-');
			id = temp[1];

			jQuery.ajax({
		        type: "POST",
		        url: base_url+'espace_client/salarie/supprime_commission/',
		        data: {'id_produit' : id, 'id_salarie': $('#id_salarie').val()},
		        success: function(res){
		         if(res == 'ok') {

		        $(li).remove();

		          jQuery.gritter.add({
			           title: 'Notification',
			           text: 'Suppression avec succès'
		          });
		         }else {
		          jQuery.gritter.add({
		           title: 'Notification',
		           text: 'Erreur de supression'
		          });
		         }
		        },
		        error: function(){
			          jQuery.gritter.add({
				           title: 'Notification',
				           text: 'Erreur de supression'
				          });
		        },
		     });
		}

	}


	);



	/* POPUP */

	$('body').on('click', '.open-popup-salarie', function(e) {
	//$('.open-popup-salarie').click(function(e) {
		e.preventDefault();

		var id = 0;
		var id_departement = 0;
		if($(this).hasClass('edit-salarie')) {
			id = $(this).attr('id').split('-')[1];
		}else {
			id_departement = $(this).attr('id').split('-')[1];
		}

		jQuery.ajax({
	        type: "POST",
	        url: base_url+'espace_client/salarie/ajax_popup_salarie/',
	        data: {'id' : id, 'id_departement' : id_departement},
	        success: function(res){
		        if(res.result == 'ok') {
		        	$('.form-salarie-container').html(res.html);
		        	$('.form-salarie-container').append('<input type="hidden" id="scrolltop" name="scrolltop" value="'+$(document).scrollTop()+'" />');
		        	charge_patronales();
		        	affiche_salaire();
		        	commission();

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
		        }
	        }
		});

		$('#popup-salarie').animate({
		    left: '0'
		}, 400, function() {

		});
	});

	$('body').on('click', '#hide-salarie', function(e) {
	//$('#hide-salarie').click(function(e) {
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