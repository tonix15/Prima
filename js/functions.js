// JavaScript Document (1st one to load)
$(document).ready(function(e) { 	
    directToSpecificTab = function(tab_name) {
        var url = document.location.toString();
        if (url.indexOf(tab_name) !== -1) {
            var tab_menu_item = url.split('#');
            var tab_item = null;
            
            for (var i = 0; i < tab_menu_item.length; i++) {
                if (tab_menu_item[i].indexOf('tab') !== -1) {
                    tab_item = tab_menu_item[i].split('-');
                    tab_item[0] = tab_name;
                    tab_item = tab_item.join('-');
                    $('#' + tab_item).click();
                    break;
                }
            }
        }
    };
    
	/**
	* @descr function to display error message and highlight input field
	* @param type as string parameter type
	* @param input as string input field in parameter selection
	* @param errmsg as string error message
	*/
    displayErrorSelection = function(type, input, errmsg) {
    	$('#' + type + '-selection-error-box').fadeOut();
        $('#' + type + '-selection-error-box').fadeIn();
        $('#' + type + '-selection-error-box').html(errmsg);
        $('#' + type + '-selection-' + input).addClass('error-input');
        return false;
    };
    
    displayAddlineErrorBox = function(error_box) {
    	$(error_box).fadeOut();
    	$(error_box).fadeIn();
    };
    
    hideSelectionErrorBox = function(id) {
        var type = id.split('-')[0];
        $('#' + type + '-selection-error-box').fadeOut();
    };
    
    setCheckboxValues = function(checkboxes) {
        var values = new Array();
        var value_container = checkboxes.split('_');
        var ctr = 0;
        $('input[name^=' + checkboxes).each(function() {
            if ($(this).is(':checked')) {
                values.push(1);
            } else {
                values.push(0);
            }
        });
        
        value_container[value_container.length - 1] = 'values';
        value_container = value_container.join('_');

        $('input[name^=' + value_container).each(function() {
            $(this).val(values[ctr++]);
        });   
    };
    
    getContainerNameToAppend = function(elem) {
        elem = elem.id.split('-');
        elem[elem.length - 1] = 'content';
        return elem.join('-');
    };
    
    checkAppendValueDuplicate= function(add_items_container, elem) {
        var hasDuplicate = false, current_val, val_occurence;
		var input_list = '#' + add_items_container + ' ' + elem;
        // elements that must not duplicated 
        $(input_list).each(function() {
            current_val = $.trim(this.value);
            val_occurence = 0;
            $(input_list).each(function() {
                if (current_val.toUpperCase() === $.trim(this.value).toUpperCase()) {
                    ++val_occurence;	
					if (val_occurence > 1) {
						hightLightDuplicatedInput(input_list, this.value);
					}
                }
            });
            hasDuplicate = hasDuplicate || (val_occurence > 1 ? true:false); 
        });
        return hasDuplicate;
    };
	
	hightLightDuplicatedInput = function(input_list, val) {
		$(input_list).each(function() {
			if (val.toUpperCase() === $.trim(this.value).toUpperCase()) {
				$(this).addClass('error-input');
			}
        });
	};
    
    checkInputHasAllValues = function(add_items_container, elem) {
        var hasInput = true;
        $('#' + add_items_container + ' ' + elem).each(function() {
			if (elem === 'select') {
                hasInput = hasInput && (this.value >= 1 ? true:false);
            } else {
                hasInput = hasInput && (this.value ? true:false);
            }
			if (!hasInput) {
				highlightRequiredInput(this);
			}			
		});
        return hasInput;
    };
    
    getAddContainerName = function(prefix, tab) {
        return prefix + '-items-add-' + tab.id.split('-').pop();
    };
    
    getArrayElementValues = function(element_type, elem_name) {
        var temp = new Array();
        $(element_type + '[name^=' + elem_name + ']').each(function() {
           temp.push(this.value); 
        });
        return temp;
    };

    hasChosen = function(value) {
        return value > 0;
    };
    
	setCheckboxOnlickLister = function() {
		var types = ['utility_isActive_temp', 'title_isActive_temp', 'language_isActive_temp',
			'contact_isActive_temp', 'meter_isActive_temp', 'allocation_isServiceMeter_temp',
			'allocation_isCommonProperty_temp', 'allocation_isBulkWater_temp', 'reason_isActive_temp',
			'allocation_isActive_temp', 'building_isActive_temp', 'team_isActive_temp', 'rate_isActive_temp',
			'fixed_rate_retail_isVat_temp', 'fixed_rate_bulk_isVat_temp', 'fixed_fee_retail_isVat_temp', 
			'fixed_fee_bulk_isVat_temp', 'utility_isMetered_temp', 'IsSendNotificaton_isActive_temp'
		];
		var len = types.length;

		for (var i = 0; i < len; i++) {
			setCheckBoxOnclickListenerWithHiddenValues(types[i]);
		}
	};
	
	highlightRequiredInput = function(input_field) {
		$(input_field).addClass('error-input');
	};
	
	removeAllErrorInput = function(add_items_container, elem) {
        $('#' + add_items_container + ' ' + elem).each(function() {
			removeErrorInput(this);
        });
    };
	removeErrorInput = function(input_field) {
		$(input_field).removeClass('error-input');
	};
	
	displayOnsubmitErorMessage = function(errmsg) {
		$('.submit-error-box').html(errmsg).fadeOut();
		$('.submit-error-box').html(errmsg).fadeIn();
	};
	
	setCheckBoxOnclickListenerWithHiddenValues = function(input_name) {
		$(document).on('click', 'input[name^=' + input_name + ']', function(){
			setCheckboxValues(input_name);
		});    
    };	
	
	isCodeHasChanged = function(original_code, changed_code) {
		// alert(original_code + original_code);
		return original_code.toUpperCase() != changed_code.toUpperCase();
	};
	
	moveModal = function(elem_id) {
		var browser_size = {
			width: window.innerWidth || document.body.clientWidth,
			height: window.innerHeight || document.body.clientHeight
		};
		
		var screen_size = {
			width: screen.width,
			height: screen.height
		};
		
		if (browser_size.width <= (screen_size.width / 2)) {
			$('#' + elem_id).css('left', '14%');
		} else {
			$('#' + elem_id).css('left', '35%');
		}
	};
	
	/** Function count the number of elements inside a given element;
	 * @param {element} the elements that contains sub elements;
	 */
	childrenCount = function(elem){
		var selector = $(elem);
		return selector.children().size();
	};
	
	/** Function count the occurrences of substring in a string;
	 * @param {String} string   Required. The string;
	 * @param {String} subString    Required. The string to search for;
	 * @param {Boolean} allowOverlapping    Optional. Default: false;
	 */
	occurrences = function(string, subString, allowOverlapping){
		string+=""; 
		subString+="";
		if(subString.length<=0){ return string.length+1; }

		var n=0, pos=0;
		var step=(allowOverlapping)?(1):(subString.length);

		while(true){
			pos=string.indexOf(subString,pos);
			if(pos>=0){ 
				n++; 
				pos+=step; 
			} 
			else{ break; }
		}
		return(n);
	};
	

    pad = function (n) {
        return (n < 10) ? ("0" + n) : n;
    };
	
/*$(window).scroll(function()
{
    if($(window).scrollTop() == $(document).height() - $(window).height())
    {
        $('div#loadmoreajaxloader').show();
        $.ajax({
        url: "loadmore.php",
        success: function(html)
        {
            if(html)
            {
                $("#postswrapper").append(html);
                $('div#loadmoreajaxloader').hide();
            }else
            {
                $('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
            }
        }
        });
    }
});*/

}); // end of document ready function

