<?php
$page_name = 'RATES';

require_once '../init.php';

//User
if(!$User->isUserLogin()){
	header('Location:' . DOMAIN_NAME . '/index.php');
	exit();
}
else{
	if(!$Session->check('user_company_selection_key')){
		$Session->write('select_company', '<strong>Please Select a Company</strong>');	
		header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
		exit();
	}
	else if($Session->read('user_company_selection_key') <=0 ){
		$Session->write('select_company', '<h2>Please Select a Company</h2>');
		header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
		exit();
	}	
}

$userCredentials = $User->getUserCredentials();
$userPK = $userCredentials['UserPk'];

require DOCROOT . '/template/header.php';

//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level = $dbhandler->getRestrictionLevel($page_name);

// Provider
$Provider = new Provider($dbh);
$provider_list = null;
$providerPK = 0;

// Rate
$rate_list = null;
$rate_data = null;
$ratePK = 0;
$effective_date = date('Y-m-d');
$business_code = '';
$rate_name = '';
$rate_utilityPK = 0;
$rate_providerPK = 0;
$rate_descr = '';
$rate_isVatApplicable = 'checked';
$rate_isActive = 'checked';
$rate_start_date = '';
$rate_starting_date = '';
$rate_end_date = '';
$rate_effective_date = '';
$rate_isUnitised = '';


/* Rate Scale */
$rate_scale_data = null;

// Scale Retail
$rate_scale_retail_from = null;
$rate_scale_retail_to = null;
$rate_scale_retail_rate = null;
$rate_scale_retail_percentage = null;

// Scale Bulk
$rate_scale_bulk_from = null;
$rate_scale_bulk_to = null;
$rate_scale_bulk_rate = null;
$rate_scale_bulk_percentage = null;
/* End of Rate Scale */

/* Rate Fixed Rate */
$rate_fixedrate_data = null;

// Fixed Rate Retail
$fixed_rate_retail_descr = null;
$fixed_rate_retail_rate = null;
$fixed_rate_retail_percentage = null;
$fixed_rate_retail_isVat = null;       

// Fixed Rate Bulk
$fixed_rate_bulk_descr = null;
$fixed_rate_bulk_rate = null;
$fixed_rate_bulk_percentage = null;
$fixed_rate_bulk_isVat = null;
/* End of Rate Fixed Rate */

/* Rate Fixed Fee */
$rate_fixedfee_data = null;

// Fixed Fee Retail
$fixed_fee_retail_descr = null;
$fixed_fee_retail_fee = null;
$fixed_fee_retail_isVat = null;       

// Fixed Fee Bulk
$fixed_fee_bulk_descr = null;
$fixed_fee_bulk_fee = null;
$fixed_fee_bulk_isVat = null;

/* End of Rate Fixed Fee */

// Utility
$Utility = new UtilityType($dbh);
$utility_list = null;

// UI
$view_class = 'hidden';
$save_name = '';
$isRateSet = false;
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_GET['View']) || isset($_GET['Create'])) {
    $utility_list = $Utility->getUtilityType(array($userPK, 0));
}

if (isset($_GET['View'], $_GET['choose_rate'])) {
    $save_name = 'Update';
    $view_class = 'show';
    
    $providerPK = $_GET['choose_provider'];
    $ratePK = $_GET['choose_rate'];
    $effective_date = $_GET['choose_date'];
    $rate_data = $dbhandler->getRate(array($userPK, $ratePK, 0), true);
    
    // Rate details
    $business_code = $rate_data['NumberBk'];
    $rate_name = $rate_data['Name'];
    $rate_utilityPK = $rate_data['UtilityTypeFk'];
    $rate_providerPK = $rate_data['ProviderFk'];
    $rate_descr = $rate_data['BillingDescription'];
    $rate_isVatApplicable = $rate_data['IsVATApplicable'] == 1 ? 'checked':'';
    $rate_isActive = $rate_data['IsActive'] == 1 ? 'checked':'';
    $rate_isUnitised = $rate_data['IsUnitised'] == 1 ? 'checked':'';
    
    // Rate Scale
    $rate_scale_data = $dbhandler->getScale(array($userPK, 0, $ratePK, $effective_date));

    // Rate Fixed Rate
    $rate_fixedrate_data = $dbhandler->getFixedRate(array($userPK, 0, $ratePK, $effective_date));
    
    // Rate Fixed Fee
    $rate_fixedfee_data = $dbhandler->getFixedFee(array($userPK, 0, $ratePK, $effective_date));
	
	foreach ($rate_scale_data as $scale) {
		if (!empty($scale['StartDate'])) {
			$rate_starting_date = $scale['StartDate'];
			break;
		}
	}
	
	if (empty($rate_starting_date)) {
		foreach ($rate_fixedrate_data as $fixedrate) {
			if (!empty($fixedrate['StartDate'])) {
				$rate_starting_date = $fixedrate['StartDate'];
				break;
			}
		}
	}
	
	if (empty($rate_starting_date)) {
		foreach ($rate_fixedfee_data as $fixedfee) {
			if (!empty($fixedfee['StartDate'])) {
				$rate_starting_date = $fixedfee['StartDate'];
				break;
			}
		}
	}
	
    
} else if (isset($_GET['Create'])) {
    $save_name = 'Create';
    $view_class = 'show';  
}

