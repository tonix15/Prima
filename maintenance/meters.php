<?php
$page_name = 'METERS';
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

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

// Building
$Building = new Building($dbh);
$building_list = null;
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

//Customer
$Customer = new Customer($dbh);
$customer_data = null;
$customerPk = 0;

//Meter
$Meter = new Meter($dbh);
$meterPK = !empty($_GET['choose_meter']) ? (int) $_GET['choose_meter'] : 0;
$meter_list = null;
$rmeter_list = null;
$meter_numberBK = '';
$meter_is_three_phase = '';
$meter_effective_date = '';
$meter_start_reading = '';
$meter_base_cost = '';
$meter_teamPK = 0;
$meter_reading_sequence = '';
$meter_isActive = 'checked';
$meter_isPrepaid = '';
$meter_isInternalMeter = '';
$meter_InternalPrepaidMeterNumberBk = '';
$meter_replacement_date = '';
$meter_ratePK = 0;
$meter_customerPK = 0;
$meter_start_date = '';
$meter_replacementFK = 0;
$meter_utilityPK = 0;

//Meter Type
$meter_type = new MeterType($dbh);
$meter_type_list = null;

//Rate
$rate = new Rate($dbh);
$rate_list = null;

//Utility Type
$utility_type = new UtilityType($dbh);
$utility_type_list = null;
$utilityPk = 0;

//Unit
$unit = new Unit($dbh);
$unit_list = null;
$unitPK = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit'] : 0;

// Billing Account
$Billing = new Billing($dbh);
$billing_data = null;
$meter_billing_accountPK = 0;

// Team
$Team = new Team($dbh);

// UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_GET['View']) || isset($_GET['Create'])) {
    $view_class = 'show';
	
	$utility_typePK = 0;
	$customer_list = $Customer->getCustomer(array($userPK, 0, 0));
	$meter_type_list = $meter_type->getMeterType(array($userPK, 0));
	$rate_list = $rate->getRate(array($userPK, 0));
}

if (isset($_GET['View'])) {
	$view_class = 'show';
	$save_name = 'Update';
	
	// Meter 
	$meter_data = $Meter->getMeter(array($userPK, $meterPK, 0, 0), true);	
	
	$meter_numberBK = $meter_data['NumberBk'];
	$meter_utilityPK = $meter_data['UtilityTypeFk'];
	$meter_typePK = $meter_data['MeterTypeFk'];
	$meter_is_three_phase = $meter_data['IsThreePhase'] == 1 ? 'checked':'';	
	$meter_start_date = $meter_data['CommissionDate'];
	$meter_start_reading = $meter_data['StartReading'];
	$meter_base_cost = $meter_data['BaseCost'];
	$meter_isActive = $meter_data['IsActive'] == 1 ? 'checked':'';
	$meter_isPrepaid = $meter_data['IsPrepaid'] == 1 ? 'checked':'';
	$meter_isInternalMeter = $meter_data['IsInternalPrepaidMeter'] == 1 ? 'checked':'';
	$meter_InternalPrepaidMeterNumberBk = $meter_data['InternalPrepaidMeterNumberBk'];
	$meter_ratePK = $meter_data['RateFk'];
	$meter_customerPK = $meter_data['PrepaidCustomerFk'];
	$meter_replacement_date = $meter_data['DecommissionDate'];
	$meter_replacementFK = $meter_data['ReplacedMeterFk'];
	$unitPK = $meter_data['UnitFk'];
	
	$customer_data = $Customer->getCustomer(array($userPK, $meter_customerPK, 0), true);
	$meter_billing_accountPK = $customer_data['BillingAccountFk'];
	
	
} else if (isset($_GET['Create'])) {
	$save_name = 'Create';
	$meterPK = 0;
	$utility_type_list = $utility_type->getUtilityType(array($userPK, 0));
}


	
	
if (isset($_POST['Create']) || isset($_POST['Update'])) {
    $view_class = 'hidden';
	$meter = array(
		'numberBK', 'utility_type', 'type', 'is_three_phase', 'start_date', 'start_reading',
		'base_cost', 'isActive', 'isPrepaid', 'ratePK', 'billing_accountPK', 
		'replacement_date', 'replacementFK', 'isInternalMeter'
	);
	
	$len = count($meter);
	
	for ($i = 0; $i < $len; $i++) {
		${'meter_' . $meter[$i]} = !empty($_POST['meter_' . $meter[$i]]) ? trim($_POST['meter_' . $meter[$i]]) : '';
	}
	
	$customer_data = $Customer->getCustomer(array($userPK, 0, $meter_billing_accountPK), true);
	$meter_customerPK = $customer_data['CustomerPk'];
}

