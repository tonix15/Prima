<?php
$page_name = 'BUILDINGS';

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

if (isset($_POST['Cancel'])) {
    header('Location: ' . DOMAIN_NAME . '/maintenance/buildings.php');
    exit;
}

// Allocation
$Allocation = new AllocationType($dbh);

// Utility
$Utility = new UtilityType($dbh);

// Preffered Contact Type
$PreferedContactType = new PreferredContactType($dbh);

// Building Type
$BuildingType = new BuildingType($dbh);

// Address
$Address = new Address($dbh);
$addressPK = 0;

// Contact Person
$ContactPerson = new ContactPerson($dbh);
$contact_personPK = 0;
$contact_name = '';
$contact_email = '';
$contact_cellphone = '';
$contact_landline = '';
$contact_fax = '';
$contact_type = '';
$contact_person_data = null;

// Building
$Building = new Building($dbh);
$building_data = null;
$rate_account_list = null;
$buildingPK = 0;
$building_business_code = '';
$building_name = '';
$building_typePK = 0;
$building_no_units = '';
$building_address1 = '';
$building_address2 = '';
$building_address3 = '';
$building_address4 = '';
$building_postal_code = '';
$building_isActive = 'checked';
$building_comments = '';
$building_meter_allocationPK = 0;
$building_property_allocationPK = 0;
$building_portfolio_managerPK = 0;
$building_bulk_allocationPK = 0;
$building_isRead_only = '';
$building_reading_day = '';
$building_reading_sequence = '';
$building_teamPK = '';
$building_standard_deposit = '';
$building_isBulkWaterBulkRate = '';
$building_isCommonPropertyBulkRate = '';
$building_isIndividualWaterMeter = '';
$building_isServicMeterBulkRate = '';

// Rate
$Rate = new Rate($dbh);

// Unit
$Unit = new Unit($dbh);

// Team
$Team = new Team($dbh);

// Company
$Company = new Company($dbh);

// UI
$view_class = 'hidden';
$create_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_GET['View'])) {
    $view_class = 'show';
    $buildingPK = $_GET['choose_building'];
    $building_data = $dbhandler->getBuilding(array($userPK, $buildingPK), true);
    $address_data = $dbhandler->getAddress(array($userPK, $building_data['BuildingAddressFk']), true);

    $contact_person_data = $dbhandler->getContactPerson(array($userPK, $building_data['BuildingContactPersonFk']), true);

    // Building
    $building_business_code = $building_data['NumberBk'];
    $building_name = $building_data['Name'];
    $building_typePK = $building_data['BuildingTypeFk'];
    $building_no_units = $building_data['NoOfUnits'];
    $building_address1 = $address_data['Address1'];
    $building_address2 = $address_data['Address2'];
    $building_address3 = $address_data['Address3'];
    $building_address4 = $address_data['Address4'];
    $building_postal_code = $address_data['PostalCode'];
    $building_isActive = $building_data['IsActive'] ? 'checked':'';
    $building_comments = $building_data['Comments'];
    $building_meter_allocationPK = $building_data['ServiceMeterAllocationTypeFk'];
    $building_property_allocationPK = $building_data['CommonPropertyAllocationTypeFk'];
    $building_bulk_allocationPK = $building_data['BulkWaterMeterAllocationTypeFk'];
    $building_isRead_only = $building_data['IsReadOnlyBuilding'] ? 'checked':'';
    $building_portfolio_managerPK = $building_data['PortfolioManagerFk'];
    $building_standard_deposit = $building_data['StandardDeposit'];
    $building_isBulkWaterBulkRate = $building_data['IsBulkWaterBulkRate'] ? 'checked':'';
    $building_isCommonPropertyBulkRate = $building_data['IsCommonPropertyBulkRate'] ? 'checked':'';
	$building_isIndividualWaterMeter = $building_data['IsIndividualWaterMeter'] ? 'checked':'';
    $building_isServicMeterBulkRate = $building_data['IsServiceMeterBulkRate'] ? 'checked':'';
    $rate_account_list = $Building->getBuildingRateAccount(array($userPK, 0, $buildingPK));
       
    $contact_name = $contact_person_data['Name'];
    $contact_email = $contact_person_data['Email'];
    $contact_cellphone = $contact_person_data['Cellphone'];
    $contact_landline = $contact_person_data['AlternatePhone'];
    $contact_fax = $contact_person_data['Fax'];
    $contact_type = $contact_person_data['PreferredContactTypeFk'];
    
} else if (isset($_GET['Create'])) {
    $create_class = 'show';
}

