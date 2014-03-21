<?php
$page_name = 'COMPANY';

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

//Address
$Address = new Address($dbh);
$addressPK = 0;

//Company
$Company = new Company($dbh);
$company_data = NULL;
$companyPK = 0;
$company_name = '';
$company_registration_number = '';
$company_vat_registration = '';
$company_product_key = '';
$company_address_1 = '';
$company_address_2 = '';
$company_address_3 = '';
$company_address_4 = '';
$company_postal_code = '';
$company_email = '';
$company_land_line = '';
$company_after_hours_number = '';
$company_fax = '';
$company_deviation_average = '';
$company_deviation_second_reading = '';
$company_database = '';
$company_path = '';
$company_cut_notification = '';
$company_disconnection_charge = '';
$company_water_restriction_charge = '';
$company_payment_threshold = '';
$company_company_code = '';
$company_utility_type_segment = '';
$company_provider_segment = '';
$company_building_segment = '';
$company_sales_account = '';
$company_cost_sales_account = '';
$company_deposit_erp_segment = '';
$company_notification_erp_segment = '';
$company_reconnection_erp_segment = '';
$company_provision_account = '';
$company_provision_batch_id = '';
$company_debtors_control_account = '';
$company_tax_control_account = '';

// UI
$view_class = 'hidden';
$submit_button = '';
$add_submit_id = '';

//View Button Actions
if(isset($_GET['View'])){
	//UI
	$view_class = 'show';
	$submit_button = 'Update';
	$add_submit_id = '';
	
	$companyPK = Sanitize::cleanWholeNumber($_GET['choose_company']);
	//Company		
	$company_data = $Company->getCompany(array($userPK, $companyPK));	
	$company_data = $Company->getSingleRecord($company_data);
	
	//Address	
	$address_data = $Address->getAddress(array($userPK, $company_data['CompanyAddressFk']));
    $address_data = $Address->getSingleRecord($address_data);	
	
	//Company
	$company_name = $company_data['CompanyName'];
	$company_registration_number = $company_data['RegistrationNumber'];
	$company_vat_registration = $company_data['VatRegistrationNumber'];
	$company_product_key = $company_data['ProductKey'];
	$company_address_1 = $address_data['Address1'];
	$company_address_2 = $address_data['Address2'];
	$company_address_3 = $address_data['Address3'];
	$company_address_4 = $address_data['Address4'];
	$company_postal_code = $address_data['PostalCode'];
	$company_email = $company_data['CompanyEmail'];
	$company_land_line = $company_data['CompanyAlternatePhone'];
	$company_after_hours_number = $company_data['CompanyEmergencyPhone'];
	$company_fax = $company_data['CompanyFax'];
	$company_deviation_average = $company_data['MeterDeviation'];
	$company_deviation_second_reading = $company_data['Input2Deviation'];
	$company_database = $company_data['Database'];
	$company_path = $company_data['path'];
	$company_cut_notification = $company_data['CutNotificationCharge'];	
	$company_disconnection_charge = $company_data['ReconnectionFee'];	
	$company_water_restriction_charge = $company_data['WaterRestrictionFee'];	
	$company_payment_threshold = $company_data['OverdueThreshold'];
	$company_company_code = $company_data['CompanyERPCode'];
	$company_utility_type_segment = $company_data['UtilityTypeERPSegment'];
	$company_provider_segment = $company_data['ProviderERPSegment'];
	$company_building_segment = $company_data['BuildingERPSegment'];
	$company_sales_account = $company_data['SalesERPAccount'];
	$company_cost_sales_account = $company_data['CostOfSalesERPAccount'];
	$company_deposit_erp_segment = $company_data['DepositERPSegment'];
	$company_notification_erp_segment = $company_data['NotificationERPSegment'];
	$company_reconnection_erp_segment = $company_data['ReconnectionERPSegment'];
	
}

//Create Button Actions(HTTP GET METHOD)
else if(isset($_GET['Create'])){
	//UI
	$view_class = 'show';
	$submit_button = 'Create';
	$add_submit_id = 'id="company-submit-buttons"';
}