if (isset($_POST['Create'])) {
	$isInternalMeter = !empty($meter_isPrepaid) && !empty($meter_isInternalMeter) ? $meter_isInternalMeter : 0;
	
	$new_meterPK = $Meter->createMeter(
		array(
			$userPK,
			0, // new meterPK
			$meter_numberBK, //NumberBk
			$_POST['Real_meter_InternalPrepaidMeterNumber'],
			$buildingPK,
			$unitPK,
			$meter_utility_type,
			$meter_type,
			$meter_start_date,
			$meter_start_reading,
			$meter_base_cost,
			$meter_replacement_date,
			empty($meter_replacementFK) ? -1 : $meter_replacementFK,
			!empty($meter_ratePK) ? $meter_ratePK : -1,
			!empty($meter_isPrepaid) ? 1 : 0, //isPrepaid
			$isInternalMeter,
			!empty($meter_customerPK) ? $meter_customerPK : -1,
			!empty($meter_is_three_phase) ? 1 : 0,
			!empty($meter_isActive) ? 1 : 0
		)
	); 
	
	if (!empty($new_meterPK) && $new_meterPK > 0) {
		$meterPK = $new_meterPK;
		$submit_result = 'success';
		$errmsg = 'Record created successfully!';
	} else {
		$submit_result = 'error';
		$errmsg = 'Error occurred when creating a new record!';
	}
	
	$error_class = 'show';

} else if (isset($_POST['Update'])) {
	$isInternalMeter = !empty($meter_isPrepaid) && !empty($meter_isInternalMeter) ? 1 : 0;

	if ($Meter->updateMeter(
		array(
			$userPK,
			$meterPK,
			$meter_numberBK, //NumberBk
			$_POST['Real_meter_InternalPrepaidMeterNumber'],
			$buildingPK,
			$unitPK,
			$meter_utility_type,
			$meter_type,
			$meter_start_date,
			$meter_start_reading,
			$meter_base_cost,
			$meter_replacement_date,
			empty($meter_replacementFK) ? -1 : $meter_replacementFK,
			!empty($meter_ratePK) ? $meter_ratePK : -1,
			!empty($meter_isPrepaid) ? 1 : 0, //isPrepaid
			$isInternalMeter,
			!empty($meter_customerPK) ? $meter_customerPK : -1,
			!empty($meter_is_three_phase) ? 1 : 0,
			!empty($meter_isActive) ? 1 : 0
		)
	)) {
		// $submit_result = 'success';
		// $errmsg = 'Record successfully updated!';
	} else {
		// $submit_result = 'error';
		// $errmsg = 'Error occured when updating the record!';
	}
	
	$submit_result = 'success';
	$errmsg = 'Record successfully updated!';
	$error_class = 'show';
}
else if (isset($_POST['Cancel'])) {
	header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$utility_type_list = $utility_type->getUtilityType(array($userPK, 0));
	$utility_type_temp = array();
	foreach ($utility_type_list as $utility_type) {
		if ($utility_type['IsMetered']) {
			$utility_type_temp[] = $utility_type;
		}
	}
	$utility_type_list = $utility_type_temp;
$building_list = $Building->getBuilding(array($userPK, 0));
$team_list = $Team->getTeam(array($userPK, 0));

if (!empty($buildingPK)) {
	$unit_list = $unit->getUnit(array($userPK, 0, $buildingPK));
}
if (!empty($buildingPK)) {
	$meter_list = $Meter->getMeter(array($userPK, 0, $buildingPK, $unitPK));
}

?>

<form method="get" class="hover-cursor-pointer">
<div class="sub-menu-title"><h1>Meter Master</h1></div>
<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<div id="meter-critera" class="wrapper-fieldset-forms">
	<fieldset class="fieldset-forms clear">
        <legend>Selection Criteria</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Building:</li>
            <li>
				<select id="meter-selection-building" class="selection-required-input" name="choose_building">
					<option value="0">Please choose a building</option>
					<?php 
                    if (!empty($building_list)) { 
                    foreach($building_list as $building) { 
                        $selected = $buildingPK == $building['BuildingPk'] ? 'selected="' . $building['BuildingPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
                    <?php } } ?>
				</select>
			</li>
            <li>Unit:</li>
            <li>
				<select id="meter-selection-unit" class="selection-required-input" name="choose_unit">
					<option value="0">Please choose a unit</option>
					<?php 
                    if (!empty($unit_list)) { 
                    foreach($unit_list as $unit) { 
                        $selected = $unitPK == $unit['UnitPk'] ? 'selected="' . $unit['UnitPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $unit['UnitPk']; ?>"><?php echo $unit['NumberBk']; ?></option>
                    <?php } } ?>
				</select>
			</li>
            <li>Meter:</li>
            <li>
				<select id="meter-selection-meter" class="selection-required-input" name="choose_meter">
					<option value="0">Please choose a meter</option>
					<?php 
                    if (!empty($meter_list)) { 
                    foreach($meter_list as $meter) { 
						if ($meter['UtilityTypeFk'] == $meter_utilityPK) {
							$rmeter_list[] = $meter;
						}
                        $selected = $meterPK  == $meter['MeterPk'] ? 'selected="' . $meter['MeterPk'] . '"':''; ?>
                        <option <?php echo $selected; ?> value="<?php echo $meter['MeterPk']; ?>" ><?php echo $meter['NumberBk']; ?></option>
                    <?php } } ?>
				</select>
			</li>
        </ul>
        <div class="selection-form-submit float-left">
            <input id="meter-selection-view" type="submit" value="View" name="View"/>
			<?php if($restriction_level > 0){?>
				<input id="meter-selection-create" type="submit" value="Create" name="Create"/>   
			<?php }?>
        </div> 
        <div id="meter-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of building selection -->
</form> <!-- end of get form -->

<?php if ($view_class === 'show') { ?>
<form id="meter-post-form" method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
<div id="meter-take-on" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms float-left">
        <legend>Take On</legend>
        <ul class="fieldset-forms-li-2-cols">
        	<li>Meter Code: </li>
        	<li><input type="text" id="meter_numberBK" class="fieldset-forms-1half-length-input" maxlength="20" name="meter_numberBK" value="<?php echo $meter_numberBK; ?>" /></li>
            <li>Utility type:</li>
            <li>
				<select id="meter_utility_type" name="meter_utility_type">
					<option value="0">Please choose a utility</option>
					<?php 
                    if (!empty($utility_type_list)) { 
                    foreach($utility_type_list as $utility_type) { 
						$selected = $meter_utilityPK == $utility_type['UtilityTypePk'] ? 'selected="' . $utility_type['UtilityTypePk'] . '"':''; 						
                     
					?>
						<option <?php echo $selected; ?> value="<?php echo $utility_type['UtilityTypePk']; ?>" ><?php echo $utility_type['Value']; ?></option>
                    <?php } } ?>
				</select>
			</li>
            <li>Meter type:</li>
            <li>
				<select id="meter_type" name="meter_type">
					<option value="0">Please choose a meter type</option>
					<?php 
                    if (!empty($meter_type_list)) { 
                    foreach($meter_type_list as $meter_type) { 
                        $selected = $meter_typePK == $meter_type['MeterTypePk'] ? 'selected="' . $meter_type['MeterTypePk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $meter_type['MeterTypePk']; ?>"><?php echo $meter_type['Value']; ?></option>
                    <?php } } ?>
				</select>
			</li>
			<li>Three phase:</li>
            <li><input type="checkbox" name="meter_is_three_phase" <?php echo $meter_is_three_phase; ?> /></li> 
            <li>Start date:</li>
            <li><input type="date" id="meter_start_date" class="fieldset-forms-3fourth-length-input" name="meter_start_date" value="<?php echo $meter_start_date; ?>" /></li>
            <li>Start reading:</li>
            <li><input type="text" maxlength="10" class="fieldset-forms-3fourth-length-input input-integer" name="meter_start_reading" value="<?php echo $meter_start_reading; ?>"/></li> 
            <li>Base cost:</li>
            <li><input type="text" class="fieldset-forms-3fourth-length-input input-decimal" name="meter_base_cost" value="<?php echo $meter_base_cost; ?>" /></li>
            <li>Active:</li>
            <li><input type="checkbox" name="meter_isActive" <?php echo $meter_isActive; ?>/></li>			
        </ul>
    </fieldset>
    <fieldset class="fieldset-forms float-left">
        <legend>Exceptions</legend>
        <div class="wrapper-fieldset-forms clear">
            <fieldset class="fieldset-forms">
                <legend>Prepaid</legend>
                <ul class="fieldset-forms-li-2-cols">
                    <li>Rate code:</li>
                    <li>
						<select id="meter_rate_code" name="meter_ratePK">
							<option value="0">Please choose a rate</option>
							<?php 
							if (!empty($rate_list)) { 
							foreach($rate_list as $rate) { 
								$selected = $meter_ratePK == $rate['RatePk'] ? 'selected="' . $rate['RatePk'] . '"':''; 
							?>
								<option <?php echo $selected; ?> value="<?php echo $rate['RatePk']; ?>"><?php echo $rate['Name']; ?></option>
							<?php } } ?>
						</select>
					</li>
					<li>Prepaid:</li>
					<li><input type="checkbox" id="meter_isPrepaid" name="meter_isPrepaid" <?php echo $meter_isPrepaid; ?> /></li>
					<li>Internal Meter:</li>
					<li><input type="checkbox" id="meter_isInternalMeter" name="meter_isInternalMeter" <?php echo $meter_isInternalMeter; ?> <?php echo empty($meter_isPrepaid) ? 'disabled="disabled"' : ''; ?>/></li>
					<li>Internal Prepaid Meter Number:</li>
					<li>
						<input type="text" id="meter_InternalPrepaidMeterNumber" name="meter_InternalPrepaidMeterNumber" value="<?php echo $meter_InternalPrepaidMeterNumberBk; ?>" <?php echo empty($meter_isInternalMeter) ? 'disabled="disabled"' : ''; ?> />
						<input type="hidden" name="Real_meter_InternalPrepaidMeterNumber" value="<?php echo $meter_InternalPrepaidMeterNumberBk; ?>" />
					</li>
                    <li>Customer:</li>
                    <li>
						<select id="meter_customer" name="meter_billing_accountPK" <?php echo empty($meter_isPrepaid) ? 'disabled="disabled"' : ''; ?>>
							<option value="0">Please choose a customer</option>
							<?php 
							$billing_account_list = $Billing->getBillingAccount(array($userPK, 0, $buildingPK, $unitPK, 1));
							if (!empty($billing_account_list)) { 
							foreach($billing_account_list as $account) { 
								$selected = $meter_billing_accountPK == $account['BillingAccountPk'] ? 'selected="' . $account['BillingAccountPk'] . '"':''; ?>
								<option <?php echo $selected; ?> value="<?php echo $account['BillingAccountPk']; ?>"><?php echo $account['ERPCode']; ?></option>
							<?php } } ?>
						</select>
					</li> 			
                </ul>
            </fieldset>
        </div>
        <div class="wrapper-fieldset-forms clear">
            <fieldset class="fieldset-forms">
                <legend>Replacement</legend>
                <ul class="fieldset-forms-li-2-cols">
                    <li>Replacement meter:</li>
                    <li>
						<select id="meter-replacement" name="meter_replacementFK">
							<option value="0">Please choose a meter</option>
							<?php 
							if (isset($_GET['Create'])) {
								$rmeter_list = $meter_list;
							}
							
							if (!empty($rmeter_list)) { 
							foreach($rmeter_list as $meter) { 
								if ($meterPK != $meter['MeterPk']) {
								$selected = $meter_replacementFK == $meter['MeterPk'] ? 'selected="' . $meter['MeterPk'] . '"':''; ?>
								<option <?php echo $selected; ?> value="<?php echo $meter['MeterPk']; ?>"><?php echo $meter['NumberBk']; ?></option>
							<?php } } } ?>
						</select>
					</li>     
                </ul>
            </fieldset>
        </div>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of customer responsible person -->

<?php if($restriction_level > 0){?>
	<div class="wrapper-fieldset-forms">
		<div id="meter-submit-error-box" class="submit-error-box warning-box warning hidden"></div>
		<div class="form-submit">
			<input id="meter-save-button" class="submit-positive" type="submit" value="<?php echo $save_name; ?>" name="<?php echo $save_name; ?>" />
			<input class="submit-netagive" type="submit" value="Cancel" name="Cancel"/>
		</div>
	</div> <!-- end of form submit buttons -->
<?php }?>
</form> <!-- end of post form -->
<?php
}
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>