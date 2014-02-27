//$(document).ready(function(){}); shorthand
$(function(){
	//Business Function
	$('#business-function-assign-menu-add-button').click(function(e){		
		if($('.business_function_menu').last().val() <=0){
			$('.business_function_menu').addClass('error-input');
			$('#business-function-submit-buttons .warning-box')
				.html('Please select a valid Business Function Menu entry before adding.')
				.fadeIn('slow');
			alert('Please select a valid Business Function Menu entry before adding.');
			e.preventDefault();
		}
		else{ 
			$('#business-function-assign-menus-form-container > ul:last-child').clone().insertAfter('#business-function-assign-menus-form-container > ul:last-child'); 
			e.preventDefault();
		}
	});		
	
	$('.user-assign-company-assign-menu-add-button').click(function(e){
		$('#assign-company > ul').last().clone().insertAfter($('#assign-company > ul').last());
		e.preventDefault();
	});
	
	//validate if email is correct format
	validateEmail = function(email){
		var isEmailValid = false;
		var atPos = email.indexOf('@');
		var dotPos = email.lastIndexOf('.');
		if(atPos < 1 || dotPos < (atPos + 2) || (dotPos + 2) >= email.length){ isEmailValid = false; }
		else{ isEmailValid = true; }
		return isEmailValid;
	}	
	
	hasAttribute = function(element, attribute){
		if($(element).size() > 0){ return $(element).prop(attribute); }
		return false;
	}
});