if (isset($_POST['Create']) || isset($_POST['Update']) || isset($_POST['Save_and_Create'])) {
    $view_class = 'show';
    $building = array('business_code', 'name', 'type', 'no_units', 'portfolio_manager', 'isActive', 'isIndividualMeter',
        'comments', 'isRead_only', 'reading_day', 'reading_team', 'reading_sequence', 'isSplit_reading',
        'address1', 'address2', 'address3', 'address4', 'postal_code', 'service_meter_allocation', 'service_property_allocation',
        'service_bulk_water', 'standard_deposit', 'portfolio_managerPK', 'isBulkWaterBulkRate', 'isServicMeterBulkRate', 'isCommonPropertyBulkRate', 'isIndividualWaterMeter');
    $contact = array('name', 'email', 'cellphone', 'landline', 'fax',  'type');
    $rate = array('utility_values', 'values', 'account_numbers', 'account_PK', 'is_showSteps_values', 'isActive_values');
    $building_len = count($building);
    $contact_len = count($contact);
    $rate_len = count($rate);
    
    // Building
    for ($i = 0; $i < $building_len; $i++) {
        ${'building_' . $building[$i]} = !empty($_POST['building_' . $building[$i]]) ? trim($_POST['building_' . $building[$i]]) : '';
    }
    
    // Contact person
    for ($i = 0; $i < $contact_len; $i++) {
        ${'contact_' . $contact[$i]} = !empty($_POST['contact_' . $contact[$i]]) ? trim($_POST['contact_' . $contact[$i]]) : '';
    }

    // Rate account
    for ($i = 0; $i < $rate_len; $i++) {
        ${'rate_' . $rate[$i]} = !empty($_POST['rate_' . $rate[$i]]) ? $_POST['rate_' . $rate[$i]] : '';
    } 
  
}

