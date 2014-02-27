//$(document).ready(function(){}); shorthand
$(function(){
	//Business Function Page Validation
	var businessFunctionValidation = false;
	$('#business_function_value').change(function(e){
		var business_function_entry = $(this).val().trim().toUpperCase();
		$('#business-function-selection-business-function > option').each(function(){
			var business_function_selection_option_values = $(this).text().trim().toUpperCase();
			if(business_function_selection_option_values == business_function_entry){
				businessFunctionValidation = false;
				$('#business-function-submit-buttons .warning-box')
					.html(business_function_selection_option_values + ' already exists. Please use another business function.')
					.fadeIn('slow');
				$('#business_function_value').addClass('error-input');
				return false;
			}
			else if(business_function_entry.length <= 0){
				businessFunctionValidation = false;
				$('#business-function-submit-buttons .warning-box')
					.html('Please enter a valid Business Function entry.')
					.fadeIn('slow');
				$('#business_function_value').addClass('error-input');
				return false;
			}
			else{
				businessFunctionValidation = true;
				$('#business_function_value').removeClass('error-input');
				$('#business-function-submit-buttons .warning-box').hide();
			}
		});
	});
	
	$('#business-function-assign-menus-form-container .menu_isActive').change(function(){
		if($(this).is(':checked')){ $(this).siblings().val('1'); }
		else{ $(this).siblings().val('0'); }
	});
	
	$('#business-function-assign-menus-form-container .menu_isReadWrite').change(function(){
		if($(this).is(':checked')){ $(this).siblings().val('1'); }
		else{ $(this).siblings().val('0'); }
	});	
		
	$('#business-function-submit-buttons .form-submit .submit-positive').click(function(e){
		if($('#business_function_value').val().trim().toUpperCase().length <= 0){
			$('#business_function_value').addClass('error-input');
			$('#business-function-submit-buttons .warning-box')
				.html('Please enter a valid Business Function entry.')
				.fadeIn('slow');
			e.preventDefault();
		}
		
		if($('.business_function_menu').val() <= 0){
			$('.business_function_menu').addClass('error-input');
			$('#business-function-submit-buttons .warning-box')
				.html('Please select a menu.')
				.fadeIn('slow');
			e.preventDefault();
		}
		
		if(!businessFunctionValidation){ e.preventDefault(); }
	});
	
	$(this).on('change','input[name*="isReadWrite_temp"]',function(){
		if($(this).is(':checked')){ $(this).siblings().val('1'); }
		else{ $(this).siblings().val('0'); }
	});
	
	$(this).on('change','input[name*="assign_menu_isActive_temp"]',function(){
		if($(this).is(':checked')){ $(this).siblings().val('1'); }
		else{ $(this).siblings().val('0'); }
	});
	
	//Company Page Validation
	var companyValidation = false;
	
	//Check if Company name already exists
	$('#company_name').change(function(){
		var company_name_entry = $(this).val().trim().toUpperCase();
		$('#company-selection-company > option').each(function(){
			var company_selection_option_values = $(this).text().trim().toUpperCase();
			if(company_selection_option_values == company_name_entry){
				companyValidation = false;
				$('#company_name').addClass('error-input');
				$('#company-submit-buttons .warning-box')
					.html(company_name_entry + ' Company already exists.')
					.fadeIn('slow');
				return false;
			}
			else if(company_name_entry.length <= 0){
				companyValidation = false;
				$('#company_name').addClass('error-input');
				$('#company-submit-buttons .warning-box')
					.html('Please enter a valid Company name.')
					.fadeIn('slow');	
			}
			else{ 
				companyValidation = true;
				$('#company_name').removeClass('error-input');
				$('#company-submit-buttons .warning-box').hide(); 
			}
		});
	});
	
	$('#company_registration_number').change(function(){var requested_reg_num = $(this).val().trim();		
		$.post(CONST.AJAX_URL + '/company.php', { CompanyRegistrationNumber: JSON.stringify(requested_reg_num) })
			.done(function(ResultSet){
				if(ResultSet == 'duplicate'){
					$('#company_registration_number').addClass('error-input');
					$('#company-submit-buttons .warning-box, #company-warning-box')
						.html(requested_reg_num.toUpperCase() + ' already exists. Please use another Registration number')
						.fadeIn('slow');	
				}
				else{
					$('#company_registration_number').removeClass('error-input');
					$('#company-submit-buttons .warning-box').hide();
				}
		});		
	});	
	
	$('#company-submit-buttons .form-submit .submit-positive').click(function(e){
		//Check if Company name text field has entry
		if($('#company_name').val().trim().toUpperCase().length <= 0){							
			$('#company_name').addClass('error-input');
			$('#company-submit-buttons .warning-box')
				.html('Please enter a valid Company name.')
				.fadeIn('slow');
			e.preventDefault();
		}
		//Check if Company registration text field has entry
		if($('#company_registration_number').val().trim().toUpperCase().length <= 0){
			$('#company_registration_number').addClass('error-input');
			$('#company-submit-buttons .warning-box')
				.html('Please enter a valid Company Registration number.')
				.fadeIn('slow');
			e.preventDefault();
		}
		if(!companyValidation){	e.preventDefault(); }
	});	
	
	//User Page Validation
	var user_validation = false;
	var isEmailValid = false;
	
	$('#user-detail .user_login').change(function(){
		var email = $(this).val().trim().toUpperCase();
		//Check if email is valid
		if(!validateEmail(email)){ 			
			isEmailValid = false; 
			$(this).addClass('error-input');
			$('#user-submit-error-box').html('<strong>Warning: </strong> Invalid email. Please enter a valid email address').fadeIn('slow');
			return false;
		}
		else{ 
			isEmailValid = true; 
			$(this).removeClass('error-input');
			$('#user-submit-error-box').hide();
		}
		
		//Check if user already exists
		$('#user-selection-user > option').each(function(){
			var existing_users = $(this).text().toUpperCase();			
			if(email == existing_users){
				user_validation = false;
				$('#user-detail .user_login').addClass('error-input');
				$('#user-submit-error-box')
					.html('<strong>Warning: </strong>' + email + ' User already exists.')
					.fadeIn('slow')
					.removeClass('hidden');
				return false;
			}
			else{
				user_validation = true;
				$('#user-detail .user_login').removeClass('error-input');
				$('#user-submit-error-box').hide().addClass('hidden');
			}
		});		
	});	
	
	//Check if User Name has entry
	$('#user-detail .user_name').change(function(){
		var user_name = $(this).val().trim().toUpperCase();
		if(user_name.length <= 0){
			user_validation = false;
			$(this).addClass('error-input');
			$('#user-submit-error-box')
				.html('Please enter a User Name')
				.fadeIn('slow')
				.removeClass('hidden');
			return false;
		}
		else{
			user_validation = true;
			$(this).removeClass('error-input');
			$('#user-submit-error-box')
				.hide()
				.addClass('hidden');
		}
	});
	
	//Check if Password has entry
	$('#user-detail .user_password').change(function(){
		var  user_password = $(this).val().trim().toUpperCase();
		if(user_password.length <= 0){
			user_validation = false;
			$(this).addClass('error-input');
			$('#user-submit-error-box')
				.html('Please enter a Password')
				.fadeIn('slow')
				.removeClass('hidden');
			return false;
		}
		else{
			user_validation = true;
			$(this).removeClass('error-input');
			$('#user-submit-error-box')
				.hide()
				.addClass('hidden');
		}
	});
	
	//Check if Active checkbox is checked or not(User Detail)
	$('#user-detail .isActive').change(function(){ 
		if($(this).is(':checked')){ $(this).siblings().val('1'); }
		else{ $(this).siblings().val('0'); }
	});
	
	//Check if Absent checkbox is checked or not(User Detail)
	$('#user-detail .isAbsent').change(function(){ 
		if($(this).is(':checked')){ $(this).siblings().val('1'); }
		else{ $(this).siblings().val('0'); }
	});

	$('.assignment_business_function_selection').change(function(){	
		var BusinessFunctionPK = $(this).val();
		var isMeterReader = null;
		var key = 0;
		if(BusinessFunctionPK.lastIndexOf('|') !== -1){
			var temp = BusinessFunctionPK.split('|');
			key = temp[0];
		}
		else{ key = BusinessFunctionPK; }
		$.post(CONST.AJAX_URL + '/businessfunction.php', { key: key })
			.done(function(ResultSet){	
				ResultSet = $.parseJSON(ResultSet);
				
				//isMeterReader(Team Tab)
				switch(ResultSet.isMeterReader){
					case '0':
						$('input[name*="business_function_isMeterReader"]').val('0'); 
						//if business function is not isMeterReader set TeamFk = -1
						$('.user_team').val('-1');
						$('#user-assign-business-function-company .tab-container ul li')
							.eq(2)
							.removeClass('show')
							.addClass('hidden');
						break;
					case '1':
						var selectedTeam = $('.assignment_team_selection > option:selected').val();
						$('input[name*="business_function_isMeterReader"]').val('1'); 
						$('.user_team').val(selectedTeam);
						$('#user-assign-business-function-company .tab-container ul li')
							.eq(2)
							.removeClass('hidden')
							.addClass('show');
						break;
				}
				
				var ERP_Code = $('input[name="user_erp_code"]').val();				
				//isPortfolioManager(ERP Code Tab)
				switch(ResultSet.isPortfolioManager){
					case '0':
						$('input[name="user_ERP_code"]')
							.attr('value', '')
							.val('');
						$('#user-assign-business-function-company .tab-container ul li:last-child')
							.removeClass('show')
							.addClass('hidden');						
						break;
					case '1': 
						$('input[name="user_ERP_code"]')
							.attr('value', ERP_Code)
							.val(ERP_Code);
						$('#user-assign-business-function-company .tab-container ul li:last-child')
							.removeClass('hidden')
							.addClass('show');
						break;
				}
		});
	});	
					
	$('#user-submit-buttons .submit-positive, .user_submit').click(function(e){		
		var user_login = $('#user-detail .user_login').val().trim().toUpperCase();
		var user_name = $('#user-detail .user_name').val().trim().toUpperCase();
		var user_password = $('#user-detail .user_password').val().trim().toUpperCase();
		
		if(user_login.length <= 0){			
			$('#user-detail .user_login').addClass('error-input');
			$('#user-submit-error-box').html('<strong>Warning: </strong> Please enter your email address.').fadeIn('slow');
			e.preventDefault();
			return false;			
		}
		
		if(!validateEmail(user_login)){ 	
			$('#user-detail .user_login').addClass('error-input');
			$('#user-submit-error-box').html('<strong>Warning: </strong> Please enter your email address.').fadeIn('slow');
			e.preventDefault();
			return false;
		}
		
		if(!isEmailValid){			
			$('#user-detail .user_login').addClass('error-input');
			$('#user-submit-error-box').html('<strong>Warning: </strong> Invalid email').fadeIn('slow');
			e.preventDefault();
			return false;
		}
		
		if(user_name.length <= 0){			
			$('#user-detail .user_name').addClass('error-input');
			$('#user-submit-error-box').html('<strong>Warning: </strong> Please enter a valid User Name.').fadeIn('slow');			
			e.preventDefault();
			return false;
		}
		
		if(user_password.length <= 0){
			$('#user-detail .user_password').addClass('error-input');
			$('#user-submit-error-box').html('<strong>Warning: </strong> Please enter a Password.').fadeIn('slow');
			e.preventDefault();
			return false;
		}
		
		if(!user_validation){
			e.stopPropagation();
			$('#user-submit-error-box').html('<strong>Warning: </strong> User Login and Email are not the same').fadeIn('slow');
			e.preventDefault();
		}
		
		
		//Check if a business function has been selected
		if($('.assignment_business_function_selection').val() <= 0){
			$('#user-submit-error-box').html('<strong>Warning: </strong> Please choose a Business function').fadeIn('slow');
			$('.assignment_business_function_selection').addClass('error-input');
			e.preventDefault();
			return false;
		}
		
		//Check if a Company has been selected 
		if($('.assignment_company_selection').val() <= 0){
			$('#user-submit-error-box').html('<strong>Warning: </strong> Please choose a Company').fadeIn('slow');
			$('.assignment_company_selection').addClass('error-input');
			$('.tab-container .tabs').last().fadeIn();
			e.preventDefault();
			return false;
		}
		
		//Team Tab Validations
		var isMeterReader = $('.business_function_isMeterReader').val();
		var team = $('.tab-container li.tabs:last-child a');
		if(isMeterReader <= 0) { 
			team.addClass("hidden");
			team.removeClass("show");						
		}
		
		$('.tab-container li.tabs:last-child').prop('style', null);
		$('.tab-container li.tabs:last-child').removeAttr('style');
		
		return true;
	});	
	
	if(hasAttribute($('input[name*="business_function_isActive_temp"]'), 'checked')){
		$('input[name*=business_function_isActive_value]').val('1');
	}
	
	if(hasAttribute($('input[name*="company_isActive_temp"]'), 'checked')){
		$('input[name*=company_isActive_value]').val('1');
	}
	
	$(this).on('change','input[name*="business_function_isActive_temp"]',function(){
		if($(this).is(':checked')){ 
			$(this).prop('checked', true);
			$(this).attr('checked', true);
			$(this).siblings().val('1'); 
		}
		else{ 
			$(this).prop('checked', false);
			$(this).removeAttr('checked');
			$(this).siblings().val('0'); 
		}
	});
	
	$(this).on('change','input[name*="company_isActive_temp"]',function(){
		if($(this).is(':checked')){ 
			$(this).prop('checked', true);
			$(this).attr('checked', true);
			$(this).siblings().val('1'); 
		}
		else{ 
			$(this).prop('checked', false);
			$(this).removeAttr('checked');
			$(this).siblings().val('0'); 
		}
	});
	
	//Team
	$('.assignment_team_selection').change(function(){
		var teamSelection = $(this).val();
		if(teamSelection == 0){ $('.user_team').val('-1'); }
		else{ $('.user_team').val(teamSelection); }
	});

	/*--- Modal functions for Billing ---*/
	$('input[name="Invoice"]').click(function(e){		
		var answer = confirm('Are you sure you want to submit an Invoice?');
		if(!answer){ e.preventDefault(); }
	});
});