if (isset($_POST['Create']) || isset($_POST['Update'])) {
    $isRateSet = isset(
        $_POST['business_code'], 
        $_POST['rate_name'], 
        $_POST['choose_detail_utility_types'],
        $_POST['choose_detail_provider'],
        $_POST['rate_descr']
     );
    
    $isRateSet = $isRateSet && $_POST['choose_detail_utility_types'] > 0;
    $isRateSet = $isRateSet && $_POST['choose_detail_provider'] > 0;
    
    if ($isRateSet) {
        // Pass the posted variables
        // Rate
        $business_code = $_POST['business_code'];
        $rate_name = $_POST['rate_name'];
        $rate_utilityPK = $_POST['choose_detail_utility_types'];
        $rate_providerPK = $_POST['choose_detail_provider'];
        $rate_descr = $_POST['rate_descr'];
        $rate_isVatApplicable = isset($_POST['rate_isVatApplicable']) ? 1 : 0;
        $rate_isActive = isset($_POST['rate_isActive']) ? 1 : 0;
        $rate_isUnitised = isset($_POST['rate_isUnitised']) ? 1 : 0;
        $rate_start_date = !empty($_POST['rate_start_date']) ? $_POST['rate_start_date'] : '01-01-1900';
        $rate_end_date = !empty($_POST['rate_start_date']) ? '2999-12-31' : '2999-12-31';
        $rate_effective_date = !empty($_POST['rate_start_date']) ? $_POST['rate_start_date'] : '01-01-1900';
        // Scale Retail
        if (isset($_POST['rate_scale_retail_from'], $_POST['rate_scale_retail_to'],
            $_POST['rate_scale_retail_rate'], $_POST['rate_scale_retail_percentage'])) {
            $rate_scale_retail_from = $_POST['rate_scale_retail_from'];
            $rate_scale_retail_to = $_POST['rate_scale_retail_to'];
            $rate_scale_retail_rate = $_POST['rate_scale_retail_rate'];
            $rate_scale_retail_percentage = $_POST['rate_scale_retail_percentage'];   
        }

        // Scale Bulk
        if (isset($_POST['rate_scale_bulk_from'], $_POST['rate_scale_bulk_to'], 
            $_POST['rate_scale_bulk_rate'], $_POST['rate_scale_bulk_percentage'])) {
            $rate_scale_bulk_from = $_POST['rate_scale_bulk_from'];
            $rate_scale_bulk_to = $_POST['rate_scale_bulk_to'];
            $rate_scale_bulk_rate = $_POST['rate_scale_bulk_rate'];
            $rate_scale_bulk_percentage = $_POST['rate_scale_bulk_percentage'];
        }
            
        // Fixed Rate Retail
        if (isset($_POST['fixed_rate_retail_descr'], $_POST['fixed_rate_retail_rate'],
            $_POST['fixed_rate_retail_percentage'], $_POST['fixed_rate_retail_isVat_values'])) {
            $fixed_rate_retail_descr = $_POST['fixed_rate_retail_descr'];
            $fixed_rate_retail_rate = $_POST['fixed_rate_retail_rate'];
            $fixed_rate_retail_percentage = $_POST['fixed_rate_retail_percentage'];
            $fixed_rate_retail_isVat = $_POST['fixed_rate_retail_isVat_values'];
        }
               
        // Fixed Rate Bulk
        if (isset($_POST['fixed_rate_bulk_descr'], $_POST['fixed_rate_bulk_rate'], 
            $_POST['fixed_rate_bulk_percentage'], $_POST['fixed_rate_bulk_isVat_values'])) {
            $fixed_rate_bulk_descr = $_POST['fixed_rate_bulk_descr'];
            $fixed_rate_bulk_rate = $_POST['fixed_rate_bulk_rate'];
            $fixed_rate_bulk_percentage = $_POST['fixed_rate_bulk_percentage'];
            $fixed_rate_bulk_isVat = $_POST['fixed_rate_bulk_isVat_values'];
        }
        
        // Fixed Fee Retail
        if (isset($_POST['fixed_fee_retail_descr'], $_POST['fixed_fee_retail_fee'],
            $_POST['fixed_fee_retail_isVat_values'])) {   
            $fixed_fee_retail_descr = $_POST['fixed_fee_retail_descr'];
            $fixed_fee_retail_fee = $_POST['fixed_fee_retail_fee'];
            $fixed_fee_retail_isVat = $_POST['fixed_fee_retail_isVat_values'];
        }

        // Fixed Fee Bulk
        if (isset($_POST['fixed_fee_bulk_descr'], $_POST['fixed_fee_bulk_fee'],
            $_POST['fixed_fee_bulk_isVat_values'])) {
            $fixed_fee_bulk_descr = $_POST['fixed_fee_bulk_descr'];
            $fixed_fee_bulk_fee = $_POST['fixed_fee_bulk_fee'];
            $fixed_fee_bulk_isVat = $_POST['fixed_fee_bulk_isVat_values'];
        } 
    }
}