if (isset($_POST['Create']) || isset($_POST['Save_and_Create'])) {
    $view_class = 'hidden';
    $create_class = 'hidden';
    
     // Create new address
    $building_addressPK = $Address->createAddress(
        array(
            $userPK, 
            0, // new addressPK
            $building_address1, 
            $building_address2, 
            $building_address3, 
            $building_address4,
            $building_postal_code   
        )
    );
    
    // create a dummy address for the new contact person
    $contact_person_addressPK = $Address->createAddress(
        array(
            $userPK, 0, // new addressPK
            '', '', '', '', ''
        )
    );
    
    // Create new contact person
    $contact_personPK = $ContactPerson->createContactPerson(
        array(
            $userPK, // userPK
            0, // new contact person
            $contact_name, 
            $contact_email, 
            $contact_cellphone, 
            $contact_landline, 
            $contact_fax, 
            !empty($contact_type) ? $contact_type : -1, 
            $contact_person_addressPK // address PK
        )
    );
    
    
    // Create new building
    $newBuildingPK = $Building->createBuilding(
        array(
            $userPK, // userPK
            0, // new building
            $building_business_code, 
            $building_name, 
            $building_no_units, 
            $building_addressPK, 
            $contact_personPK, 
            !empty($building_isRead_only) ? 1 : 0,
            !empty($building_service_meter_allocation) ? $building_service_meter_allocation : -1,
            !empty($building_service_property_allocation) ? $building_service_property_allocation : -1,
			!empty($building_service_individual_water_meter) ? $building_service_individual_water_meter : -1,
            !empty($building_service_bulk_water) ? $building_service_bulk_water : -1,
            !empty($building_isActive) ? 1 : 0,
            $building_type,
            $building_portfolio_managerPK > 0 ? $building_portfolio_managerPK : -1, //portfolio manager fk
            $building_comments,
            $building_standard_deposit,
        	!empty($building_isServicMeterBulkRate) ? 1 : 0,
        	!empty($building_isCommonPropertyBulkRate) ? 1 : 0,
        	!empty($building_isBulkWaterBulkRate) ? 1 : 0,
			!empty($building_isIndividualWaterMeter) ? 1 : 0
        )
    );
    
    // Create new building rate account
    $rate_account_len = count($rate_account_PK);
    for ($i = 0; $i < $rate_account_len; $i++) {
        if (!empty($rate_utility_values[$i]) && !empty($rate_values[$i])) {
			$Building->createBuildingRateAccount(
				array(
					$userPK,
					0,
					$newBuildingPK,
					!empty($rate_values[$i]) ? $rate_values[$i] : -1,
					!empty($rate_utility_values[$i]) ? $rate_utility_values[$i] : -1,
					!empty($rate_account_numbers[$i]) ? trim($rate_account_numbers[$i]) : '',
					!empty($rate_is_showSteps_values[$i]) ? 1 : 0,
					!empty($rate_isActive_values[$i]) ? 1 : 0
				)
			);
		}
    }
    
    $units_len = isset($_POST['Save_and_Create']) ? $building_no_units : 0;
    $new_unitPK = 0;
    $first_unitPK = 0;
    
	for ($i = 0; $i <= $units_len; $i++) {
		$new_unitPK = $Unit->createUnit(
			array(
				$userPK,
				0, // new unitPk
				$i, // unit no.
				$newBuildingPK,
				1, // isOccupied
				0 // square meters
			)
		);
		
		if ($i === 0) {
			$first_unitPK = $new_unitPK;
		}
	}
	
	if (!empty($newBuildingPK) && $newBuildingPK > 0) {
		$Billing = new Billing($dbh);
		$Customer = new Customer($dbh);
		$buildingPK = $newBuildingPK;
		$new_billing_accountPK = 0;
		
		if ($first_unitPK > 0) {
			$new_unitPK = $first_unitPK;
			// create new Billing Account
			$new_billing_accountPK = $Billing->createBillingAccount(
				array(
					$userPK, 0, // new Billing Account
					$buildingPK,
					$new_unitPK,
					0, '', 0, 0, 0, 0, '', 0,
					'2100-01-01',
					'2100-01-01'
				)
			);
			
			/** Create new Customer */
			// New Address record for Employer, Reference and Contact details
			$contacts = array('contact', 'employer', 'reference');
			$contacts_len = count($contacts);
			for ($i = 0; $i < $contacts_len; $i++) {
				${'new_' . $contacts[$i] . '_addressPK'} = $Address->createAddress(
					array(
						$userPK, 0, // new address
						'', '', '', '', ''
					)
				);
			}
			
			unset($contacts[0]);
			for ($i = 1; $i <= 2; $i++) {
				${'new_' . $contacts[$i] . 'PK'} = $ContactPerson->createContactPerson(
					array(
						$userPK, 0, // new contact person Pk
						'', '', '', '', '', -1, //Preferred contact type
						${'new_' . $contacts[$i] . '_addressPK'}
					)
				);
			
			}
			
			// create new customer
			$new_customerPK = $Customer->createCustomer(
				array(
					$userPK, 0, // New customerPK
					-1, '', '', '', '', '', '', '', -1, '', '', '', -1,
					$new_contact_addressPK, // Postal address
					$new_employerPK,
					$new_referencePK, '',
					$new_billing_accountPK, '',
				)
			);
		} 
		
		if ($new_customerPK > 0) {
			$Company->createCompanyBuilding(array($userPK, 0, $User->getCompanyId(), $newBuildingPK));
			$submit_result = 'success';
			$errmsg = 'Record created successfully!';
		} else {
			$submit_result = 'error';
			$errmsg = 'Error occurred when creating a new record!';
		}
		
	} else {
		$submit_result = 'error';
		$errmsg = 'Error occurred when creating a new record!';
	}
    
	$error_class = 'show';
} else if (isset($_POST['Update'])) {
    $view_class = 'hidden';
    $create_class = 'hidden';
    $isUpdate = true;
    // update address
	
    $Address->updateAddress(
        array(
            $userPK, 
            $building_data['BuildingAddressFk'], // new addressPK
            $building_address1, 
            $building_address2, 
            $building_address3, 
            $building_address4,
            $building_postal_code   
        )
    ); 
    
    // update contact person
    $ContactPerson->updateContactPerson(
        array(
            $userPK, // userPK
            $building_data['BuildingContactPersonFk'], // new contact person
            $contact_name, 
            $contact_email, 
            $contact_cellphone, 
            $contact_landline, 
            $contact_fax, 
            !empty($contact_type) ? $contact_type : -1,
            $contact_person_data['AddressFk'] // address PK
        )
    );

    // update building
    $isUpdate = $isUpdate && $Building->updateBuilding(
        array(
            $userPK, // userPK
            $buildingPK, 
            $building_business_code, 
            $building_name, 
            $building_no_units, 
            $building_data['BuildingAddressFk'], 
            $building_data['BuildingContactPersonFk'], 
            !empty($building_isRead_only) ? 1 : 0,
            !empty($building_service_meter_allocation) ? $building_service_meter_allocation : -1,
            !empty($building_service_property_allocation) ? $building_service_property_allocation : -1,
            !empty($building_service_bulk_water) ? $building_service_bulk_water : -1,
            !empty($building_isActive) ? 1 : 0,
            $building_type,
            $building_portfolio_managerPK > 0 ? $building_portfolio_managerPK : -1, //portfolio manager fk
            $building_comments,
            $building_standard_deposit,
        	!empty($building_isServicMeterBulkRate) ? 1 : 0,
        	!empty($building_isCommonPropertyBulkRate) ? 1 : 0,
        	!empty($building_isBulkWaterBulkRate) ? 1 : 0,
		    !empty($building_isIndividualWaterMeter) ? 1 : 0
        ) //,
    );
 
   
    // update building rate account
    $rate_account_len = count($rate_account_PK);
    for ($i = 0; $i < $rate_account_len; $i++) {
        $method = !empty($rate_account_PK[$i]) && $rate_account_PK[$i] > 0 ? 'updateBuildingRateAccount' : 'createBuildingRateAccount';
		$Building->$method(
			array(
				$userPK,
				$rate_account_PK[$i],
				$buildingPK,
				!empty($rate_values[$i]) ? $rate_values[$i] : -1,
				!empty($rate_utility_values[$i]) ? $rate_utility_values[$i] : -1,
				!empty($rate_account_numbers[$i]) ? trim($rate_account_numbers[$i]) : '',
				!empty($rate_is_showSteps_values[$i]) ? 1 : 0,
				!empty($rate_isActive_values[$i]) ? 1 : 0
			)
		); 
    } 
	
	if ($isUpdate) {
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

$preferred_contact_types = $PreferedContactType->getPreferredContactType(array($userPK, 0));
$building_type_list = $BuildingType->getBuildingType(array($userPK, 0));
$allocation_list = $Allocation->getAllocationType(array($userPK, 0));
$building_list = $Building->getBuilding(array($userPK, 0));
$rate_list = $Rate->getRate(array($userPK, 0, 0));
$utility_list = $Utility->getUtilityType(array($userPK, 0));
$team_list = $Team->getTeam(array($userPK, 0));
$portfolio_manager_list = $Building->getBuildingPortfolioManager(array($userPK, 0));

?>
<form method="get" class="hover-cursor-pointer">
	<div class="sub-menu-title">
		<h1>Building Master</h1>
	</div>
	<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
	<div id="building-selection" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>Building Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Building:</label></li>
				<li><select id="building-selection-building"
					class="selection-required-input" name="choose_building">
						<option value="0">Please choose a building</option>
                    <?php 
                    if (!empty($building_list)) { 
                    foreach($building_list as $building) { 
                        $selected = $buildingPK == $building['BuildingPk'] ? 'selected="' . $building['BuildingPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?>
							value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
                    <?php } } ?>
                </select></li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="building-selection-view" type="submit" value="View" name="View" /> 
				<?php if($restriction_level > 0){ ?>
					<input type="submit" value="Create" name="Create" />
				<?php }?>
			</div>
			<div id="building-selection-error-box"
				class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div>
	<!-- end of building selection -->
</form>
<!-- end of get form -->

<?php if ($view_class === 'show' || $create_class === 'show') { ?>
<form method="post"
	class="hover-cursor-pointer <?php echo $view_class; ?> <?php echo $create_class; ?>">
	<div id="building-details" class="wrapper-fieldset-forms">
		<fieldset id="building-fieldset-details"
			class="fieldset-forms float-left">
			<legend>Building Detail</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li>Building code:</li>
				<li><input type="text" id="building_code"
					class="fieldset-forms-1half-length-input" maxlength="20"
					value="<?php echo $building_business_code; ?>"
					name="building_business_code" /></li>
				<li>Name:</li>
				<li><input type="text" id="building_name" maxlength="50" value="<?php echo $building_name; ?>" name="building_name" /></li>
				<li>Type:</li>
				<li><select id="building_type" name="building_type"><option
							value="0">Please choose a type</option>
            <?php 
            if (!empty($building_type_list)) { 
            foreach($building_type_list as $type) { 
               $selected = $building_typePK == $type['BuildingTypePk'] ? 'selected="' . $type['BuildingTypePk'] . '"':''; 
               ?>    
                <option <?php echo $selected; ?>
							value="<?php echo $type['BuildingTypePk']; ?>"><?php echo $type['Value']; ?></option>
            <?php } } ?>
            </select></li>
				<li>Number of units:</li>
				<li><input type="text" id="building_no_units" maxlength="10"
					class="fieldset-forms-1fourth-length-input input-integer"
					value="<?php echo $building_no_units; ?>" name="building_no_units" /></li>
				<li>Address 1:</li>
				<li><input type="text" maxlength="100" name="building_address1"
					value="<?php echo $building_address1; ?>" /></li>
				<li>Address 2:</li>
				<li><input type="text" maxlength="100" name="building_address2"
					value="<?php echo $building_address2; ?>" /></li>
				<li>Address 3:</li>
				<li><input type="text" maxlength="100" name="building_address3"
					value="<?php echo $building_address3; ?>" /></li>
				<li>Address 4:</li>
				<li><input type="text" maxlength="100" name="building_address4"
					value="<?php echo $building_address4; ?>" /></li>
				<li>Postal code:</li>
				<li><input type="text" maxlength="10" name="building_postal_code"
					value="<?php echo $building_postal_code; ?>"
					class="fieldset-forms-1fourth-length-input" /></li>
				<li>Portfolio manager:</li>
				<li><select name="building_portfolio_managerPK">
						<option value="0">Please select...</option>
                <?php foreach ($portfolio_manager_list as $portfolio_manager) {   
                    $selected = $building_portfolio_managerPK === $portfolio_manager['UserPk'] ? 'selected="' . $portfolio_manager['$portfolio_manager'] . '"':'';
                ?>
                    <option <?php echo $selected; ?>
							value="<?php echo $portfolio_manager['UserPk']; ?>"><?php echo $portfolio_manager['DisplayName']; ?></option>
                <?php } ?>
                </select></li>
                <li>Active:</li>
				<li><input type="checkbox" <?php echo $building_isActive; ?>
					name="building_isActive" /></li>
			</ul>
		</fieldset>
		<fieldset id="building-fieldset-contact-comments"
			class="fieldset-forms float-left">
			<legend>Comments</legend>
			<textarea maxlength="1000" name="building_comments"><?php echo $building_comments; ?></textarea>
		</fieldset>
		<div class="clear"></div>
	</div>
	<!-- end of building details -->

	<div id="building-contact-details" class="wrapper-fieldset-forms">
		<fieldset id="provider-fieldset-contact-details"
			class="fieldset-forms">
			<legend>Contact Details</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Name:</label></li>
				<li><input type="text" maxlength="100" name="contact_name"
					value="<?php echo $contact_name; ?>" /></li>
				<li>Email:</li>
				<li><input type="text" maxlength="100" name="contact_email"
					value="<?php echo $contact_email; ?>" /></li>
				<li>Cellphone:</li>
				<li><input type="text" maxlength="100" name="contact_cellphone"
					value="<?php echo $contact_cellphone; ?>" /></li>
				<li>Landline:</li>
				<li><input type="text" maxlength="100" name="contact_landline"
					value="<?php echo $contact_landline; ?>" /></li>
				<li>Fax:</li>
				<li><input type="text" maxlength="100" name="contact_fax"
					value="<?php echo $contact_fax; ?>" /></li>
				<li>Contact type:</li>
				<li><select name="contact_type">
						<option value="0">Please choose a contact type</option>
                <?php foreach ($preferred_contact_types as $type) {   
                    $selected = $contact_type === $type['PreferredContactTypePk'] ? 'selected="' . $type['PreferredContactTypePk'] . '"':'';
                ?>
                    <option <?php echo $selected; ?>
							value="<?php echo $type['PreferredContactTypePk']; ?>"><?php echo $type['Value']; ?></option>
                <?php } ?>
                </select></li>
			</ul>
		</fieldset>
		<div class="clear"></div>
	</div>
	<!-- end of contact building details -->

	<div id="building-service-meters" class="wrapper-fieldset-forms">
		<fieldset id="building-fieldset-contact-details"
			class="fieldset-forms">
			<legend>Service Meters / Common Property</legend>
			<ul class="fieldset-forms-li-3-cols">
				<li></li><li></li><li>Apply Bulk Rate</li>
			</ul>
			<ul class="fieldset-forms-li-3-cols">
				<li>Service meter allocation:</li>
				<li><select name="building_service_meter_allocation">
						<option value="">Please choose a method</option>
                <?php foreach ($allocation_list as $allocation) {
                    if ($allocation['IsServiceMeter'] == 1) {
                        $selected = $building_meter_allocationPK == $allocation['AllocationTypePk'] ? 'selected="' . $allocation['AllocationTypePk'] . '"':'';
                ?>
                    <option <?php echo $selected; ?>
							value="<?php echo $allocation['AllocationTypePk']; ?>"><?php echo $allocation['Value']; ?></option>
                <?php } } ?>
                </select></li>
                <li><input type="checkbox" <?php echo $building_isServicMeterBulkRate; ?>
					name="building_isServicMeterBulkRate" /></li>
            </ul>
            <ul class="fieldset-forms-li-3-cols">
				<li>Common property allocation:</li>
				<li><select name="building_service_property_allocation">
						<option value="">Please choose a method</option>
                <?php foreach ($allocation_list as $allocation) {   
                    if ($allocation['IsCommonProperty'] == 1) {
                        $selected = $building_property_allocationPK == $allocation['AllocationTypePk'] ? 'selected="' . $allocation['AllocationTypePk'] . '"':''; 
                ?>
                    <option <?php echo $selected; ?>
							value="<?php echo $allocation['AllocationTypePk']; ?>"><?php echo $allocation['Value']; ?></option>
                <?php } } ?>
                </select></li>
                <li><input type="checkbox" <?php echo $building_isCommonPropertyBulkRate; ?>
					name="building_isCommonPropertyBulkRate" /></li>
             </ul>
			 <ul class="fieldset-forms-li-3-cols"> 
				<li>Individual water meters:</li>
                <li style="text-align:left; padding-top:10px; height:27px">
				    <input type="checkbox" <?php echo $building_isIndividualWaterMeter; ?>
					name="building_isIndividualWaterMeter" /></li>
				<li></li>
             </ul>
             <ul class="fieldset-forms-li-3-cols">
				<li>Bulk water allocation:</li>
				<li><select name="building_service_bulk_water">
						<option value="0">Please choose a method</option>
                <?php foreach ($allocation_list as $allocation) {   
                    if ($allocation['IsBulkWater'] == 1) {
                        $selected = $building_bulk_allocationPK == $allocation['AllocationTypePk'] ? 'selected="' . $allocation['AllocationTypePk'] . '"':''; 
                ?>
                    <option <?php echo $selected; ?>
							value="<?php echo $allocation['AllocationTypePk']; ?>"><?php echo $allocation['Value']; ?></option>
                <?php } } ?>
                </select></li> 
                <li><input type="checkbox" <?php echo $building_isBulkWaterBulkRate; ?>
					name="building_isBulkWaterBulkRate" /></li>   
			</ul>
		</fieldset>
		<fieldset id="building-fieldset-contact-comments"
			class="fieldset-forms">
			<legend>Triple MMM parameters</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li>Read only:</li>
				<li><input type="checkbox" <?php echo $building_isRead_only; ?>
					name="building_isRead_only" /></li>
				<li>Standard deposit:</li>
				<li><input type="text"
					class="fieldset-forms-1half-length-input input-decimal"
					name="building_standard_deposit"
					value="<?php echo $building_standard_deposit; ?>" /></li>
			</ul>
		</fieldset>
		<div class="clear"></div>
	</div>
	<!-- end of services meters/common property -->

	<div id="building-rates" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>Rate Account</legend>
			<ul class="fieldset-forms-li-4-cols">
				<li>Utility:</li>
				<li class="building-rate-li">Rate:</li>
				<li class="center-li-contents">Provider Account:</li>
				<li class="center-li-contents">Show steps:</li>
				<li class="center-li-contents">Active:</li>
			</ul>
			<div id="building-rate-account-add-content">
        <?php
        if (!empty($rate_account_list)) {
            foreach ($rate_account_list as $account) {
                $utilityPK = $account['UtilityTypeFk'];
                $ratePk = $account['RateFk'];
                $is_showSteps = $account['IsStepsShown'] ? 'checked':'';
                $isActive = $account['IsActive'] ? 'checked':'';
        ?>
        <ul class="fieldset-forms-li-4-cols">
					<li><select name="rate_utility_values[]" class="rate-account-utility">
							<option value="0">Please choose a utility</option>
                    <?php 
                    if (!empty($utility_list)) {
                        foreach ($utility_list as $utility) {
                        $selected = $utilityPK == $utility['UtilityTypePk'] ? 'selected="' . $utility['UtilityTypePk'] . '"':'';
                    ?>
                    <option <?php echo $selected; ?>
								value="<?php echo $utility['UtilityTypePk']; ?>"><?php echo $utility['Value']; ?></option>
                    <?php } } ?>
                </select></li>
					<li class="building-rate-li"><select name="rate_values[]" class="rate-account-rate">
							<option value="0">Please choose a rate</option>
                    <?php 
                    if (!empty($rate_list)) {
                        foreach ($rate_list as $rate) {
						if ($utilityPK == $rate['UtilityTypeFk']) {
                        $selected = $ratePk == $rate['RatePk'] ? 'selected="' . $rate['RatePk'] . '"':'';
                    ?>
                    <option <?php echo $selected; ?>
								value="<?php echo $rate['RatePk']; ?>"><?php echo $rate['Name']; ?></option>
                    <?php } } ?>
                </select></li>
					<li class="center-li-contents"><input type="text" class="fieldset-forms-1half-length-input"
						value="<?php echo $account['AccountNumber']; ?>"
						name="rate_account_numbers[]" /> <input type="hidden"
						name="rate_account_PK[]"
						value="<?php echo $account['BuildingRateAccountPk']; ?>" /></li>
					<li class="center-li-contents"><input type="checkbox" name="rate_is_showSteps_temp[]"
						<?php echo $is_showSteps; ?> /> <input type="hidden"
						name="rate_is_showSteps_values[]"
						value="<?php echo $account['IsStepsShown']; ?>" /></li>
					<li class="center-li-contents"><input type="checkbox" name="rate_isActive_temp[]"
						<?php echo $isActive; ?> /> <input type="hidden"
						name="rate_isActive_values[]"
						value="<?php echo $account['IsActive']; ?>" /></li>
				</ul>
        <?php } } } else { ?>
         <ul class="fieldset-forms-li-4-cols">
					<li><select name="rate_utility_values[]" class="rate-account-utility">
							<option value="0">Please choose a utility</option>
                    <?php 
                    if (!empty($utility_list)) {
                        foreach ($utility_list as $utility) {    
                    ?>
                    <option
								value="<?php echo $utility['UtilityTypePk']; ?>"><?php echo $utility['Value']; ?></option>
                    <?php } } ?>
                </select></li>
					<li class="building-rate-li"><select name="rate_values[]" class="rate-account-rate">
							<option value="0">Please choose a rate</option>
                </select></li>
					<li class="center-li-contents"><input type="text" maxlength="100"
						class="fieldset-forms-1half-length-input"
						name="rate_account_numbers[]" /> <input type="hidden"
						name="rate_account_PK[]" value="0" /></li>
					<li class="center-li-contents"><input type="checkbox" name="rate_is_showSteps_temp[]" checked />
						<input type="hidden" name="rate_is_showSteps_values[]" value="1" />
					</li>
					<li class="center-li-contents"><input type="checkbox" name="rate_isActive_temp[]" checked />
						<input type="hidden" name="rate_isActive_values[]" value="1" />
					</li>
				</ul>   
        <?php } ?>
        </div>
			<div class="clear"></div>
			<div class="selection-form-submit float-left">
				<button id="building-rate-account-add-button" class="addline-button">Add</button>
			</div>
			<div id="building-rate-account-addline-error-box"
				class="addline-error-box error-box float-left hidden"></div>
			<div class="clear"></div>
		</fieldset>
		<div class="clear"></div>
	</div>
	<!-- end of contact building details -->

	<div class="wrapper-fieldset-forms <?php echo $view_class; ?>">
		<div class="submit-error-box error-box hidden"></div>
		<div class="form-submit">
			<input type="submit" value="Update"
				class="submit-positive building-save-button" name="Update" /> <input
				type="submit" value="Cancel" class="submit-netagive" name="Cancel" />
		</div>
	</div>
	<!-- end of form submit buttons -->
	<?php if($restriction_level > 0){ ?>
		<div class="wrapper-fieldset-forms <?php echo $create_class; ?>">
			<div id="building-submit-error-box" class="submit-error-box error-box warning-box warning hidden"></div>
			<div class="form-submit">
				<input class="submit-positive building-save-button" type="submit" value="Create" name="Create" /> <input style="width: 200px;" class="submit-positive building-save-button" type="submit" value="Save & Create Units" name="Save_and_Create" /> 
				<input class="submit-netagive" type="submit" value="Cancel" name="Cancel" />
			</div>
		</div>
		<!-- end of form submit buttons -->
	<?php }?>
</form>
<!-- end of post form -->
<?php
}
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>