//Create Button Actions(HTTP POST METHOD)
if(isset($_POST['Create'])){
	
	//Company Details
	//Company Name	
	if(isset($_POST['company_name']) || !empty($_POST['company_name'])){ $company_name = Sanitize::sanitizeStr($_POST['company_name']); }
	else{ $company_name = NULL; }
	//Registration Number
	if(isset($_POST['registration_number']) || !empty($_POST['registration_number'])){ $company_registration_number = Sanitize::sanitizeStr($_POST['registration_number']); }
	else{ $company_registration_number = NULL; }
	//Vat Registration Number
	if(isset($_POST['vat_registration']) || !empty($_POST['vat_registration'])){ $company_vat_registration = Sanitize::sanitizeStr($_POST['vat_registration']); }
	else{ $company_vat_registration = NULL; }
	//Product key
	if(isset($_POST['product_key']) || !empty($_POST['product_key'])){ $company_product_key = Sanitize::sanitizeStr($_POST['product_key']); }
	else{ $company_product_key = NULL; }
	
	//Contact Details
	//Address 1
	if(isset($_POST['address_line_1']) || !empty($_POST['address_line_1'])){ $company_address_1 = Sanitize::sanitizeStr($_POST['address_line_1']); }
	else{ $company_address_1 = NULL; }
	//Address 2
	if(isset($_POST['address_line_2']) || !empty($_POST['address_line_2'])){ $company_address_2 = Sanitize::sanitizeStr($_POST['address_line_2']); }
	else{ $company_address_2 = NULL; }
	//Address 3
	if(isset($_POST['address_line_3']) || !empty($_POST['address_line_3'])){ $company_address_3 = Sanitize::sanitizeStr($_POST['address_line_3']); }
	else{ $company_address_3 = NULL; }
	//Address 4
	if(isset($_POST['address_line_4']) || !empty($_POST['address_line_4'])){ $company_address_4 = Sanitize::sanitizeStr($_POST['address_line_4']); }
	else{ $company_address_4 = NULL; }
	//Postal Code
	if(isset($_POST['postal_code']) || !empty($_POST['postal_code'])){ $company_postal_code = Sanitize::sanitizeStr($_POST['postal_code']); }
	else{ $company_postal_code = NULL; }
	//Company E-mail
	if(isset($_POST['email']) || !empty($_POST['email'])){ $company_email = Sanitize::cleanEmail($_POST['email']); }
	else{ $company_email = NULL; }
	//Company Alternate Phone
	if(isset($_POST['land_line']) || !empty($_POST['land_line'])){ $company_land_line = Sanitize::sanitizeStr($_POST['land_line']); }
	else{ $company_land_line = NULL; }
	//Company Emergency Phone
	if(isset($_POST['after_hours_number']) || !empty($_POST['after_hours_number'])){ $company_after_hours_number = Sanitize::sanitizeStr($_POST['after_hours_number']); }
	else{ $company_after_hours_number = NULL; }
	//Company Fax
	if(isset($_POST['fax']) || !empty($_POST['fax'])){ $company_fax = Sanitize::sanitizeStr($_POST['fax']); }
	else{ $company_fax = NULL; }
	
	//Movendus
	//Meter Deviation
	$company_deviation_average = !empty($_POST['deviation_average']) && $_POST['deviation_average'] > 0 ? $_POST['deviation_average'] : 0;
	//Meter Deviation Second Reading
	$company_deviation_second_reading = !empty($_POST['deviation_second_reading']) && $_POST['deviation_second_reading'] > 0 ? $_POST['deviation_second_reading'] : 0;
	
	//Database
	$company_database = !empty($_POST['database']) ? $_POST['database']:NULL;
	$company_path = !empty($_POST['directory']) ? $_POST['directory']:NULL;
	
	//Global Charges
	//Cut Notification
	$company_cut_notification = !empty($_POST['cut_notification']) && $_POST['cut_notification'] > 0 ? $_POST['cut_notification'] : 0;
	//Disconnnection Charge
	$company_disconnection_charge = !empty($_POST['disconnection_charge']) && $_POST['disconnection_charge'] > 0 ? $_POST['disconnection_charge'] : 0;
	//Water Restriction Charge
	$company_water_restriction_charge = !empty($_POST['water_restriction_charge']) && $_POST['water_restriction_charge'] > 0 ? $_POST['water_restriction_charge'] : 0;
	//Payment Threshold
	$company_payment_threshold = !empty($_POST['payment_threshold']) && $_POST['payment_threshold'] > 0 ? $_POST['payment_threshold'] : 0;
	
	//ERP Integration
	//Company Code
	$company_company_code = !empty($_POST['company_code']) ? Sanitize::sanitizeStr($_POST['company_code']) : NULL;
	//Utility Type Segment
	$company_utility_type_segment = !empty($_POST['utility_type_segment']) ? Sanitize::sanitizeStr($_POST['utility_type_segment']) : NULL;
	//Provider Segment
	$company_provider_segment = !empty($_POST['provider_segment']) ? Sanitize::sanitizeStr($_POST['provider_segment']) : NULL;
	//Building Segment
	$company_building_segment = !empty($_POST['building_segment']) ? Sanitize::sanitizeStr($_POST['building_segment']) : NULL;
	//Sales Account
	$company_sales_account = !empty($_POST['sales_account']) ? Sanitize::sanitizeStr($_POST['sales_account']) : NULL;
	//Cost of Sales Account
	$company_cost_sales_account = !empty($_POST['cost_sales_account']) ? Sanitize::sanitizeStr($_POST['cost_sales_account']) : NULL;
	//Cost of Sales Account
	$company_deposit_erp_segment = !empty($_POST['deposit_erp_segment']) ? Sanitize::sanitizeStr($_POST['deposit_erp_segment']) : NULL;
	//Cost of Sales Account
	$company_notification_erp_segment = !empty($_POST['Notification_ERP_Segment']) ? Sanitize::sanitizeStr($_POST['Notification_ERP_Segment']) : NULL;
	//Cost of Sales Account
	$company_reconnection_erp_segment = !empty($_POST['Reconnection_ERP_Segment']) ? Sanitize::sanitizeStr($_POST['Reconnection_ERP_Segment']) : NULL;
	//Provision Account
	$company_provision_account = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : NULL;
	//Provision Batch Id
	$company_provision_batch_id = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : 0;
	//Debtors Control Account
	$company_debtors_control_account = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : 0;
	//Tax Control Account
	$company_tax_control_account = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : 0;
	
	//Address
	$address_params = array(
		$userPK,
		0,
		$company_address_1,
		$company_address_2,
		$company_address_3,
		$company_address_4,
		$company_postal_code
	);
	
	//Get Last Inserted Id for Address
	$AddressLastInsertedId = $Address->createAddress($address_params);
	
	//Company
	$company_params = array(
		$userPK,
		0,//Set CompanyPk to 0 when insert, 1 when select or update
		$company_name,
		$company_registration_number,
		$company_vat_registration,
		$company_product_key,
		$AddressLastInsertedId,
		$company_email,
		$company_land_line,
		$company_after_hours_number,
		$company_fax,
		$company_deviation_average,
		$company_deviation_second_reading,
		$company_database,//database column		 
		$company_path,//path column
		$company_cut_notification,
		$company_disconnection_charge,
		$company_water_restriction_charge,
		$company_payment_threshold,
		$company_company_code,
		$company_utility_type_segment,
		$company_provider_segment,
		$company_building_segment,
		$company_sales_account,
		$company_cost_sales_account,
		$company_deposit_erp_segment,
		$company_notification_erp_segment,
		$company_reconnection_erp_segment,
		$company_reconnection_erp_segment,
		$company_provision_account,
		$company_provision_batch_id,
		$company_debtors_control_account,
		$company_tax_control_account
	);
	
	//Get Last Inserted Id for Company
	$CompanyLastInsertedId = $Company->createCompany($company_params);
	
	if(!empty($CompanyLastInsertedId) || $CompanyLastInsertedId > 0){
		$Session->write('Success', '<strong>' . $company_name . '</strong> created successfully.');
		header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));		
		exit();		
	}
}

