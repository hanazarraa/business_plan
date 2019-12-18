(function($){
 
	$.fn.readToken = function(nom){
		
		a = $(this).attr('class');
		index = a.indexOf(nom);
		
		if(index != -1)
		{
			b = a.substring(index);
			c = b.split(" ");
			valeur = c[0].substring(c[0].lastIndexOf("-")+1)
			
			return valeur;
		}
		else
		{
			return null;
		}
		
        };
 
})(jQuery)