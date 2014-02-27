Modernizr.load({
	test: Modernizr.inputtypes.date,  
	nope: ['http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js'],
	complete: function () {
		$('input[type=date]').datepicker({dateFormat: 'yy-mm-dd'}); 	
	}
});