//Update Button Action
else if(isset($_POST['Update'])){	
	$companyPK = Sanitize::cleanWholeNumber($_GET['choose_company']);
	
	//Company		
	$company_data = $Company->getCompany(array($userPK, $companyPK));	
	$company_data = $Company->getSingleRecord($company_data);
	
	//Address	
	$address_data = $Address->getAddress(array($userPK, $company_data['CompanyAddressFk']));
    $address_data = $Address->getSingleRecord($address_data);	
	
	//Company Details
	$company_name = !empty($_POST['company_name']) ? Sanitize::sanitizeStr($_POST['company_name']) : NULL;
	$company_registration_number = !empty($_POST['registration_number']) ? Sanitize::sanitizeStr($_POST['registration_number']) : NULL;
	$company_vat_registration = !empty($_POST['vat_registration']) ? Sanitize::sanitizeStr($_POST['vat_registration']) : NULL;
	$company_product_key = !empty($_POST['product_key']) ? Sanitize::sanitizeStr($_POST['product_key']) : NULL;
	
	//Contact Details
	$company_address_1 = !empty($_POST['address_line_1']) ? Sanitize::sanitizeStr($_POST['address_line_1']) : NULL;
	$company_address_2 = !empty($_POST['address_line_2']) ? Sanitize::sanitizeStr($_POST['address_line_2']) : NULL;
	$company_address_3 = !empty($_POST['address_line_3']) ? Sanitize::sanitizeStr($_POST['address_line_3']) : NULL;
	$company_address_4 = !empty($_POST['address_line_4']) ? Sanitize::sanitizeStr($_POST['address_line_4']) : NULL;
	$company_postal_code = !empty($_POST['postal_code']) ? Sanitize::sanitizeStr($_POST['postal_code']) : NULL;
	$company_email = !empty($_POST['email']) ? Sanitize::cleanEmail($_POST['email']) : NULL;
	$company_land_line = !empty($_POST['land_line']) ? Sanitize::sanitizeStr($_POST['land_line']) : NULL;
	$company_after_hours_number = !empty($_POST['after_hours_number']) ? Sanitize::sanitizeStr($_POST['after_hours_number']) : NULL;
	$company_fax = !empty($_POST['fax']) ? Sanitize::sanitizeStr($_POST['fax']) : NULL;
	
	//Movendus
	$company_deviation_average = !empty($_POST['deviation_average']) && $_POST['deviation_average'] > 0 ? $_POST['deviation_average'] : 0;
	$company_deviation_second_reading = !empty($_POST['deviation_second_reading']) && $_POST['deviation_second_reading'] > 0 ? Sanitize::cleanWholeNumber($_POST['deviation_second_reading']) : 0;
	
	//Database
	$company_database = !empty($_POST['database']) ? $_POST['database']:NULL;
	$company_path = !empty($_POST['directory']) ? $_POST['directory']:NULL;
	
	//Global Charges
	$company_cut_notification = !empty($_POST['cut_notification']) && $_POST['cut_notification'] > 0 ? $_POST['cut_notification'] : 0;
	$company_disconnection_charge = !empty($_POST['disconnection_charge']) && $_POST['disconnection_charge'] > 0 ? $_POST['disconnection_charge'] : 0;
	$company_water_restriction_charge = !empty($_POST['water_restriction_charge']) && $_POST['water_restriction_charge'] > 0 ? $_POST['water_restriction_charge'] : 0;
	$company_payment_threshold = !empty($_POST['payment_threshold']) && $_POST['payment_threshold'] > 0 ? $_POST['payment_threshold'] : 0;
	
	//ERP Integration
	$company_company_code = !empty($_POST['company_code']) ? Sanitize::sanitizeStr($_POST['company_code']) : '';
	$company_utility_type_segment = !empty($_POST['utility_type_segment']) ? Sanitize::sanitizeStr($_POST['utility_type_segment']) : '';
	$company_provider_segment = !empty($_POST['provider_segment']) ? Sanitize::sanitizeStr($_POST['provider_segment']) : '';
	$company_building_segment = !empty($_POST['building_segment']) ? Sanitize::sanitizeStr($_POST['building_segment']) : '';
	$company_sales_account = !empty($_POST['sales_account']) ? Sanitize::sanitizeStr($_POST['sales_account']) : '';
	$company_cost_sales_account = !empty($_POST['cost_sales_account']) ? Sanitize::sanitizeStr($_POST['cost_sales_account']) : '';
	$company_deposit_erp_segment = !empty($_POST['deposit_erp_segment']) ? Sanitize::sanitizeStr($_POST['deposit_erp_segment']) : '';
	$company_notification_erp_segment = !empty($_POST['notification_erp_segment']) ? Sanitize::sanitizeStr($_POST['notification_erp_segment']) : '';
	$company_reconnection_erp_segment = !empty($_POST['reconnection_erp_segment']) ? Sanitize::sanitizeStr($_POST['reconnection_erp_segment']) : '';
	$company_reconnection_erp_segment = !empty($_POST['reconnection_erp_segment']) ? Sanitize::sanitizeStr($_POST['reconnection_erp_segment']) : '';	
	$company_provision_account = !empty($_POST['Reconnection_ERP_Segment']) ? Sanitize::sanitizeStr($_POST['Reconnection_ERP_Segment']) : NULL;
	$company_provision_batch_id = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : 0;
	$company_debtors_control_account = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : 0;
	$company_tax_control_account = !empty($_POST['Reconnection_ERP_Segment']) ? $_POST['Reconnection_ERP_Segment'] : 0;
	
	$address_params = array(
		$userPK,
		$company_data['CompanyAddressFk'],
		$company_address_1,
		$company_address_2,
		$company_address_3,
		$company_address_4,
		$company_postal_code
	);
	
	$Address->updateAddress($address_params);
	
	$company_params = array(
		$userPK,
		$companyPK,
		$company_name,
		$company_registration_number,
		$company_vat_registration,
		$company_product_key,
		$company_data['CompanyAddressFk'],
		$company_email,
		$company_land_line,
		$company_after_hours_number,
		$company_fax,
		$company_deviation_average,
		$company_deviation_second_reading,
		$company_database,//database column		 
		$company_path,//path column
		$company_cut_notification,
		$company_disconnection_charge,
		$company_water_restriction_charge,
		$company_payment_threshold,
		$company_company_code,
		$company_utility_type_segment,
		$company_provider_segment,
		$company_building_segment,
		$company_sales_account,
		$company_cost_sales_account,
		$company_deposit_erp_segment,
		$company_notification_erp_segment,
		$company_reconnection_erp_segment,
		$company_reconnection_erp_segment,
		$company_provision_account,
		$company_provision_batch_id,
		$company_debtors_control_account,
		$company_tax_control_account
	);
	
	$updateStatus = $Company->updateCompany($company_params);
	
	if(!empty($updateStatus)){
		$Session->write('Success', '<strong>' . $company_name . '</strong> updated successfully.');
		header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));		
		exit();		
	} 
}

