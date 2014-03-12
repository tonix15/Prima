// JavaScript Document (2nd one to load)
$(document).ready(function(e) { 
    /* -------------------- form tabs shifting functionality ------------------- */
    var tab_menu_selected = null;
    var tab_name = null;
	
	/*--- User Company Selection ---*/

    $('.tab-menu li').click(function() {
        tab_name = $(this).closest('ul').prop('id');		
        $('#' + tab_name +  ' li' ).each(function() {
            if ($(this).hasClass('tab-menu-selected')) {
                tab_menu_selected = $(this).prop('id');				
                return;
            }
        });
        $('#' + tab_menu_selected).removeClass('tab-menu-selected');
        $('#' + tab_menu_selected).addClass('tab-menu-item');
        // $('#' + tab_menu_selected + '-content').removeClass('show');
        // $('#' + tab_menu_selected + '-content').addClass('hidden');
		$('#' + tab_menu_selected + '-content').fadeOut();
		$('#' + tab_menu_selected + '-content').hide();
        tab_menu_selected = $(this).prop('id');
        $(this).removeClass('tab-menu-item');
        $(this).addClass('tab-menu-selected');
		$('#' + tab_menu_selected + '-content').fadeIn();
		$('#' + tab_menu_selected + '-content').show();
        // $('#' + tab_menu_selected + '-content').removeClass('hidden');
        // $('#' + tab_menu_selected + '-content').addClass('show');
    });

    /* ------ parameters -------- */
    $('.parameters-add-button').click(function() {
        var add_items_container = getContainerNameToAppend(this);
        var parameter_type = add_items_container.split('-')[1];	
		var error_box = '#parameters-' + parameter_type + '-addline-error-box';

		var hasErrors = false;
        if (!checkInputHasAllValues(add_items_container, 'input[type="text"]')) {
			$(error_box).html('Input a value before adding');
            displayAddlineErrorBox(error_box);
			$('#' + add_items_container + ' input[type="text"]').last().mousedown(function() {
				$(error_box).fadeOut();
			});
			return false;
        } else {
        	removeAllErrorInput(add_items_container, 'input[type="text"]');
			$(error_box).fadeOut();
        }
        
        if (checkAppendValueDuplicate(add_items_container, '.utility-descr-value')) {
			$(error_box).html('One or more lines has a duplicate value');
			$('#' + add_items_container + ' input[type="text"]').mousedown(function() {
				$(error_box).fadeOut();
			});
			
			hasErrors = true;
        } else {
        	removeAllErrorInput(add_items_container, '.utility-descr-value');
			$(error_box).fadeOut();
        } 
        
        if (parameter_type === 'utility') {
	        if (checkAppendValueDuplicate(add_items_container, '.utility-erpcode-value')) {
				$(error_box).html('One or more lines has a duplicate value');
				$('#' + add_items_container + ' input[type="text"]').mousedown(function() {
					$(error_box).fadeOut();
				});
				hasErrors = true;
	        } else {
	        	removeAllErrorInput(add_items_container, '.utility-erpcode-value');
				$(error_box).fadeOut();
	        } 
        }
        
        if (hasErrors) {
        	displayAddlineErrorBox(error_box);
        	return false;
        }
		// add new line
        ul = '#' + add_items_container + ' ul:last-child';
        $('#' + add_items_container).append($(ul).clone());
        $(ul + ' input[type="checkbox"]').prop('checked', true);
        $(ul + ' input[type="hidden"]').val(1);
        $(ul + ' input[type="hidden"].parameter-pk-value').val(0);
        $(ul + ' input[type="text"]').val('');
        return false;
    });	
	
    $('#parameter-save-button').click(function() {
		// validate parameters
		var parameters = ['utility', 'title', 'language', 'building', 'team', 'contact', 'reason', 'testmeter'];
		var tab = ['utility', 'title', 'language', 'building', 'team', 'preferred-contact', 'reason-code', 'meter-test-result'];
		var len = parameters.length,
		add_items_container = '',
		hasErrors = false;
		
		for (var i = 0; i < len; i++) {
			add_items_container = 'parameters-' + parameters[i] + '-add-content';
			removeAllErrorInput(add_items_container, '.utility-descr-value');
			
			if (checkAppendValueDuplicate(add_items_container, '.utility-descr-value')) {
				hasErrors = true;
			}
			
			if (parameters[i] === 'utility') {
				removeAllErrorInput(add_items_container, '.utility-erpcode-value');
				if (checkAppendValueDuplicate(add_items_container, '.utility-erpcode-value')) {
					hasErrors = true;
				}
			}
			
			if (hasErrors) {
				$('#parameters-' + tab[i]).click();
				displayOnsubmitErorMessage('One or more lines has a duplicate value');
				return false;
			}
		}
		return true;	
	});

    /* ------ rate -------- */
    $('.rate-add-button').click(function() {
		var add_items_container = getContainerNameToAppend(this);
		var rate_type = add_items_container.split('-');
		var error_box = '#' + rate_type[0] + '-' + rate_type[1] + '-addline-error-box';
		
        if (!checkInputHasAllValues(add_items_container, 'input[type="text"]')) {
			$(error_box).html('Fill in all the fields before adding');
            displayAddlineErrorBox(error_box);
			$('#' + add_items_container + ' input[type="text"]').mousedown(function() {
				$(error_box).fadeOut();
			});
			return false;
        } else {
			removeAllErrorInput(add_items_container, 'input[type="text"]');
			$(error_box).fadeOut();
		}
        
		if (checkAppendValueDuplicate(add_items_container, '.rate-unique-value')) {
			$(error_box).html('One or more lines has a duplicate value');
            displayAddlineErrorBox(error_box);
			$('#' + add_items_container + ' input[type="text"].rate-unique-value').click(function() {
				$(error_box).fadeOut();
			});
			return false;
        } else {
			removeAllErrorInput(add_items_container, '.rate-unique-value');
			$(error_box).fadeOut();
		}
	
		// add new line
        ul = '#' + add_items_container + ' ul:last-child';
		var to_value = 0;
		if (rate_type[0] === 'ratescale') {
			to_value = $(ul + ' input[type="text"].ratescale-to').val();
		}
		
        $('#' + add_items_container).append($(ul).clone());
        $(ul + ' input[type="hidden"]').val(1);
		$(ul + ' input[type="hidden"].rate-pk-value').val(0);
		$(ul + ' input[type="text"].rate-unique-value').removeClass('error-input');
        $(ul + ' input[type="text"]').val('');

		if (rate_type[0] === 'ratescale') {
			$(ul + ' input[type="text"].ratescale-from').val(to_value);
			$(ul + ' input[type="text"].ratescale-to').val(999999);
		}
		
        return false;
    });
	
	var orig_rate_code = $('#rate_code').val();
	$('#rate-save-button').click(function() {
		// validate rates
		var required_input = ['code', 'name', 'utility', 'provider'];
		var rate_types = [
			'ratescale-retail', 'ratescale-bulk', 'fixrate-retail', 
			'fixrate-bulk', 'fixedfees-retail', 'fixedfees-bulk'
		];
		var len = required_input.length,
		rate_types_len = rate_types.length,
		input_field = '',
		hasErrors = false,
		hasSuppliedRate = false;
		
		for (var i = 0; i < len; i++) {
			input_field = '#rate_' + required_input[i];
			if (!$.trim($(input_field).val()) || parseInt($(input_field).val()) <= 0) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			} else {
				$(input_field).removeClass('error-input');
			}
		}
		if (hasErrors) {
			displayOnsubmitErorMessage('Highlighted fields requires input');
			return false;
		} else {
			$('.submit-error-box').fadeOut();
		}
		
		for (var i = 0; i < rate_types_len; i++) {
			hasSuppliedRate = hasSuppliedRate || checkInputHasAllValues(rate_types[i] + '-add-content ul:first-child', 'input[type="text"]');
			if (i === 0) {
				hasSuppliedRate = hasSuppliedRate && ($('#rate_descr').val().length > 0 ? true : false);
				if (!hasSuppliedRate) {
					$('#rate_descr').addClass('error-input');
				}
			}
		}	

		if (!hasSuppliedRate) {
			displayOnsubmitErorMessage('Please provide at least 1 rate scale or fixed rate or fixed fees.');
			return false;
		} else {
			for (var i = 0; i < rate_types_len; i++) {
				if (i === 0) {
					$('#rate_descr').removeClass('error-input');
				}
				removeAllErrorInput(rate_types[i] + '-add-content ul:first-child', 'input[type="text"]');	
			}
		}
		// check duplicate rate record
		rate_code = $.trim($('#rate_code').val());
		if (orig_rate_code.toUpperCase() != rate_code.toUpperCase()) {
			var data = { action: 'checkDuplicate', rate_code: rate_code };
			data = 'data=' + JSON.stringify(data);
			$.ajaxSetup({async: false});
			hasErrors = false;
			$.post(CONST.AJAX_URL + '/rate.php', data).done(function(result) { 
				if (result && result === 'duplicate') {	
					hasErrors = true;
				}
			});
			$.ajaxSetup({async: true});
			
			if (hasErrors) {
				highlightRequiredInput($('#rate_code'));
				displayOnsubmitErorMessage('Rate code already exists');
				return false;
			}
		}
		
		// validate fixed rate and fixed fees
		var rates = ['fixrate-retail', 'fixrate-bulk', 'fixedfees-retail', 'fixedfees-bulk'];
		var len = rates.length,
		add_items_container = '';
		
		for (var i = 0; i < len; i++) {
			add_items_container = rates[i] + '-add-content';
			if (checkAppendValueDuplicate(add_items_container, 'input[type="text"].rate-unique-value')) {
				displayOnsubmitErorMessage('One or more lines has a duplicate value');
				return false;
			}
		}

		for (var i = 0; i < 2; i++) { // check fixed rate
			if (checkInputHasAllValues(rates[i] + '-add-content ul:first-child', 'input[type="text"]')) {
				var percent_elements = '#' + rates[i] + '-add-content input.fixedrate-percent';
				var total_percentage = 0;
				$(percent_elements).each(function() {
					if (this.value) {
						total_percentage += parseFloat(this.value);
					}
				});

				if (total_percentage % 100 != 0) {
					$(percent_elements).each(function() {
						highlightRequiredInput(this);
					});
					displayOnsubmitErorMessage('Fixed rate percentage must be divisible by 100');
					return false;
				} else {
					$(percent_elements).each(function() {
						removeErrorInput(this);
					});
				}
			}
		}
		return true;	
	});
   
    $('#modal-rate-detail-new-period-button').click(function() {
        var start_date = $('#modal-rate-detail-start-date').val();
        if (start_date) {
            $('#rate-detail-start-date').val(start_date);
            $('#rate-start-date').val(start_date);
            $('.modal-shade, .modal-view').fadeOut();
            $('body').css('overflow', 'visible');
        } else {
        	$('#modal-rate-detail-start-date').addClass('error-input');
        	$('#rates-modal-new-period').css('height', 190);
        	$('#date-selection-error-box').fadeIn();         
        }
        return false;
    }); 
    
    $('#modal-rate-detail-start-date').change(function() {
    	$('#modal-rate-detail-start-date').removeClass('error-input');
    	$('#rates-modal-new-period').css('height', 165);
    	$('#date-selection-error-box').hide();        
    });

    $('#rate-selection-provider').change(function() {
        var providerPK = $(this).val();
        $('#rate-selection-rate').html('<option value="0">Please select...</option>');
        var data = { action: 'getRates', providerPK: providerPK };
        data = 'data=' + JSON.stringify(data);
        
        $.post(CONST.AJAX_URL + '/rate.php', data).done(function(result) {   	
        	var rate_list = $.parseJSON(result);
            var len = rate_list.length;
            if (rate_list) {    
                for (var i = 0; i < len; i++) {
                   $('#rate-selection-rate').append('<option value="' + rate_list[i].ratePK + '">' + rate_list[i].name + '</option>'); 
                }
                
                if (len > 0) { // hide please selected
                	$('#rate-selection-rate > option:first-child').hide();
                } 
            }
        });
    });
    
    $('#rate-selection-view').click(function() {
        if ($('#rate-selection-rate').val() <= 0) {
            return displayErrorSelection('rate', 'rate', 'Please select a rate');
        }
        
        if (!$('#rate-selection-date').val()) {
            return displayErrorSelection('rate', 'date', 'Please select an effective date');
        }
        return true;
    });

    $('.selection-required-input').change(function() {
        $(this).removeClass('error-input');
        hideSelectionErrorBox($(this).prop('id'));
    });

    /* ------ unit -------- */
    $('#unit-list-add-button').click(function() {  
    	var add_items_container = getContainerNameToAppend(this);
		var error_box = '#unit-list-addline-error-box';
		
        if (!checkInputHasAllValues(add_items_container, 'input[type="text"]')) {
			$(error_box).html('Fill in all the fields before adding');
            displayAddlineErrorBox(error_box);
			$('#' + add_items_container + ' input[type="text"]').click(function() {
				$(error_box).fadeOut();
			});
			return false;
        } else {
			removeAllErrorInput(add_items_container, 'input[type="text"]');
			$(error_box).fadeOut();
		}
        
		if (checkAppendValueDuplicate(add_items_container, '.unit-unique-value')) {
			$(error_box).html('One or more lines has a duplicate unit value');
            displayAddlineErrorBox(error_box);
			$('#' + add_items_container + ' input[type="text"].unit-unique-value').click(function() {
				$(error_box).fadeOut();
			});
			return false;
        } else {
			removeAllErrorInput(add_items_container, '.unit-unique-value');
			$(error_box).fadeOut();
		}
    	
		// add new line
        ul = '#' + add_items_container + ' ul:last-child';
        $('#' + add_items_container).append($(ul).clone());
        $(ul + ' input[type="hidden"]').val(0);
        $(ul + ' input[type="text"]').val('');
        $(ul + ' input[name^=unit_isOccupied_values]').val(1);
        $(ul + ' input[type="checkbox"]').prop('checked', true);
        return false; 
    });
    
    $(this).on('click', 'input[name^=unit_isOccupied_temp]', function(){  
        setCheckboxValues('unit_isOccupied_temp');
    });
    
    $('#unit-selection-list').click(function() {
        if ($('#unit-selection-building').val() <= 0) {
            return displayErrorSelection('unit', 'building', 'Please select a building');
        }
        return true;
    });
    
    $('#unit-save-button').click(function() {
    	// validate units
    	if (checkAppendValueDuplicate('unit-list-add-content', '.unit-unique-value')) {
    		displayOnsubmitErorMessage('One or more lines has a duplicate unit value');
			return false;
        } else {
			removeAllErrorInput(add_items_container, '.unit-unique-value');
			$(error_box).fadeOut();
		}
		
    	return true;
    });

    /* ------ Provider -------- */
	
	var orig_provider_code = $.trim($('#provider_code').val());
	var orig_erpcode = $.trim($('#provider_erpcode').val());
	$('#provider-save-button').click(function() {
		// validate provider
		var required_input = ['code', 'name', 'erpcode'];
		var len = required_input.length,
		input_field = '',
		provider_code = '',
		erpcode = '',
		hasErrors = false,
		hasChanges = false,
		data = new Object();
		
		for (var i = 0; i < len; i++) {
			input_field = '#provider_' + required_input[i];
			if (!$.trim($(input_field).val())) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			}
		}

		if (hasErrors) {
			displayOnsubmitErorMessage('Highlighted fields requires input');
			return false;
		}
		
		provider_code = $.trim($('#provider_code').val());
		erpcode = $.trim($('#provider_erpcode').val());
		data.action = 'checkDuplicate';
		
		if (orig_provider_code.toUpperCase() != provider_code.toUpperCase()) {
			data.provider_code = provider_code;
			hasChanges = true;
		} 
		
		if (orig_erpcode.toUpperCase() != erpcode.toUpperCase()) {
			data.erpcode = erpcode;
			hasChanges = true;
		} 
		
		if (hasChanges) {
			var response = null;
			hasErrors = false;
			data = 'data=' + JSON.stringify(data);
			
			$.ajaxSetup({async: false});
			$.post(CONST.AJAX_URL + '/provider.php', data).done(function(result) { 	
				if (result) {
					try {
						response = $.parseJSON(result);
						if (Object.keys(response).length > 0) {
							hasErrors = true;
						}
					} catch(e) { }
				}
			});
			$.ajaxSetup({async: true});
			
			if (hasErrors) {
				var errmsg = '';

				if (response.checkProviderCode === 'duplicate') {
					errmsg = 'Provider code ';
					highlightRequiredInput('#provider_code');
				}
				
				if (response.checkERPCode === 'duplicate') {
					errmsg = errmsg ? errmsg + 'and ERP code ' : 'ERP code ';
					highlightRequiredInput('#provider_erpcode');
				}

				displayOnsubmitErorMessage(errmsg + 'already exists');
				return false;
			}	
		}

		return true;
	});
	
	$('#provider-selection-view').click(function() {
        if (!hasChosen($('#provider-selection-provider').val())) {
            return displayErrorSelection('provider', 'provider', 'Please select a provider');
        }
        
        return true;
    });

     /* ------- Building -------- */
    $('#building-rate-account-add-button').click(function() {
		var add_items_container = getContainerNameToAppend(this);
		var hasInput = true;
		var error_box = '#building-rate-account-addline-error-box';
        var ul = '';
        
		hasInput = hasInput && checkInputHasAllValues(add_items_container, 'select');   		 
		
        if (!hasInput) {
            $(error_box).html('Input a value before adding'); 
            displayAddlineErrorBox(error_box);
			$('#' + add_items_container).children().mousedown(function() {
				$(error_box).fadeOut();
			});
			return false;
        }
        
        ul = '#' + add_items_container + ' ul:last-child';
        $('#' + add_items_container).append($(ul).clone());
        $(ul + ' .rate-account-rate').html('<option value="0">Please select...</option>');
        $(ul + ' select, ' + ul + ' input[type="hidden"]').val(0);
        $(ul + ' input[type="text"]').val('');
        $(ul + ' input[type="checkbox"]').prop('checked', false);
        return false;
    });
	
	var original_building_code = $('#building_code').val();
	$('.building-save-button').click(function() {
		// validate building
		var required_input = ['code', 'name', 'type', 'no_units'];
		var input_field = '',
		hasErrors = false;
		
		for (var i = 0; i < 2; i++) {
			input_field = '#building_' + required_input[i];
			if (!$.trim($(input_field).val())) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			}
		}
		
		for (var i = 2; i < 4; i++) {
			input_field = '#building_' + required_input[i];
			if ($.trim($(input_field).val()) <= 0) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			}
		}

		if (hasErrors) {
			displayOnsubmitErorMessage('Highlighted fields requires input');
			return false;
		}
		
		var building_code = $.trim($('#building_code').val());
		if (original_building_code.toUpperCase() != building_code.toUpperCase()) {
			var data = { action: 'checkDuplicate', building_code: $('#building_code').val() };
			data = 'data=' + JSON.stringify(data);
			$.ajaxSetup({async: false});
			hasErrors = false;
			$.post(CONST.AJAX_URL + '/building.php', data).done(function(result) { 
				if (result && result === 'duplicate') {	
					hasErrors = true;
				}
			});
			$.ajaxSetup({async: true});
			
			if (hasErrors) {
				highlightRequiredInput($('#building_code'));
				displayOnsubmitErorMessage('Building code already exists');
				return false;
			}
		}
			
		
		if (!checkInputHasAllValues('building-rate-account-add-content ul:first-child', 'select')) {
			displayOnsubmitErorMessage('Please provide at least one rate account');
			return false;
		} 
		
		return true;
	});
    
    $(this).on('click', 'input[name^=rate_is_showSteps_temp]', function(){  
        setCheckboxValues('rate_is_showSteps_temp');
    });
    
    $('#building-selection-view').click(function() {
        if ($('#building-selection-building').val() <= 0) {
            return displayErrorSelection('building', 'building', 'Please select a building');
        }
        return true;
    });
    
    
    $(this).on('change', '.rate-account-utility', function(){  
    	var utilityPK = $(this).val();
    	var sel_rate_provider = $(this).parent().parent().find('.rate-account-rate');
    	sel_rate_provider.html('<option value="0">Please choose a rate</option>');
    	var data = { action: 'getRates', providerPK: 0, };
        data = 'data=' + JSON.stringify(data);
        
        $.post(CONST.AJAX_URL + '/rate.php', data).done(function(result) {  
        	var rate_list = $.parseJSON(result);
            var len = rate_list.length;
            if (rate_list) {    
                for (var i = 0; i < len; i++) {
                	if (rate_list[i].utilityPK == utilityPK) {
            			sel_rate_provider.append('<option value="' + rate_list[i].ratePK + '">' + rate_list[i].name + '</option>'); 
                	}
                	
                	if (len > 0) { // hide please selected
                    	$('.rate-account-utility > option:first-child').hide();
                    } 
                }
            }
        }); 
    });

	
	
    
    /* functions to display modals */
    $('#rate-detail-new-period-button').click(function() {
    	moveModal('rates-modal-new-period');
    	if (!$('#rate-detail-start-date').val()) {
    		$('#modal-rate-detail-start-date').val('');
    	} else {
    		$('#modal-rate-detail-start-date').val($('#rate-detail-start-date').val());
    	}

    	$('#date-selection-error-box').hide();
    	$('#modal-rate-detail-start-date').removeClass('error-input');
        $('.modal-shade, .modal-view').fadeIn();
        $('body').css('overflow', 'hidden');
        return false;
    });

    $('.modal-close-button, #modal-rate-detail-cancel-button').click(function() {
        $('.modal-shade, .modal-view').fadeOut();
        $('body').css('overflow', 'visible');
    });
    
    /* ------- Customer -------- */
	$('#customer-save-button').click(function() {
		// validate customer
		var required_input = [
			'occupancy_date', // start of billing, index 0
			'deposit_required' // billing length 2
			//'name', // start of customer, index 2
			//'surname'
		],
		input_field = '',
		hasErrors = false;
		
		for (var i = 0; i < 2; i++) {
			input_field = '#billing_' + required_input[i];
			if (!$.trim($(input_field).val())) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			}
		}
		
		/* for (var i = 2; i < 4; i++) {
			input_field = '#customer_' + required_input[i];
			if (!$.trim($(input_field).val())) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			}
		} */
		
		if (hasErrors) {
			displayOnsubmitErorMessage('Highlighted fields requires input');
			return false;
		}
		
		// check occupancy date and vacancy date
		var occupancy_date_unix = new Date($('#billing_occupancy_date').val()).getTime();
		var vacancy_date_unix = new Date($('#billing_vacancy_date').val()).getTime();
		
		if (vacancy_date_unix <  occupancy_date_unix) {
			highlightRequiredInput('#billing_vacancy_date');
			displayOnsubmitErorMessage('Vacancy date should be greater than Occupancy date');
			return false;
		} else {
			$('#billing_vacancy_date').removeClass('error-input');
		}
		
		// check occupancy date
		if ($(this).prop('name') === 'Create') {
			var data = { 
				action: 'checkOccupancyDate', 
				buildingPK: $('#customer-selection-building').val(),
				unitPK: $('#customer-selection-unit').val(),
				occupancy_date: $('#billing_occupancy_date').val() 
			};
			data = 'data=' + JSON.stringify(data);
			$.ajaxSetup({async: false});
			hasErrors = false;
			$.post(CONST.AJAX_URL + '/customer.php', data).done(function(result) { 
				if (result && result === 'false') {	
					hasErrors = true;
				} 
			});
			$.ajaxSetup({async: true});
			
			if (hasErrors) {
				displayOnsubmitErorMessage('Unit is still occupied on the occupancy date you provided');
				highlightRequiredInput('#billing_occupancy_date');
				return false;
			}
		}
		
		return true;
	});
	
	var isSet_original = true;
	var customer_unitPK = 0;
	$('#customer-selection-building, #customer-selection-unit').click(function() {
		if($('input[name=Update]').length === 1 && isSet_original) {
			customer_buildingPK = $('#customer-selection-building').val();
			customer_unitPK = $('#customer-selection-unit').val();
			isSet_original = false;
		};
	});
	
	function showMoveButton() {
		$('input[name=customer_new_buildingPK]').val(0);
		$('input[name=customer_new_unitPK]').val(0);
		if($('input[name=Update]').length === 1
		&& $('input[name=Update]').is(':visible')
		&& $('#customer-selection-building').val() > 0
		&& $('#customer-selection-unit').val() > 0
		&& $('#customer-selection-unit').val() != customer_unitPK) {	
			$('#customer-selection-move').fadeIn();
		} else {
			$('#customer-selection-move').fadeOut();
		}
		$('#customer-selection-success-box').hide();
	}
	
    $('#customer-selection-building').change(function() {
		var buildingPK = $(this).val();
        $('#customer-selection-unit').html('<option value="0">Please choose a unit</option>');

        if (buildingPK > 0) {
            var data = { action: 'getUnits', buildingPK: buildingPK };
            data = 'data=' + JSON.stringify(data);
            $.post(CONST.AJAX_URL + '/unit.php', data).done(function(result) { 
                var unit_list = $.parseJSON(result);
                var len = unit_list.length;
                if (unit_list) {    
                    for (var i = 0; i < len; i++) {
                       $('#customer-selection-unit').append('<option value="' + unit_list[i].unitPK + '">' + unit_list[i].unitNo + '</option>'); 
                    }
                    
                    if (len > 0) { // hide please selected
                    	$('#customer-selection-unit > option:first-child').hide();
                    } 
                }
            });
        }
		showMoveButton();
    });
	
	$('#customer-selection-move').click(function() {
		$(this).hide();
		$('#customer-selection-success-box').fadeIn();
		$('input[name=customer_new_buildingPK]').val($('#customer-selection-building').val());
		$('input[name=customer_new_unitPK]').val($('#customer-selection-unit').val());
		return false;
	});
 
    $('#customer-selection-unit').change(function() {
        var unitPK = $(this).val();
        var buildingPK = $('#customer-selection-building').val();
        $('#customer-selection-tenant').html('<option value="0">Please choose a tenant no.</option>');
        $('#customer-selection-tenant1').html('<option value="0">Please choose a tenant no.</option>');
        if (unitPK > 0) {
            var data = { action: 'getBillingAccounts', unitPK: unitPK, buildingPK: buildingPK };
            data = 'data=' + JSON.stringify(data);
            $.post(CONST.AJAX_URL + '/billing.php', data).done(function(result) { 
                var billing_account_list = $.parseJSON(result);
                var len = billing_account_list.length;
                if (result) {    
                    for (var i = 0; i < len; i++) {
                       $('#customer-selection-tenant').append('<option value="' + billing_account_list[i].billing_accountPK + '">' + billing_account_list[i].billing_accountNo + '</option>'); 
					   $('#customer-selection-tenant1').append('<option value="' + billing_account_list[i].billing_accountPK + '">' + billing_account_list[i].billing_accountNo + '</option>'); 
                    }
                    
                    if (len > 0) { // hide please selected
                    	$('#customer-selection-tenant > option:first-child').hide();
						$('#customer-selection-tenant1 > option:first-child').hide();
                    } 
                }
            });
        }
		showMoveButton();
    });
    
    $('#customer-selection-view').click(function() {
        if ($('#customer-selection-building').val() <= 0) {
            return displayErrorSelection('customer', 'building', 'Please select a building');
        }
        
        if ($('#customer-selection-unit').val() <= 0) {
            return displayErrorSelection('customer', 'unit', 'Please select a unit');
        }
        
        if ($('#customer-selection-tenant').val() <= 0) {
            return displayErrorSelection('customer', 'tenant', 'Please select a tenant number');
        }

        return true;
    });
	
	$('#customer-selection-create').click(function() {
        if ($('#customer-selection-building').val() <= 0) {
            return displayErrorSelection('customer', 'building', 'Please select a building');
        }
        
        if ($('#customer-selection-unit').val() <= 0) {
            return displayErrorSelection('customer', 'unit', 'Please select a unit');
        }
		var customerCreateConfirm = confirm("Are you sure you want to Create a new Customer?");		
		if(customerCreateConfirm == false){ return false; }
		
        return true;
    });
		
	/* ------ Meter -------*/
	$('#meter-selection-building').change(function() {
        var buildingPK = $(this).val();
        $('#meter-selection-unit').html('<option value="0">Please choose a unit</option>');
        $('#meter-selection-meter').html('<option value="0">Please choose a meter</option>');
        if (buildingPK > 0) {
            var data = { action: 'getUnitsWithMeter', buildingPK: buildingPK };
            data = 'data=' + JSON.stringify(data);
            $.post(CONST.AJAX_URL + '/meter.php', data).done(function(result) { 
            	try {
            		var requestResult = $.parseJSON(result);
                    var unit_len = requestResult.unit_list.length;
                    var meter_len = requestResult.meter_list.length;
                    
                    if (unit_len > 0) {    
                        for (var i = 0; i < unit_len; i++) {
                           $('#meter-selection-unit').append('<option value="' + requestResult.unit_list[i].unitPK + '">' + requestResult.unit_list[i].unitNo + '</option>'); 
                        }
                        
                        $('#meter-selection-unit > option:first-child').hide(); 
                    }
                    
                    if (meter_len > 0) {    
                        for (var i = 0; i < meter_len; i++) {
                           $('#meter-selection-meter').append('<option value="' + requestResult.meter_list[i].meterPK + '">' + requestResult.meter_list[i].meterNo + '</option>'); 
                        }
                        
                        $('#meter-selection-meter > option:first-child').hide(); 
                    }
            	} catch (e) { }
            });
        }
    });
	
	$('#meter-selection-unit').change(function() {
        var unitPK = $(this).val();
        var buildingPK = $('#meter-selection-building').val();
		
        $('#meter-selection-meter').html('<option value="0">Please choose a meter</option>');
        
        var data = { action: 'getMeters', unitPK: unitPK, buildingPK: buildingPK };
        data = 'data=' + JSON.stringify(data);
        $.post(CONST.AJAX_URL + '/meter.php', data).done(function(result) { 
			if (result) {    
				var meter_list = $.parseJSON(result);
				var len = meter_list.length;
                for (var i = 0; i < len; i++) {
                   $('#meter-selection-meter').append('<option value="' + meter_list[i].meterPK + '">' + meter_list[i].meterNo + '</option>'); 
                }
                
                if (len > 0) { // hide please selected
                	$('#meter-selection-meter > option:first-child').hide();
                } 
            }
        });
    });
	
	$('#meter-selection-view, #meter-selection-create').click(function() {
        if ($('#meter-selection-building').val() <= 0) {
            return displayErrorSelection('meter', 'building', 'Please select a building');
        }
        
        if ($(this).prop('id') === 'meter-selection-view') {
			if ($('#meter-selection-meter').val() <= 0) {
				return displayErrorSelection('meter', 'meter', 'Please select a meter');
			} 
			
			return true;
		}
        
        if ($('#meter-selection-unit').val() <= 0) {
            return displayErrorSelection('meter', 'unit', 'Please select a unit');
        }
        
        return true;
    });
	
	$('#meter-selection-unit, #meter-selection-meter').change(function() {
		$('#meter-post-form').fadeOut();
		setTimeout(function() {
			$('#meter-post-form').remove();
		}, 500);	
	});

	$('#meter_isPrepaid').click(function() {
		if ($(this).is(':checked')) {
			$('#meter_isInternalMeter').prop('disabled', false);
			$('#meter_customer').prop('disabled', false);
		} else {
			$('#meter_isInternalMeter').prop('disabled', true);
			$('#meter_customer').prop('disabled', true);
		}
	});
	
	$('#meter_isInternalMeter').click(function(){
		if($(this).is(':checked')){			
			$('#meter_InternalPrepaidMeterNumber').prop('disabled', false);
		}
		else{
			$('#meter_InternalPrepaidMeterNumber').prop('disabled', true);
		}
	});
	
	$('#meter_InternalPrepaidMeterNumber').change(function(){
		var InternalPrepaidMeterNumber = $(this).val().trim();
		$('input[name="Real_meter_InternalPrepaidMeterNumber"]').val(InternalPrepaidMeterNumber);
	});
	
	$('#meter-save-button').click(function() {
		// validate meter
		var required_input = [
		    'numberBK',
			'utility_type', 
			'type', 
			'start_date'
		];
		var len = required_input.length,
		input_field = '',
		hasErrors = false;
		
		for (var i = 0; i < len; i++) {
			input_field = '#meter_' + required_input[i];
			if ($.trim($(input_field).val()) <= 0 
					|| !$.trim($(input_field).val())) {
				highlightRequiredInput(input_field);
				hasErrors = true;
			}
		}
		
		if ($('#meter_isPrepaid').is(':checked') && !$('#meter_isInternalMeter').is(':checked') && $('#meter_customer').val() <= 0) {
			highlightRequiredInput('#meter_customer');
			hasErrors = true;
		} else {
			$('#meter_customer').removeClass('error-input');
		}
			
		if (hasErrors) {
			displayOnsubmitErorMessage('Highlighted fields requires input');
			return false;
		}
		
		// check occupancy date and vacancy date
		var start_date_unix = new Date($('#meter_start_date').val()).getTime();
		var replacement_date_unix = new Date($('#meter_replacement_date').val()).getTime();
		
		if (replacement_date_unix < start_date_unix) {
			highlightRequiredInput('#meter_replacement_date');
			displayOnsubmitErorMessage('Replacement date should be greater than the Start date');
			return false;
		} else {
			$('#meter_replacement_date').removeClass('error-input');
		}
		
		return true;
	});
	
	$('#meter_utility_type').change(function() {
		var utility_typePK = this.value,
		unitPK = $('#meter-selection-unit').val(),
        buildingPK = $('#meter-selection-building').val(),
		meterPK = $('#meter-selection-meter').val();
        var data = { 
    		action: 'getReplacementMeters', 
    		unitPK: unitPK, buildingPK: buildingPK, 
    		utility_typePK: utility_typePK,
    		meterPK: meterPK
        };
        data = 'data=' + JSON.stringify(data);
        $('#meter-replacement').html('<option value="0">Please choose a meter</option>');
        
        
        $.post(CONST.AJAX_URL + '/meter.php', data).done(function(result) { 
			if (result) {    
				var meter_list = $.parseJSON(result);
				var len = meter_list.length;
                for (var i = 0; i < len; i++) {
                   $('#meter-replacement').append('<option value="' + meter_list[i].meterPK + '">' + meter_list[i].meterNo + '</option>'); 
                }
                
                if (len > 0) { // hide please selected
                	$('#meter-replacement > option:first-child').hide();
                } 
            }
        });
	}); // end of Meter
	
	/*------- Account Detail ------------------------------*/
	$('#customer-selection-tenant1').change(function(){
		var customerNumberBK = $("#customer-selection-tenant1 option:selected").text().trim();
		$('input[name="tenant_no"]').val(customerNumberBK);
	});
	
	/*------- Invoice Detail ------------------------------*/
	$('#customer-selection-tenant').change(function(){
		var buildingPK = $('#customer-selection-building').val();
		var unitPK = $('#customer-selection-unit').val();
		var customerNumberBK = $('#customer-selection-tenant > option:checked').text().trim();
		$('input[name="selected_invoice"]').val(customerNumberBK);
		
		if(customerNumberBK > 0){
			var data = { action: 'getInvoice', unitPK: unitPK, buildingPK: buildingPK, customerNumberBK:customerNumberBK };
			data = 'data=' + JSON.stringify(data);
			$.post(CONST.AJAX_URL + '/invoice.php', data).done(function(result) { 
				var invoice_list = $.parseJSON(result);
				var len = invoice_list.length;
				if (result) {    
					for (var i = 0; i < len; i++) {
					   $('#customer-selection-invoice').append('<option value="' + invoice_list[i].InvoiceNumber + '">' + invoice_list[i].InvoiceNumber + '</option>'); 
					}
					
					if (len > 0) { // hide please selected
						$('#customer-selection-invoice > option:first-child').hide();
					}
				}
			});
		}
	});
		
	
	/*------- System Administration Company ---------------*/
	$('#company-view-submit').click(function() {
        if ($('#company-selection-company').val() <= 0) {
            return displayErrorSelection('company', 'company', 'Please select a company');
        }
        return true;
    });
	
	/*------- System Administration Business Function ---------------*/
	$('#business-function-view-submit').click(function() {
        if ($('#business-function-selection-business-function').val() <= 0) {
            return displayErrorSelection('business-function', 'business-function', 'Please select a business function');
        }
        return true;
    });
	
	/*------- System Administration User ---------------*/
	$('#user-view-submit').click(function() {
        if ($('#user-selection-user').val() <= 0) {
            return displayErrorSelection('user', 'user', 'Please select a user');
        }
        return true;
    });
	
	// UI
	// allow only decimal
	$(this).on('keydown', '.input-decimal', function(e) {  
        var chrCode = (e.which) ? e.which : event.keyCode;
		if (chrCode === 190 && $.trim($(this).val()).indexOf('.') !== -1) { return false; }	
		if (chrCode === 110 && $.trim($(this).val()).indexOf('.') !== -1) { return false; }	
		return (chrCode > 47 && chrCode < 58) || 
			 (chrCode > 95 && chrCode < 106) ||
			 (chrCode > 36 && chrCode < 41) ||
			 chrCode === 190 ||
			 chrCode === 110 ||
			 chrCode === 8  ||
			 chrCode === 9  ||
			 chrCode === 45 ||
			 chrCode === 46 ? true: false;
    });
	
	// allow only integer
	$(this).on('keydown', '.input-integer', function(e) {  
        var chrCode = (e.which) ? e.which : event.keyCode;
		if (chrCode === 190 && $.trim($(this).val()).indexOf('.') !== -1) { return false; }		
		return (chrCode > 47 && chrCode < 58) || 
			 (chrCode > 95 && chrCode < 106) ||
			 (chrCode > 36 && chrCode < 41) ||
			 chrCode === 8  ||
			 chrCode === 9  ||
			 chrCode === 45 ||
			 chrCode === 46 ? true: false;
    });
	
	$(window).resize(function() {
		moveModal('rates-modal-new-period');
	});
	
	$(this).on('mousedown', '.error-input', function(e) {  
        $(this).removeClass('error-input');
        $('.submit-error-box').fadeOut();
    });
	
	$(this).on('mousedown', '.ratescale-from, #sysad-parameters input[type="text"], .csv_path', function(e) {  
		return false;
    });
	
	$(this).on('keydown', '.ratescale-from, #sysad-parameters input[type="text"], .csv_path', function(e) {  
		var chrCode = (e.which) ? e.which : event.keyCode;
		return chrCode === 9  ? true: false;
    });
	
	/*-------- Remove Please select ... sentence in the select elements if select element has many childrens --------*/		
	if ($('select').length > 0){
		$('select > option:first-child').hide();
	}	
	
	/*-------- Planning - Billing Period ------*/
	
	/* $('.billing_sequence').keyup(function() {
		$('#termination_sequence_' + $(this).prop('id').split('_').pop()).val($(this).val());
	});
	
	$('.termination_sequence').keyup(function() {
		$('#billing_sequence_' + $(this).prop('id').split('_').pop()).val($(this).val());
	});
	
	$('.billing_team').change(function() {
		$('#termination_team_' + $(this).prop('id').split('_').pop()).val($(this).val());
	});
	
	$('.termination_team').change(function() {
		$('#billing_team_' + $(this).prop('id').split('_').pop()).val($(this).val());
	});
	
	$('.billing_reading_day').change(function() {
		$('#termination_reading_day_' + $(this).prop('id').split('_').pop()).val($(this).val());
	});
	
	$('.termination_reading_day').change(function() {
		$('#billing_reading_day_' + $(this).prop('id').split('_').pop()).val($(this).val());
	}); */
	
	/*--- Billing Modal ---*/
	$('input[name="Invoice"]').click(function(){
		var top = Math.max($(window).height() - $('.modal-container').outerHeight(), 0) / 2;
		var left = Math.max($(window).width() - $('.modal-container').outerWidth(), 0) / 2;
		$('.overlay').fadeTo('fast', 0.5);
		$('.modal-container')
			.css({
				top: top + $(window).scrollTop(),
				left: left + $(window).scrollLeft()
			}).fadeIn('slow');
	});
	
	$('.modal-header > .close > a, input[name="Cancel-Invoice"]').click(function(e){
		e.preventDefault();
		$('.overlay').fadeOut('fast');
		$('.modal-container').fadeOut('fast');
	});
	
	setCheckboxOnlickLister();
    directToSpecificTab('planning');
    directToSpecificTab('parameters');
    
    if ($('.fancybox').length > 0) {
	    $('.fancybox').fancybox({
			helpers: {
				title : {
					type : 'outside'
				},
				overlay : {
					speedOut : 0
				}
			}
		});
    }
    
    $('.reasonability-readings').focus(function() {
    	this.value = '';
    }).blur(function() {
		if (!$.trim(this.value)) {
			this.value = this.defaultValue;	
		}
    });
    
    /** Planning */
    var billing_period_list = new Array();
    var termination_period_list = new Array();
    $('#planning-save-button').click(function() {
    	var today = new Date();
    	today = today.getFullYear() + '-' + pad(today.getMonth() + 1) + '-' + pad(today.getDate());
    	var current_date = new Date(today).getTime();
    	var billingHasNoError = true;
    	var terminationHasNoError = true;
    	var team_list = $('select[name="billing_team_PK[]"]');
    	var reading_day_list = $('input[name="billing_reading_day[]"]');
    	var sequence_list = $('input[name="billing_sequence[]"]');
    	
    	if (team_list.length === reading_day_list.length && reading_day_list.length === sequence_list.length) {
    		var len = team_list.length;
    		var billing_period = null;
    		
    		for (var i = 0; i < len; i++) {
    			billing_period = billing_period_list[i];
    			
    			if (billing_period.teamPK != team_list[i].value || billing_period.readingDay != reading_day_list[i].value ||
    					billing_period.sequenceNo != sequence_list[i].value) { // if either of these values are not equal, it means that there is a change in the field value
    	    		
    	    		var reading_date = new Date(reading_day_list[i].value).getTime();

    	    		if (reading_date < current_date) { // if the current date is lesser than the reading date, then it is an error
    	    			highlightRequiredInput($(reading_day_list[i]));
    	    			billingHasNoError = false;
    	    		}
    			}
    		}
    		
    	} else {
    		alert('An error occured in the planning page.');
    	}
    	
    	team_list = $('select[name="termination_team_PK[]"]');
    	reading_day_list = $('input[name="termination_reading_day[]"]');
    	
    	if (team_list.length === reading_day_list.length && reading_day_list.length) {
    		var len = team_list.length;
    		var termination_period = null;
    		
    		for (var i = 0; i < len; i++) {
    			termination_period = termination_period_list[i];
    			
    			if (termination_period.teamPK != team_list[i].value || termination_period.readingDay != reading_day_list[i].value) { // if either of these values are not equal, it means that there is a change in the field value
    	    		var reading_date = new Date(reading_day_list[i].value).getTime();

    	    		if (reading_date < current_date) { // if the current date is lesser than the reading date, then it is an error
    	    			highlightRequiredInput($(reading_day_list[i]));
    	    			terminationHasNoError = false;
    	    		}
    			}
    		}
    		
    	} else {
    		alert('An error occured in the planning page.');
    	}
   
    	
    	if (!billingHasNoError || !terminationHasNoError) {
    		if (!billingHasNoError) {
    			$('a[href^="#tab-planning"]').click();
    		} else if (!terminationHasNoError) {
    			$('a[href^="#tab-terminations"]').click();
    		}

    		displayOnsubmitErorMessage('Can not change history, please select a date today or a future date.');
    	} 
    	
    	return billingHasNoError && terminationHasNoError;
    });
    
    $('.planning-period-selection').change(function() {
    	removeErrorInput('#billing_period_month');
    	removeErrorInput('#billing_period_year');
    	$('#billing-selection-error-box').fadeOut();
    });
    
    if ($('select[name="billing_team_PK[]"]').length > 0 && $('input[name="billing_reading_day[]"]').length > 0 &&
    		$('input[name="billing_sequence[]"]').length > 0) {
    	var team_list = $('select[name="billing_team_PK[]"]');
    	var reading_day_list = $('input[name="billing_reading_day[]"]');
    	var sequence_list = $('input[name="billing_sequence[]"]');

    	if (team_list.length === reading_day_list.length && reading_day_list.length === sequence_list.length) {
    		var len = team_list.length;
    		for (var i = 0; i < len; i++) {
    			billing_period_list.push(new BuildingBillingPeriod(
					team_list[i].value,
					reading_day_list[i].value,
					sequence_list[i].value
    			));
    		}
    		
    		function BuildingBillingPeriod(teamPK, readingDay, sequenceNo) { // BillingPeriod Object
    	    	this.teamPK = teamPK;
    	    	this.readingDay = readingDay;
    	    	this.sequenceNo = sequenceNo;
    	    };
    	} else {
    		alert('An error occured in the planning page.');
    	}
    }
    
    if ($('select[name="termination_team_PK[]"]').length > 0 && $('input[name="termination_reading_day[]"]').length > 0) {
    	var team_list = $('select[name="termination_team_PK[]"]');
    	var reading_day_list = $('input[name="termination_reading_day[]"]');

    	if (team_list.length === reading_day_list.length && reading_day_list.length) {
    		var len = team_list.length;
    		for (var i = 0; i < len; i++) {
    			termination_period_list.push(new BuildingBillingPeriod(
					team_list[i].value,
					reading_day_list[i].value
    			));
    		}
    		
    		function BuildingTerminationPeriod(teamPK, readingDay) { // BillingPeriod Object
    	    	this.teamPK = teamPK;
    	    	this.readingDay = readingDay;
    	    };
    	    
    	} else {
    		alert('An error occured in the planning page.');
    	}
    }
 	
}); // end of document ready function