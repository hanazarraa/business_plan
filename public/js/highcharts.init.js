$(document).ready(function() {
	Highcharts.setOptions({
	    lang: {
	        decimalPoint: '.',
	        thousandsSep: ' '
	    },
	    
        plotOptions: {
            series: {
                animation: false
            }
        },

	    tooltip: {
	        yDecimals: 2 // If you want to add 2 decimals
	    }
	});
});