//Cancel Button Actions
else if (isset($_POST['Cancel'])) { 
	header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));
	exit();
}

?>
<form method="get" class="hover-cursor-pointer" id="company-selection-form">
	<?php
		if($Session->check('Success')){ 
			echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
			$Session->sessionUnset('Success');
		}
	?>
	<div class="sub-menu-title">
		<h1>Company Master</h1>
	</div><!-- end of .sub-menu-title -->
	<div id="company-selection" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>Company Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Company number:</label></li>
				<li>  
					<select id="company-selection-company" name="choose_company" class="selection-required-input">
						<option value="0">Please choose a company</option>
					<?php 
						$company_list = $Company->getCompany(array($userPK, 0));
						foreach ($company_list as $company) { 
						$selected = $company['CompanyPk'] === $companyPK ? 'selected="' . $companyPK . '"':'';
					?>
						<option <?php echo $selected; ?> value="<?php echo $company['CompanyPk']; ?>"><?php echo $company['CompanyName']; ?></option>
					<?php } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="company-view-submit" type="submit" value="View" name="View"/>
				<?php if($restriction_level > 0){ ?>
				<input type="submit" value="Create" name="Create"/>
				<?php } ?>
			</div>
			<div id="company-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
			
		</fieldset>
	</div><!-- end of #company-selection -->	
