//$(document).ready(function(){}); shorthand
$(function(){
	//User Company Selection Page	
	var UserCompanyKey = $('#user-selection-company').val();
	var UserCompanyText = $('#user-selection-company > option:selected').text();
	var temp = $('.user-company-selection-value').val(UserCompanyKey + '|' + UserCompanyText); 
	var _key = temp.val().split('|');
	var selectedUserCompany = _key[0];
	
	$('#user-selection-company').change(function(){ 
		var UserCompanyKey = $('#user-selection-company').find('option:selected').val();
		var UserCompanyText = $('#user-selection-company').find('option:selected').text().toUpperCase();
		var temp = $('.user-company-selection-value').val(UserCompanyKey + '|' + UserCompanyText); 
		var _key = temp.val().split('|');
		selectedUserCompany = _key[0];
		if(selectedUserCompany > 0 && $('#user-company-selection-error-box').is(':visible')){			
			$('#user-company-selection-error-box').fadeOut('slow');
		}
	});	
	
	$('#user-company-selection-button').click(function(e){		
		if(selectedUserCompany <= 0){
			e.preventDefault();
			$('#user-selection-company').addClass('error-input');
			$('#user-company-selection-error-box')
				.html('Please choose a company')
				.fadeIn('slow');
		}
		else{
			$('#user-selection-company').removeClass('error-input');
			$('#user-company-selection-error-box')
				.html()
				.fadeOut('slow');
		}
	});
});