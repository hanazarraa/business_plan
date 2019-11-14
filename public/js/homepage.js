$(document).ready( function () {
	$('.citation').hide();
	$('.liencitation .active').hide();
	
	var citation_active = 1;
	var max_citation = 5;
	var timeout;
	
	var change_citation = function(i) {
		$('.citation').hide();
		$('#citation-'+i).fadeIn(1000);
		$('.liencitation .active').hide();
		$('.liencitation .inactive').show();
		$('#liencitation-'+i).find('.active').show();
		$('#liencitation-'+i).find('.inactive').hide();
		
		timeout = setTimeout(function() {
			citation_active++;
			if(citation_active > 5) {
				citation_active = 1;
			}
			change_citation(citation_active);
		}, 14000);
	}
	
	change_citation(citation_active);
	
	$('.liencitation').click(function(e) {
		e.preventDefault();
		
		clearTimeout(timeout);
		citation_active = $(this).attr('id').split('-')[1];
		change_citation(citation_active);
	});
	
	$('.heure_affiche').each(function(e) {
		if($(this).html() == '(Journe)') {
			$(this).html('(Journée)');
		}
	});
});