</form> <!-- end of get form -->

<form id="company-data-form" method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
	<div id="company-details" class="wrapper-fieldset-forms">
		<fieldset id="company-fieldset-company-details" class="fieldset-forms">
			<legend>Company Details</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Company name:</label></li>
				<li>
					<input id="company_name" maxlength="100" type="text" name="company_name" value="<?php echo $company_name; ?>" />
				</li>
				<li><label>Registration number:</label></li>
				<li>
					<input type="text" id="company_registration_number" maxlength="100" name="registration_number" value="<?php echo $company_registration_number; ?>" />
				</li>
				<li><label>VAT registration:</label></li>
				<li>
					<input type="text" maxlength="100" name="vat_registration" value="<?php echo $company_vat_registration; ?>" />
				</li>
				<li><label>Product key:</label></li>
				<li><input type="text" maxlength="100" name="product_key" value="<?php echo $company_product_key; ?>" /></li>
			</ul>
		</fieldset>
	</div><!-- end of #company-details -->
	
	<div id="company-contact-details" class="wrapper-fieldset-forms">
		<fieldset id="company-fieldset-contact-details" class="fieldset-forms">
			<legend>Contact Details</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Address line 1:</label></li>
				<li><input type="text" maxlength="100" name="address_line_1" value="<?php echo $company_address_1; ?>" /></li>
				<li><label>Address line 2:</label></li>                                                    
				<li><input type="text" maxlength="100" name="address_line_2" value="<?php echo $company_address_2; ?>" /></li>
				<li><label>Address line 3:</label></li>                                                    
				<li><input type="text" maxlength="100" name="address_line_3" value="<?php echo $company_address_3; ?>" /></li>
				<li><label>Address line 4:</label></li>                                                    
				<li><input type="text" maxlength="100" name="address_line_4" value="<?php echo $company_address_4; ?>" /></li>
				<li><label>Postal code:</label></li>
				<li><input type="text" maxlength="10" class="fieldset-forms-1fourth-length-input" name="postal_code" value="<?php echo $company_postal_code; ?>" /></li>
				<li><label>Email:</label></li>
				<li><input type="text" maxlength="100" name="email" value="<?php echo $company_email; ?>" /></li>
				<li><label>Land line:</label></li>
				<li><input type="text" maxlength="100" class="fieldset-forms-1half-length-input" name="land_line" value="<?php echo $company_land_line; ?>" /></li>
				<li><label>After hours number:</label></li>
				<li><input type="text" maxlength="100" class="fieldset-forms-1half-length-input" name="after_hours_number" value="<?php echo $company_after_hours_number; ?>" /></li>
				<li><label>Fax:</label></li>
				<li><input type="text" maxlength="100" class="fieldset-forms-1half-length-input" name="fax" value="<?php echo $company_fax; ?>" /></li>
			</ul>
		</fieldset>
	</div><!-- end of #company-contact-details -->
	
	<div id="company-movendus" class="wrapper-fieldset-forms">
		<fieldset id="company-fieldset-company-movendus" class="fieldset-forms">
			<legend>Movendus</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Deviation; % average:</label></li>
				<li><input type="text" class="input-decimal" name="deviation_average" value="<?php echo $company_deviation_average; ?>" /></li>
				<li><label>Deviation; second reading:</label></li>
				<li><input type="text" class="input-integer" maxlength="10" name="deviation_second_reading" value="<?php echo $company_deviation_second_reading; ?>" /></li>
			</ul>
		</fieldset>
	</div><!-- end of #company-movendus -->
	
	<!-- Element where elFinder will be created (REQUIRED) -->
	<!--<div id="elfinder"></div>-->
	
	<div id="company-database" class="wrapper-fieldset-forms">
		<fieldset id="company-fieldset-company-database" class="fieldset-forms">
			<legend>Database</legend>			
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Database:</label></li>
				<li><input type="text" name="database" value="<?php echo $company_database; ?>" /></li>
				<li><label for="csv_path">Path:</label></li>
				<li><input class="csv_path" type="text" name="directory" id="directory" value="<?php echo $company_path; ?>" /></li>				
			</ul>
		</fieldset>
	</div><!-- end of #company-database -->
	
	<div id="company-global-charges" class="wrapper-fieldset-forms">
		<fieldset id="company-fieldset-company-global-charges" class="fieldset-forms">
			<legend>Global Charges</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Cut Notification:</label></li>
				<li><input type="text" class="cut_notification input-decimal" name="cut_notification" value="<?php echo $company_cut_notification; ?>" /></li>
				<li><label>Disconnection charge:</label></li>
				<li><input type="text" class="disconnection_charge input-decimal" name="disconnection_charge" value="<?php echo $company_disconnection_charge; ?>" /></li>
				<li><label>Water restriction charge:</label></li>
				<li><input type="text" class="water_restriction_charge input-decimal" name="water_restriction_charge" value="<?php echo $company_water_restriction_charge; ?>" /></li>
				<li><label>Payment threshold:</label></li>
				<li><input type="text" class="payment_threshold input-decimal" name="payment_threshold" value="<?php echo $company_payment_threshold; ?>" /></li>
			</ul>
		</fieldset>
	</div><!-- end of #company-global-charges -->
	
	<div id="company-erp-integration" class="wrapper-fieldset-forms">
		<fieldset id="company-fieldset-company-global-charges" class="fieldset-forms">
			<legend>ERP Integration</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Company Code:</label></li>
				<li><input type="text" class="company_code" name="company_code" value="<?php echo $company_company_code; ?>" /></li>
				<li><label>Utility Type Segment:</label></li>
				<li><input type="text" class="utility_type_segment" name="utility_type_segment" value="<?php echo $company_utility_type_segment; ?>" /></li>
				<li><label>Provider Segment:</label></li>
				<li><input type="text" class="provider_segment" name="provider_segment" value="<?php echo $company_provider_segment; ?>" /></li>
				<li><label>Building Segment:</label></li>
				<li><input type="text" class="building_segment" name="building_segment" value="<?php echo $company_building_segment; ?>" /></li>
				<li><label>Sales Account:</label></li>
				<li><input type="text" class="sales_account" name="sales_account" value="<?php echo $company_sales_account; ?>" /></li>
				<li><label>Cost of Sales Account:</label></li>
				<li><input type="text" class="cost_sales_account" name="cost_sales_account" value="<?php echo $company_cost_sales_account; ?>" /></li>
				<li><label>Deposit ERP Segment:</label></li>
				<li><input type="text" class="deposit_erp_segment" name="deposit_erp_segment" value="<?php echo $company_deposit_erp_segment; ?>" /></li>
				<li><label>Notification ERP Segment:</label></li>
				<li><input type="text" class="Notification_ERP_Segment" name="notification_erp_segment" value="<?php echo $company_notification_erp_segment; ?>" /></li>
				<li><label>Reconnection ERP Segment:</label></li>
				<li><input type="text" class="Reconnection_ERP_Segment" name="reconnection_erp_segment" value="<?php echo $company_reconnection_erp_segment; ?>" /></li>
				<li><label>Provision Account:</label></li>
				<li><input type="text" class="provision_account" name="provision_account" value="<?php echo $company_provision_account; ?>" /></li>
				<li><label>Provision Batch Id:</label></li>
				<li><input type="text" class="provision_batch_id" name="provision_batch_id" value="<?php echo $company_provision_batch_id; ?>" /></li>
				<li><label>Debtors Control Account:</label></li>
				<li><input type="text" class="debtors_control_account" name="debtors_control_account" value="<?php echo $company_debtors_control_account; ?>" /></li>
				<li><label>Tax Control Account:</label></li>
				<li><input type="text" class="tax_control_account" name="tax_control_account" value="<?php echo $company_tax_control_account; ?>" /></li>			</ul>
		</fieldset>
	</div><!-- end of #company-global-charges -->
	
	<div <?php echo $add_submit_id; ?> class="wrapper-fieldset-forms hover-cursor-pointer <?php echo $view_class; ?>">
		<div id="company-warning-box" class="warning warning-box hidden"></div>
		<?php if($restriction_level > 0){ ?>
		<div class="form-submit">
			<input type="submit" value="<?php echo $submit_button; ?>" class="submit-positive" name="<?php echo $submit_button; ?>" />
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel" />
		</div>
		<?php } ?>
	</div>
</form><!-- end of #company-data-form -->

<?php require DOCROOT . '/template/footer.php'; ?>

<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/res/directory-picker/css/directory-picker.css" />

<script type="text/javascript" src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/directory-picker/js/directory-picker.min.js"></script>
<script type="text/javascript">
	(function(){
		$('#directory').directory_picker({
			script: '<?php echo DOMAIN_NAME; ?>/res/directory-picker/php/dirlist.php',
			pick_files: 0
		});
	})(jQuery);
</script>