if (isset($_POST['Create'])) {
    $view_class = 'hidden';
    
    // Rate
    if ($isRateSet) {
        $lastInserted_ratePK = $dbhandler->createRate(
            array(
                $userPK,
                0, // new rate
                $business_code,
                $rate_name,
                $rate_utilityPK,
                $rate_isVatApplicable,
                $rate_descr,
                $rate_providerPK,
                $rate_isActive,
            	$rate_isUnitised
            )
        ); 
        $len = count($rate_scale_retail_from);
        // create scale retail
        for ($i = 0; $i < $len; $i++) {
            if (!empty($rate_scale_retail_to[$i]) && 
				!empty($rate_scale_retail_rate[$i]) && 
				!empty($rate_scale_retail_percentage[$i])) {
				$dbhandler->createScale(
					array(
						$userPK,
						0,
						$lastInserted_ratePK,
						$rate_scale_retail_from[$i],
						$rate_scale_retail_to[$i],
						$rate_scale_retail_rate[$i],
						$rate_scale_retail_percentage[$i],
						1, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($rate_scale_bulk_from);
        // create scale bulk
        for ($i = 0; $i < $len; $i++) {
			if (!empty($rate_scale_bulk_to[$i]) && 
				!empty($rate_scale_bulk_rate[$i]) && 
				!empty($rate_scale_bulk_percentage[$i])) {
				$dbhandler->createScale(
					array(
						$userPK,
						0,
						$lastInserted_ratePK,
						$rate_scale_bulk_from[$i],
						$rate_scale_bulk_to[$i],
						$rate_scale_bulk_rate[$i],
						$rate_scale_bulk_percentage[$i],
						0, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_rate_retail_descr);
        // create fixed rate retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_rate_retail_rate[$i]) && 
				!empty($fixed_rate_retail_descr[$i]) && 
				!empty($fixed_rate_retail_percentage[$i]) && 
				!empty($lastInserted_ratePK)) {
				$dbhandler->createFixedRate(
					array(
						$userPK,
						0,
						$lastInserted_ratePK,
						$fixed_rate_retail_isVat[$i],
						$fixed_rate_retail_rate[$i],
						$fixed_rate_retail_percentage[$i],
						$fixed_rate_retail_descr[$i],
						1, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_rate_bulk_descr);
        // create fixed rate bulk
        for ($i = 0; $i < $len; $i++) {
            if (!empty($fixed_rate_bulk_rate[$i]) && 
				!empty($fixed_rate_bulk_descr[$i]) && 
				!empty($fixed_rate_bulk_percentage[$i]) && 
				!empty($lastInserted_ratePK)) {
				$dbhandler->createFixedRate(
					array(
						$userPK,
						0,
						$lastInserted_ratePK,
						$fixed_rate_bulk_isVat[$i],
						$fixed_rate_bulk_rate[$i],
						$fixed_rate_bulk_percentage[$i],
						$fixed_rate_bulk_descr[$i],
						0, // isBulk
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_fee_retail_descr);
        // create fixed fee retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_fee_retail_descr[$i]) && 
				!empty($fixed_fee_retail_fee[$i]) && 
				!empty($lastInserted_ratePK)) {
				$dbhandler->createFixedFee(
					array(
						$userPK,
						0,
						$lastInserted_ratePK,
						$fixed_fee_retail_isVat[$i],
						$fixed_fee_retail_fee[$i],
						$fixed_fee_retail_descr[$i],
						1, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }
        
        $len = count($fixed_fee_bulk_descr);
        // create fixed fee retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_fee_bulk_descr[$i]) && 
				!empty($fixed_fee_bulk_fee[$i]) && 
				!empty($lastInserted_ratePK)) {
				$dbhandler->createFixedFee(
					array(
						$userPK,
						0,
						$lastInserted_ratePK,
						$fixed_fee_bulk_isVat[$i],
						$fixed_fee_bulk_fee[$i],
						$fixed_fee_bulk_descr[$i],
						0, // isBulk
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }
    } 
    
    if (!empty($lastInserted_ratePK) && $isRateSet) {
		$ratePK = $lastInserted_ratePK;
        $submit_result = 'success';
		$errmsg = 'Record successfully created!';
    } else {
        $submit_result = 'error';
		$errmsg = 'Error occured when updating the record!';
    } 
    $error_class = 'show';
} else if (isset($_POST['Update'])) {
    $view_class = 'hidden';
    
    $rate_scale_retailPKs = !empty($_POST['rate_scale_retailPKs']) ? $_POST['rate_scale_retailPKs']: null;
    $rate_scale_bulkPKs = !empty($_POST['rate_scale_bulkPKs']) ? $_POST['rate_scale_bulkPKs'] : null;
    
    $fixed_rate_retailPKs = !empty($_POST['fixed_rate_retailPKs']) ? $_POST['fixed_rate_retailPKs'] : null;
    $fixed_rate_bulkPKs = !empty($_POST['fixed_rate_bulkPKs']) ? $_POST['fixed_rate_bulkPKs'] : null;
    
    $fixed_fee_retailPKs = !empty($_POST['fixed_fee_retailPKs']) ? $_POST['fixed_fee_retailPKs'] : null;
    $fixed_fee_bulkPKs = !empty($_POST['fixed_fee_bulkPKs']) ? $_POST['fixed_fee_bulkPKs'] : null;
    
        
    if ($isRateSet) {
        // Rate
        $dbhandler->updateRate(array(
	        $userPK,
	        $ratePK, // ratePK
	        $business_code,
	        $rate_name,
	        $rate_utilityPK,
	        $rate_isVatApplicable,
	        $rate_descr,
	        $rate_providerPK,
	        $rate_isActive,
	        $rate_isUnitised
        )); 
        
        $len = count($rate_scale_retail_from);
        // create scale retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($rate_scale_retail_to[$i]) && 
				!empty($rate_scale_retail_rate[$i]) && 
				!empty($rate_scale_retail_percentage[$i])) {
				$newPK = isset($rate_scale_retailPKs[$i]) ? $rate_scale_retailPKs[$i] : 0;
				$dbhandler->createScale(array(
					$userPK,
					$newPK, 
					$ratePK, // ratePK
					$rate_scale_retail_from[$i],
					$rate_scale_retail_to[$i],
					$rate_scale_retail_rate[$i],
					$rate_scale_retail_percentage[$i],
					1, // isRetail
					$rate_start_date,
					$rate_end_date 
				));
			}
        }

        $len = count($rate_scale_bulk_from);
        // create scale bulk
        for ($i = 0; $i < $len; $i++) {
			if (!empty($rate_scale_bulk_to[$i]) && 
				!empty($rate_scale_bulk_rate[$i]) && 
				!empty($rate_scale_bulk_percentage[$i])) {
				$newPK = isset($rate_scale_bulkPKs[$i]) ? $rate_scale_bulkPKs[$i] : 0;
				$dbhandler->createScale(
					array(
						$userPK,
						$newPK,
						$ratePK,
						$rate_scale_bulk_from[$i],
						$rate_scale_bulk_to[$i],
						$rate_scale_bulk_rate[$i],
						$rate_scale_bulk_percentage[$i],
						0, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_rate_retail_descr);
        // create fixed rate retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_rate_retail_rate[$i]) && 
				!empty($fixed_rate_retail_descr[$i]) && 
				!empty($fixed_rate_retail_percentage[$i])) {
				$newPK = isset($fixed_rate_retailPKs[$i]) ? $fixed_rate_retailPKs[$i] : 0;
				$dbhandler->createFixedRate(
					array(
						$userPK,
						$newPK,
						$ratePK,
						$fixed_rate_retail_isVat[$i],
						$fixed_rate_retail_rate[$i],
						$fixed_rate_retail_percentage[$i],
						$fixed_rate_retail_descr[$i],
						1, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_rate_bulk_descr);
        // create fixed rate retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_rate_bulk_rate[$i]) && 
				!empty($fixed_rate_bulk_descr[$i]) && 
				!empty($fixed_rate_bulk_percentage[$i])) {
				$newPK = isset($fixed_rate_bulkPKs[$i]) ? $fixed_rate_bulkPKs[$i] : 0;
				$dbhandler->createFixedRate(
					array(
						$userPK,
						$newPK,
						$ratePK,
						$fixed_rate_bulk_isVat[$i],
						$fixed_rate_bulk_rate[$i],
						$fixed_rate_bulk_percentage[$i],
						$fixed_rate_bulk_descr[$i],
						0, // isBulk
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_fee_retail_descr);
        // create fixed fee retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_fee_retail_descr[$i]) && 
				!empty($fixed_fee_retail_fee[$i])) {
				$newPK = isset($fixed_fee_retailPKs[$i]) ? $fixed_fee_retailPKs[$i] : 0;
				$dbhandler->createFixedFee(
					array(
						$userPK,
						$newPK,
						$ratePK,
						$fixed_fee_retail_isVat[$i],
						$fixed_fee_retail_fee[$i],
						$fixed_fee_retail_descr[$i],
						1, // isRetail
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }

        $len = count($fixed_fee_bulk_descr);
        // create fixed fee retail
        for ($i = 0; $i < $len; $i++) {
			if (!empty($fixed_fee_bulk_descr[$i]) && 
				!empty($fixed_fee_bulk_fee[$i])) {
				$newPK = isset($fixed_fee_bulkPKs[$i]) ? $fixed_fee_bulkPKs[$i] : 0;
				$dbhandler->createFixedFee(
					array(
						$userPK,
						$newPK,
						$ratePK,
						$fixed_fee_bulk_isVat[$i],
						$fixed_fee_bulk_fee[$i],
						$fixed_fee_bulk_descr[$i],
						0, // isBulk
						$rate_start_date,
						$rate_end_date 
					)
				);
			}
        }
    }
    if ($isRateSet) {
        // $submit_result = 'success';
		// $errmsg = 'Record successfully updated!';
    } else {
        // $submit_result = 'error';
		// $errmsg = 'Error occurred when updating the record!';
    } 
    
    $submit_result = 'success';
    $errmsg = 'Record successfully updated!';
	$error_class = 'show';
} else if (isset($_POST['Cancel'])) {
    $view_class = 'hidden';
}

$provider_list  = $Provider->getProvider(array($userPK, 0));
$rate_list = $dbhandler->getRate(array($userPK, 0, $providerPK));

?>

<div class="sub-menu-title"><h1>Rate Master</h1></div>
<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<form method="get">
<div id="rate-selection" class="wrapper-fieldset-forms hover-cursor-pointer">
    <fieldset class="fieldset-forms">
        <legend>Rate Selection</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li><label>Provider code:</label></li>
            <li>
                <select id="rate-selection-provider" name="choose_provider" class="rate-selection-input">
                    <option value="0">Please select...</option>
                <?php foreach ($provider_list as $provider) { 
                    $selected = $provider['ProviderPk'] === $providerPK ? 'selected="' . $providerPK . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $provider['ProviderPk']; ?>"><?php echo $provider['NumberBk']; ?></option>
                <?php } ?>
                </select>
            </li>
            <li>Rate code:</li>
            <li>
                <select id="rate-selection-rate" name="choose_rate" class="selection-required-input">
                    <option value="0">Please select...</option>
                <?php foreach ($rate_list as $rate) { 
                    $selected = $rate['RatePk'] === $ratePK ? 'selected="' . $ratePK . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $rate['RatePk']; ?>"><?php echo $rate['NumberBk']; ?></option>
                <?php } ?>
                </select>
            </li>
            <li>Date:</li>
            <li><input type="date" id="rate-selection-date" name="choose_date" class="selection-required-input" value="<?php echo $effective_date; ?>"/></li>	
        </ul>
        <div class="selection-form-submit float-left">
            <input id="rate-selection-view" type="submit" value="View" name="View"/>
            <?php if($restriction_level){?>
				<input type="submit" value="Create" name="Create"/>   
			<?php }?>
        </div> 
        <div id="rate-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of rate selection -->
</form> <!-- end of get form -->

<?php if ($view_class === 'show') { ?>
<form method="post" class="<?php echo $view_class; ?>" id="rate-selection-form">
<div id="rate-detail" class="wrapper-fieldset-forms hover-cursor-pointer">
    <fieldset class="fieldset-forms">
        <legend>Rate Detail</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li><label>Rate Code:</label></li>
            <li><input type="text" id="rate_code" name="business_code" value="<?php echo $business_code; ?>" maxlength="20" /></li>
            <li>Name:</li>
            <li><input type="text" id="rate_name" name="rate_name" value="<?php echo $rate_name; ?>" maxlength="50" /></li>
            <li>Utility:</li>
            <li>
                <select id="rate_utility" name="choose_detail_utility_types">
                    <option value="0">Please select...</option>
                <?php foreach ($utility_list as $utility) { 
                    $selected = $utility['UtilityTypePk'] === $rate_utilityPK ? 'selected="' . $rate_utilityPK . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $utility['UtilityTypePk']; ?>"><?php echo $utility['Value']; ?></option>
                <?php } ?>
                </select>
            </li>
            <li>Provider:</li>
            <li>
                <select id="rate_provider" name="choose_detail_provider">
                    <option value="0">Please select...</option>
                <?php foreach ($provider_list as $provider) { 
                    $selected = $provider['ProviderPk'] === $rate_providerPK ? 'selected="' . $rate_providerPK . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $provider['ProviderPk']; ?>"><?php echo $provider['Name']; ?></option>
                <?php } ?>
                </select>
            </li>
        </ul>
    </fieldset>
    <fieldset class="fieldset-forms hover-cursor-pointer">
        <ul class="fieldset-forms-li-2-cols">
            <li><label>Start date:</label></li>
            <li>
                <input id="rate-detail-start-date" class="disabled-input" type="date" disabled required name="rate_starting_date" value="<?php echo $rate_starting_date; ?>"/>
                <input id="rate-start-date" name="rate_start_date" class="hidden" type="date"/>
            </li>
            <li><button id="rate-detail-new-period-button">New Period</button></li>
            <li></li>
            <li>Active:</li>
            <li><input name="rate_isActive" type="checkbox" <?php echo $rate_isActive; ?> /></li>
        </ul>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of rate detail -->

<div id="rate-table" class="wrapper-fieldset-forms hover-cursor-pointer">
    <fieldset class="fieldset-forms">
        <legend>Rate Table</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li><label>Invoice description</label></li>
            <li><input type="text" id="rate_descr" class="rate-unique-value" name="rate_descr" value="<?php echo $rate_descr; ?>"/></li>
            <li style="border:none;">VAT applicable</li>
            <li style="border:none;"><input type="checkbox" <?php echo $rate_isVatApplicable; ?> name="rate_isVatApplicable"/></li>
            <li style="border:none;">Unitized</li>
            <li style="border:none;"><input type="checkbox" <?php echo $rate_isUnitised; ?> name="rate_isUnitised"/></li>	
        </ul>
        <ul id="rate-table-tab-menu" class="tab-menu">                                                                       
            <li id="rate-table-retail-rate" class="tab-menu-selected"><a>Retail Rate</a></li>
            <li id="rate-table-bulk-rate" class="tab-menu-item"><a>Bulk Rate</a></li>
        </ul>
        <div class="tab-extra-line"></div>
        <div id="rate-table-retail-rate-content"class="show tab-content ">
            <ul class="fieldset-forms-li-4-cols">
                <li class="center-li-contents"><label>From:</label></li>
                <li class="center-li-contents"><label>To:</label></li>
                <li class="center-li-contents"><label>Rate:</label></li>
                <li class="center-li-contents"><label>% Applied:</label></li>
            </ul>
			<div id="ratescale-retail-add-content">
            <?php
			$ratescale_retail_data = array();
			
			if (!empty($rate_scale_data)) {
				foreach($rate_scale_data as $data) {
					if ($data['IsRetail'] == 1) {
						$ratescale_retail_data[] = $data;
					}
				}
			}
			
            if (!empty($ratescale_retail_data)) {
                foreach ($ratescale_retail_data as $data) { 
            ?>
            <ul class="fieldset-forms-li-4-cols">
                <li class="center-li-contents">
					<input type="hidden" class="rate-pk-value" name="rate_scale_retailPKs[]" value="<?php echo $data['ScalePk']; ?>" />
					<input type="text" maxlength="10" value="<?php echo $data['From']; ?>" name="rate_scale_retail_from[]" class="ratescale-from input-integer"/>
				</li>
                <li class="center-li-contents"><input type="text" maxlength="10" value="<?php echo $data['To']; ?>" name="rate_scale_retail_to[]" class="ratescale-to input-integer"/></li>
                <li class="center-li-contents"><input type="text" value="<?php echo $data['Rate']; ?>" name="rate_scale_retail_rate[]" class="input-decimal"/></li>
                <li class="center-li-contents"><input type="text" maxlength="10" value="<?php echo $data['Percentage']; ?>" name="rate_scale_retail_percentage[]" class="input-integer"/></li>
            </ul>
            <?php } } else { ?>
			<ul class="fieldset-forms-li-4-cols">
                <li class="center-li-contents">
					<input type="hidden" class="rate-pk-value" name="rate_scale_retailPKs[]" value="0" />
					<input type="text" maxlength="10" name="rate_scale_retail_from[]" class="ratescale-from input-integer" value="0"/>
				</li>
                <li class="center-li-contents"><input type="text" maxlength="10" name="rate_scale_retail_to[]" class="ratescale-to input-integer" value="999999"/></li>
                <li class="center-li-contents"><input type="text" name="rate_scale_retail_rate[]" class="input-decimal"/></li>
                <li class="center-li-contents"><input type="text" maxlength="10" name="rate_scale_retail_percentage[]" class="input-integer"/></li>
            </ul>
			<?php } ?>
            </div>
			<div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="ratescale-retail-add-button" class="rate-add-button addline-button">Add</button>
			</div> 
			<div id="ratescale-retail-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div>
        </div>
        <div id="rate-table-bulk-rate-content" class="hidden tab-content parameters-menu-hover">
            <ul class="fieldset-forms-li-4-cols">
                <li class="center-li-contents"><label>From:</label></li>
                <li class="center-li-contents"><label>To:</label></li>
                <li class="center-li-contents"><label>Rate:</label></li>
                <li class="center-li-contents"><label>% Applied:</label></li>
            </ul>
			<div id="ratescale-bulk-add-content">
            <?php
			$ratescale_bulk_data = array();
			
			if (!empty($rate_scale_data)) {
				foreach($rate_scale_data as $data) {
					if ($data['IsRetail'] != 1) {
						$ratescale_bulk_data[] = $data;
					}
				}
			}
			
            if (!empty($ratescale_bulk_data)) {
                foreach ($ratescale_bulk_data as $data) { 
            ?>
            <ul class="fieldset-forms-li-4-cols">
                <li class="center-li-contents">
					<input type="hidden" class="rate-pk-value" name="rate_scale_bulkPKs[]" value="<?php echo $data['ScalePk']; ?>" />
					<input type="text" maxlength="10" value="<?php echo $data['From']; ?>" name="rate_scale_bulk_from[]" class="ratescale-from input-integer"/>
				</li>
                <li class="center-li-contents"><input type="text" maxlength="10" value="<?php echo $data['To']; ?>" name="rate_scale_bulk_to[]" class="ratescale-to input-integer"/></li>
                <li class="center-li-contents"><input type="text" value="<?php echo $data['Rate']; ?>" name="rate_scale_bulk_rate[]" class="input-decimal"/></li>
                <li class="center-li-contents"><input type="text" maxlength="10" value="<?php echo $data['Percentage']; ?>" name="rate_scale_bulk_percentage[]" class="input-integer"/></li>
            </ul>
            <?php } } else { ?>
			<ul class="fieldset-forms-li-4-cols">
                <li class="center-li-contents">
					<input type="hidden" class="rate-pk-value" name="rate_scale_bulkPKs[]" value="0" />
					<input type="text" maxlength="10" name="rate_scale_bulk_from[]" class="ratescale-from input-integer" value="0"/>
				</li>
                <li class="center-li-contents"><input type="text" maxlength="10" name="rate_scale_bulk_to[]" class="ratescale-to input-integer" value="999999"/></li>
                <li class="center-li-contents"><input type="text" name="rate_scale_bulk_rate[]" class="input-decimal"/></li>
                <li class="center-li-contents"><input type="text" maxlength="10" name="rate_scale_bulk_percentage[]" class="input-integer"/></li>
            </ul>
			<?php } ?>
            </div>
            <div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="ratescale-bulk-add-button" class="rate-add-button addline-button">Add</button>
			</div> 
			<div id="ratescale-bulk-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div>
        </div>
    </fieldset>
</div> <!-- end of rate table -->

<div id="rate-fixed-rate" class="wrapper-fieldset-forms hover-cursor-pointer">
    <fieldset class="fieldset-forms">
        <legend>Fixed Rate</legend>
        <ul id="rate-fix-rate-tab-menu" class="tab-menu">                                                                       
            <li id="rate-fix-rate-retail-rate" class="tab-menu-selected"><a>Retail Rate</a></li>
            <li id="rate-fix-rate-bulk-rate" class="tab-menu-item"><a>Bulk Rate</a></li>
        </ul>
        <div class="tab-extra-line"></div>
        <div id="rate-fix-rate-retail-rate-content" class="show tab-content parameters-menu-hover">
            <ul class="fieldset-forms-li-4-cols">
                <li>Invoice description</li>       
                <li class="center-li-contents">Fixed rate:</li> 
                <li class="center-li-contents">% Applied:</li>           
                <li class="center-li-contents">VAT:</li>
            </ul>
            <div id="fixrate-retail-add-content">
            <?php 
			$fixedrate_retail_data = array();
			
			if (!empty($rate_fixedrate_data)) {
				foreach($rate_fixedrate_data as $data) {
					if ($data['IsRetail'] == 1) {
						$fixedrate_retail_data[] = $data;
					}
				}
			}
			
            if (!empty($fixedrate_retail_data)) {
                foreach ($fixedrate_retail_data as $data) { 
                    $isVatApplicable = $data['IsVATApplicable'] == 1 ? 'checked':'';
            ?>
            <ul class="fieldset-forms-li-4-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_rate_retailPKs[]" value="<?php echo $data['FixedRatePk']; ?>" />
					<input type="text" maxlength="100" class="rate-unique-value" value="<?php echo $data['BillingDescription']; ?>" name="fixed_rate_retail_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" class="fieldset-forms-1fourth-length-input input-decimal" value="<?php echo $data['FixedRate']; ?>" name="fixed_rate_retail_rate[]" /></li>
                <li class="center-li-contents"><input type="text" maxlength="10" class="fieldset-forms-1fourth-length-input fixedrate-percent input-integer" value="<?php echo $data['Percentage']; ?>" name="fixed_rate_retail_percentage[]"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_rate_retail_isVat_temp[]" <?php echo $isVatApplicable; ?> />
                    <input type="hidden" name="fixed_rate_retail_isVat_values[]" value="<?php echo $data['IsVATApplicable']; ?>"/>
                </li>	
            </ul>
            <?php } } else { ?>
			<ul class="fieldset-forms-li-4-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_rate_retailPKs[]" value="0" />
					<input type="text" maxlength="100" class="rate-unique-value" name="fixed_rate_retail_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" class="fieldset-forms-1fourth-length-input input-decimal" name="fixed_rate_retail_rate[]"/></li>
                <li class="center-li-contents"><input type="text" maxlength="10" class="fieldset-forms-1fourth-length-input fixedrate-percent input-integer" name="fixed_rate_retail_percentage[]"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_rate_retail_isVat_temp[]" checked/>
                    <input type="hidden" name="fixed_rate_retail_isVat_values[]" value="1"/>
                </li>	
            </ul>
			<?php } ?>
            </div>
			<div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="fixrate-retail-add-button" class="rate-add-button addline-button">Add</button>
			</div> 
			<div id="fixrate-retail-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div>
        </div>
        <div id="rate-fix-rate-bulk-rate-content" class="hidden tab-content">
            <ul class="fieldset-forms-li-4-cols">
                <li>Invoice description</li>       
                <li class="center-li-contents">Fixed rate:</li> 
                <li class="center-li-contents">% Applied:</li>           
                <li class="center-li-contents">VAT:</li>
            </ul>
            <div id="fixrate-bulk-add-content">
            <?php 
			$fixedrate_bulk_data = array();
			
			if (!empty($rate_fixedrate_data)) {
				foreach($rate_fixedrate_data as $data) {
					if ($data['IsRetail'] != 1) {
						$fixedrate_bulk_data[] = $data;
					}
				}
			}
			
            if (!empty($fixedrate_bulk_data)) {
                foreach ($fixedrate_bulk_data as $data) { 
                    $isVatApplicable = $data['IsVATApplicable'] == 1 ? 'checked':'';
            ?>
            <ul class="fieldset-forms-li-4-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_rate_bulkPKs[]" value="<?php echo $data['FixedRatePk']; ?>" />
					<input type="text" maxlength="100" class="rate-unique-value" value="<?php echo $data['BillingDescription']; ?>" name="fixed_rate_bulk_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" class="fieldset-forms-1fourth-length-input input-decimal" value="<?php echo $data['FixedRate']; ?>" name="fixed_rate_bulk_rate[]" /></li>
                <li class="center-li-contents"><input type="text" maxlength="10" class="fieldset-forms-1fourth-length-input fixedrate-percent input-integer" value="<?php echo $data['Percentage']; ?>" name="fixed_rate_bulk_percentage[]"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_rate_bulk_isVat_temp[]" <?php echo $isVatApplicable; ?> />
                    <input type="hidden" name="fixed_rate_bulk_isVat_values[]" value="<?php echo $data['IsVATApplicable']; ?>"/>          
                </li>	
            </ul>
            <?php } } else { ?>
			<ul class="fieldset-forms-li-4-cols">
                <li>
					<input type="hidden" name="fixed_rate_bulkPKs[]" class="rate-pk-value" value="0" />
					<input type="text" maxlength="100" class="rate-unique-value" name="fixed_rate_bulk_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" class="fieldset-forms-1fourth-length-input input-decimal" name="fixed_rate_bulk_rate[]" /></li>
                <li class="center-li-contents"><input type="text" maxlength="10" class="fieldset-forms-1fourth-length-input fixedrate-percent input-integer" name="fixed_rate_bulk_percentage[]"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_rate_bulk_isVat_temp[]" checked />
                    <input type="hidden" name="fixed_rate_bulk_isVat_values[]" value="1"/>          
                </li>	
            </ul>
			<?php } ?>
            </div>
			<div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="fixrate-bulk-add-button" class="rate-add-button addline-button">Add</button>
			</div> 
			<div id="fixrate-bulk-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div> 
        </div>     
    </fieldset>
</div> <!-- end of fixed rate -->

<div id="rate-fixed-fees" class="wrapper-fieldset-forms hover-cursor-pointer">
    <fieldset class="fieldset-forms">
        <legend>Fixed Fees</legend>
        <ul id="rate-fix-fees-tab-menu" class="tab-menu">                                                                       
            <li id="rate-fix-fees-retail-rate" class="tab-menu-selected"><a>Retail Rate</a></li>
            <li id="rate-fix-fees-bulk-rate" class="tab-menu-item"><a>Bulk Rate</a></li>
        </ul>
        <div class="tab-extra-line"></div>
        <div id="rate-fix-fees-retail-rate-content" class="show tab-content parameters-menu-hover">
            <ul class="fieldset-forms-li-3-cols">
                <li><label>Invoice description</label></li>       
                <li class="center-li-contents">Basic charges:</li>           
                <li class="center-li-contents">VAT:</li>	
            </ul>
            <div id="fixedfees-retail-add-content">
            <?php 
			$fixedfees_retail_data = array();
			
			if (!empty($rate_fixedfee_data)) {
				foreach($rate_fixedfee_data as $data) {
					if ($data['IsRetail'] == 1) {
						$fixedfees_retail_data[] = $data;
					}
				}
			}
            if (!empty($fixedfees_retail_data)) {
                foreach ($fixedfees_retail_data as $data) { 
                    $isVatApplicable = $data['IsVATApplicable'] == 1 ? 'checked':'';
            ?>
            <ul class="fieldset-forms-li-3-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_fee_retailPKs[]" value="<?php echo $data['FixedFeePk']; ?>" />
					<input type="text" class="rate-unique-value" maxlength="100" value="<?php echo $data['BillingDescription']; ?>" name="fixed_fee_retail_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" value="<?php echo $data['FixedFee']; ?>" name="fixed_fee_retail_fee[]" class="input-decimal"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_fee_retail_isVat_temp[]" <?php echo $isVatApplicable; ?> />
                    <input type="hidden" name="fixed_fee_retail_isVat_values[]" value="<?php echo $data['IsVATApplicable']; ?>"/>          
                </li>
            </ul>
            <?php } } else { ?>
			<ul class="fieldset-forms-li-3-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_fee_retailPKs[]" value="0" />
					<input type="text" maxlength="100" class="rate-unique-value" name="fixed_fee_retail_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" name="fixed_fee_retail_fee[]" class="input-decimal"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_fee_retail_isVat_temp[]" checked />
                    <input type="hidden" name="fixed_fee_retail_isVat_values[]" value="1"/>          
                </li>
            </ul>
			<?php } ?>
            </div>
            <div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="fixedfees-retail-add-button" class="rate-add-button addline-button">Add</button>
			</div> 
			<div id="fixedfees-retail-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div> 
        </div>
        <div id="rate-fix-fees-bulk-rate-content" class="hidden tab-content parameters-menu-hover">
            <ul class="fieldset-forms-li-3-cols">
                <li><label>Invoice description</label></li>       
                <li class="center-li-contents">Basic charges:</li>           
                <li class="center-li-contents">VAT:</li>	
            </ul>
            <div id="fixedfees-bulk-add-content">
            <?php
			$fixedfees_bulk_data = array();
			
			if (!empty($rate_fixedfee_data)) {
				foreach($rate_fixedfee_data as $data) {
					if ($data['IsRetail'] != 1) {
						$fixedfees_bulk_data[] = $data;
					}
				}
			}
			
            if (!empty($fixedfees_bulk_data)) {
                foreach ($fixedfees_bulk_data as $data) { 
                    $isVatApplicable = $data['IsVATApplicable'] == 1 ? 'checked':'';
            ?>
            <ul class="fieldset-forms-li-3-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_fee_bulkPKs[]" value="<?php echo $data['FixedFeePk']; ?>" />
					<input type="text" class="rate-unique-value" maxlength="100" value="<?php echo $data['BillingDescription']; ?>" name="fixed_fee_bulk_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" value="<?php echo $data['FixedFee']; ?>" name="fixed_fee_bulk_fee[]" class="input-decimal"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_fee_bulk_isVat_temp[]" <?php echo $isVatApplicable; ?> />
                    <input type="hidden" name="fixed_fee_bulk_isVat_values[]" value="<?php echo $data['IsVATApplicable']; ?>"/>
                </li>
            </ul>
            <?php } } else { ?>
			<ul class="fieldset-forms-li-3-cols">
                <li>
					<input type="hidden" class="rate-pk-value" name="fixed_fee_bulkPKs[]" value="0" />
					<input type="text" maxlength="100" class="rate-unique-value" name="fixed_fee_bulk_descr[]"/>
				</li>
                <li class="center-li-contents"><input type="text" name="fixed_fee_bulk_fee[]" class="input-decimal"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="fixed_fee_bulk_isVat_temp[]" checked />
                    <input type="hidden" name="fixed_fee_bulk_isVat_values[]" value="1"/>
                </li>
            </ul>
			<?php } ?>
            </div>
            <div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="fixedfees-bulk-add-button" class="rate-add-button addline-button">Add</button>
			</div> 
			<div id="fixedfees-bulk-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div>
		</div>
    </fieldset>
</div> <!-- end of rate fix fees -->

<?php if($restriction_level > 0){?>
	<div class="wrapper-fieldset-forms hover-cursor-pointer <?php echo $view_class; ?>">
		<div id="rate-submit-error-box" class="submit-error-box error-box warning-box warning hidden"></div>
		<div class="form-submit">
			<input id="rate-save-button" class="submit-positive" type="submit" value="<?php echo $save_name; ?>" name="<?php echo $save_name; ?>" />
			<input class="submit-netagive" type="submit" value="Cancel" name="Cancel"/>
		</div>
	</div> <!-- end of form submit buttons -->
<?php }?>
</form> <!-- end of post form -->

<!-- modal shows after uploading songs -->
<div class="modal-shade"></div>
<div id="rates-modal-new-period" class="modal-view hover-cursor-pointer">
	<div class="modal-view-top">
		<p class="modal-view-title">New Period</p>
		<button title="close" class="modal-close-button"></button>
	</div>
	<div class="modal-view-content clear">
            <fieldset class="fieldset-forms">
                <div id="date-selection-error-box" class="selection-error-box error-box float-left hidden">Date is invalid</div>
                <ul class="fieldset-forms-li-2-cols">
                    <li><label>Start date:</label></li>
                    <li><input type="date" id="modal-rate-detail-start-date" autocomplete = "on"/></li>
                </ul>
                <div class="clear">
                    <button id="modal-rate-detail-new-period-button">Confirm</button>
                    <button id="modal-rate-detail-cancel-button">Cancel</button>
                </div>
            </fieldset>
	</div> <!-- end of modal content -->
</div> <!-- end of modal top -->
<!-- end of modal -->    
<?php
}
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>