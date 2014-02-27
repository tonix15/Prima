<?php
require_once '../init.php';
$page_name = 'CUSTOMERS';
$companyPK =  $_SESSION['user_company_selection_key'];

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
$rate_account_list = null;
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

// Title
$Title = new TitleType($dbh);
$title_list = null;

// Preffered Contact Type
$PreferedContactType = new PreferredContactType($dbh);

// Language
$Language = new LanguageType($dbh);

// Contact Person
$ContactPerson = new ContactPerson($dbh);
$contact_cellphone = '';
$contact_landline = '';
$contact_fax = '';
$contact_email = '';
$contact_typePK = 0;
$contact_address1 = '';
$contact_address2 = '';
$contact_address3 = '';
$contact_address4 = '';
$contact_postal_code = '';

// Employer
$employer_name = '';
$employer_landline = '';
$employer_fax = '';
$employer_address1 = '';
$employer_address2 = '';
$employer_address3 = '';
$employer_address4 = '';
$employer_postal_code = '';

// Reference
$reference_name = '';
$reference_landline = '';
$reference_fax = '';
$reference_address1 = '';
$reference_address2 = '';
$reference_address3 = '';
$reference_address4 = '';
$reference_postal_code = '';

// Unit
$Unit = new Unit($dbh);
$unitPK = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit'] : 0;
$unit_list = null;

// Customer
$Customer = new Customer($dbh);
$customer_data = null;
$customer_title_typePk = 0;
$customer_initials = '';
$customer_surname = '';
$customer_name = '';
$customer_idno = '';
$customer_language_typePK = 0;
$customer_company_name = '';
$customer_company_registration_no = '';
$customer_vat_no = '';
$customer_comments = '';
$customer_arrangement_date = '';

// Billing Account
$Billing = new Billing($dbh);
$billing_account_list = null;
$billing_account_data = null;
$billing_accountPK = !empty($_GET['choose_tenant']) ? (int) $_GET['choose_tenant'] : 0;
$billing_occupancy_date = '';
$billing_vacancy_date = '';
$billing_deposit_required = "0.00";
$billing_isDeposit_refundable = '';
$billing_isAgreement_received = '';
$billing_isPrepaid = '';
$billing_isOwner = '';
$billing_owner_email = '';
$new_billing_accountPK = 0;

// Address
$Address = new Address($dbh);


//sage
$sage = new Sage($dbh);


// UI
$view_class = 'hidden';
$save_name = '';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_GET['View']) || isset($_GET['Create'])) {
    $view_class = 'show';
    $title_list = $Title->getTitleType(array($userPK, 0));
    $language_list = $Language->getLanguageType(array($userPK, 0));
    $preferred_contact_types = $PreferedContactType->getPreferredContactType(array($userPK, 0));
	$sage->ExportCustomer(array($companyPK));

}

if (isset($_POST['Create']) || isset($_POST['Update'])) {
    $view_class = 'hidden';
    $sage->ExportCustomer(array($companyPK));
    $customer = array('title', 'initials', 'surname', 'name', 'idno', 'language',
        'company_name', 'company_registration_no', 'vat_no', 'comments', 'arrangement_date');
    $billing = array('occupancy_date', 'vacancy_date', 'deposit_required', 
        'isDeposit_refundable', 'isAgreement_received', 'isPrepaid', 'isOwner', 'owner_email');
    $employer = array('name', 'landline', 'fax', 'address1', 'address2', 
        'address3', 'address4', 'postal_code');
    $contact = array('cellphone', 'email', 'landline', 'fax', 'type',
        'address1', 'address2', 'address3', 'address4', 'postal_code');
    
    // Billing
    $billing_len = count($billing);
    for ($i = 0; $i < $billing_len; $i++) { // create variable dynamically from posted billing account values
        ${'billing_' . $billing[$i]} = !empty($_POST['billing_' . $billing[$i]]) ? trim($_POST['billing_' . $billing[$i]]) : '';
    }
    
    // Customer
    $customer_len = count($customer);
    for ($i = 0; $i < $customer_len; $i++) { // create variable dynamically from posted billing account values
        ${'customer_' . $customer[$i]} = !empty($_POST['customer_' . $customer[$i]]) ? trim($_POST['customer_' . $customer[$i]]) : '';
    }
    
    // Employer and Reference
    $employer_len = count($employer);
    for ($i = 0; $i < $employer_len; $i++) { // create variable dynamically from posted billing account values
        ${'employer_' . $employer[$i]} = !empty($_POST['employer_' . $employer[$i]]) ? trim($_POST['employer_' . $employer[$i]]) : '';
        ${'reference_' . $employer[$i]} = !empty($_POST['reference_' . $employer[$i]]) ? trim($_POST['reference_' . $employer[$i]]) : '';
    }
    
    // Contact
    $contact_len = count($contact);
    for ($i = 0; $i < $contact_len; $i++) { // create variable dynamically from posted billing account values
        ${'contact_' . $contact[$i]} = !empty($_POST['contact_' . $contact[$i]]) ? trim($_POST['contact_' . $contact[$i]]) : '';
    }
    
} else if (isset($_POST['Cancel'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['Create']) || (isset($_POST['customer_new_buildingPK'], $_POST['customer_new_unitPK']) && // check if the customer moved to a new unit
	$_POST['customer_new_buildingPK'] > 0 && $_POST['customer_new_unitPK'] > 0)) {
	$sage->ExportCustomer(array($companyPK));
	$latest_billing_account = null;
	$isCustomerMoved = false;
	
	// When customer moves to another unit
	if (isset($_POST['customer_new_buildingPK'], $_POST['customer_new_unitPK']) &&
		$_POST['customer_new_buildingPK'] > 0 && $_POST['customer_new_unitPK'] > 0) {
		$isCustomerMoved = true;
	}
	
	
	
	if (isset($_POST['customer_new_buildingPK'], $_POST['customer_new_unitPK']) && $isCustomerMoved === true) {
		$buildingPK = $_POST['customer_new_buildingPK'];
		$unitPK = $_POST['customer_new_unitPK'];
	}
	
	$isPrepaid = !empty($billing_isPrepaid) ? true : false;
	$current_billing_accounts = $Billing->getBillingAccount(array($userPK, 0, $buildingPK, $unitPK, 1));
	$latest_account = null;
	
	if ($isPrepaid) {
		foreach ($current_billing_accounts as $account) {
			if (substr_count($account['NumberBk'], 'PP') > 0) {
				if (is_null($latest_account)) {
					$latest_account = $account;
				} else {
					if ((int) substr($account['NumberBk'], 2) > (int) substr($latest_account['NumberBk'], 2)) {
						$latest_account = $account;
					}
				}
			}
		}

		$new_tenant_number = !empty($latest_account['NumberBk']) ? 'PP' . ((int) substr($latest_account['NumberBk'], 2) + 1) : 'PP';
	} else {
		foreach ($current_billing_accounts as $account) {
			if (substr_count($account['NumberBk'], 'PP') === 0) {
				if (is_null($latest_account)) {
					$latest_account = $account;
				} else {
					if ((int) $account['NumberBk'] > (int) $latest_account['NumberBk']) {
						$latest_account = $account;
					}
				}
			}
		}

		$new_tenant_number = !empty($latest_account['NumberBk']) ? ((int) $latest_account['NumberBk']) + 1 : '01';
		$new_tenant_number = strlen($new_tenant_number) == 1 ? '0' . $new_tenant_number : $new_tenant_number;
	}
    
    // $new_tenant_number = $isPrepaid === true ? 'PP' . $new_tenant_number : $new_tenant_number;

    $new_billing_accountPK = $Billing->createBillingAccount(
        array(
            $userPK,
            0,
            $isCustomerMoved ? $_POST['customer_new_buildingPK'] : $buildingPK,
            $isCustomerMoved ? $_POST['customer_new_unitPK'] : $unitPK,
            $new_tenant_number,
            '', // ERPCode
            !empty($billing_deposit_required) ? $billing_deposit_required : 0,
            !empty($billing_isDeposit_refundable) ? 1 : 0,
            !empty($billing_isAgreement_received) ? 1 : 0,
            !empty($billing_isOwner) ? 1 : 0,
            $billing_owner_email,
            !empty($billing_isPrepaid) ? 1 : 0,
            $billing_occupancy_date,
            !empty($billing_vacancy_date) ? $billing_vacancy_date : '2100-01-01'
        )     
    );
    
	//StoredProcedures::displayParams($new_billing_accountPK);
    // New Address record for Employer, Reference and Contact details
    $contacts = array('contact', 'employer', 'reference');
    $contacts_len = count($contacts);
    for ($i = 0; $i < $contacts_len; $i++) {
        ${'new_' . $contacts[$i] . '_addressPK'} = $Address->createAddress(
            array(
                $userPK,
                0, // new address
                ${$contacts[$i] . '_address1'},
                ${$contacts[$i] . '_address2'},
                ${$contacts[$i] . '_address3'},
                ${$contacts[$i] . '_address4'},
                ${$contacts[$i] . '_postal_code'}   
            )
        );
        
    } 
  
	unset($contacts[0]);
    for ($i = 1; $i <= 2; $i++) {
        ${'new_' . $contacts[$i] . 'PK'} = $ContactPerson->createContactPerson(
            array(
                $userPK,
                0, // new contact person
                ${$contacts[$i] . '_name'},
                '',
                '',
                ${$contacts[$i] . '_landline'},
                ${$contacts[$i] . '_fax'},
                -1, //Preferred contact type
                ${'new_' . $contacts[$i] . '_addressPK'}       
            )
        );
        
    }
	
    $new_customerPK = $Customer->createCustomer(
        array(
            $userPK,
            0, // New customerPK
            !empty($customer_title) && $customer_title > 0 ? $customer_title : -1,
            $customer_initials,
            $customer_surname,
            $customer_name,
            $contact_email,
            $contact_cellphone,
            $contact_landline,
            $contact_fax,
            !empty($contact_type) && $contact_type ? $contact_type : -1,
            $customer_idno,
            $customer_company_registration_no,
            $customer_company_name,
            !empty($customer_language) && $customer_language > 0 ? $customer_language : -1,
            $new_contact_addressPK, // Postal address
            $new_employerPK,
            $new_referencePK,
            $customer_vat_no,
            $new_billing_accountPK,
            $customer_comments, 
            $customer_arrangement_date
        )
    );
		//StoredProcedures::displayParams($new_customerPK );
	if ($isCustomerMoved) {
		
		$old_unit = $Unit->getUnit(array($userPK, $unitPK, 0), true);
		$Unit->updateUnit(
			array(
				$userPK, 
				$old_unit['UnitPk'], 
				$old_unit['NumberBk'],
				$old_unit['BuildingFk'],
				0, // is not anymore occupied
				$old_unit['SquareMeters']
			)
		);
		
		$buildingPK = $_POST['customer_new_buildingPK'];
		$unitPK = $_POST['customer_new_unitPK'];
		
		$new_unit = $Unit->getUnit(array($userPK, $unitPK, 0), true);
		$Unit->updateUnit(
			array(
					$userPK,
					$new_unit['UnitPk'],
					$new_unit['NumberBk'],
					$new_unit['BuildingFk'],
					1, // is now occupied
					$new_unit['SquareMeters']
			)
		);
	} 
	
	if (!empty($new_customerPK) && $new_customerPK > 0) {
		$billing_accountPK = $new_billing_accountPK;
		$submit_result = 'success';
		$errmsg = 'Record created successfully!';
	} else {
		$submit_result = 'error';
		$errmsg = 'Error occurred when creating a new record!';
	}	

	$error_class = 'show';
} else if (isset($_POST['Update'])) {
	$sage->ExportCustomer(array($companyPK));
	$billing_account_data = $Billing->getBillingAccount(array($userPK, $billing_accountPK, 0, 0, 1));
	$billing_account_data = $Billing->getSingleRecord($billing_account_data);
	
	$customer_data = $Customer->getCustomer(array($userPK, 0, $billing_account_data['BillingAccountPk']));
    $customer_data = $Customer->getSingleRecord($customer_data);
	
    $isPrepaid = !empty($billing_isPrepaid) ? 1 : 0;
    $tenant_number = 0;
    
    if ($billing_account_data['IsPrepaid'] != $isPrepaid) { // if there's a change in isPrepaid, then update the NumberBk
    	$current_billing_accounts = $Billing->getBillingAccount(array($userPK, 0, $billing_account_data['BuildingFk'], $billing_account_data['UnitFk'], 1));
    	$latest_account = null;
    	
    	if ($isPrepaid === 1) { 	
		    foreach ($current_billing_accounts as $account) {
				if (substr_count($account['NumberBk'], 'PP') > 0) {
					if (is_null($latest_account)) {
						$latest_account = $account;
					} else {
						if ((int) substr($account['NumberBk'], 2) > (int) substr($latest_account['NumberBk'], 2)) {
							$latest_account = $account;
						}
					}
				}
			}

			$tenant_number = !empty($latest_account['NumberBk']) ? 'PP' . ((int) substr($latest_account['NumberBk'], 2) + 1) : 'PP';
	    } else {
		    foreach ($current_billing_accounts as $account) {
				if (substr_count($account['NumberBk'], 'PP') === 0) {
					if (is_null($latest_account)) {
						$latest_account = $account;
					} else {
						if ((int) $account['NumberBk'] > (int) $latest_account['NumberBk']) {
							$latest_account = $account;
						}
					}
				}
			}
	
			$tenant_number = !empty($latest_account['NumberBk']) ? ((int) $latest_account['NumberBk']) + 1 : '01';
			$tenant_number = strlen($tenant_number) == 1 ? '0' . $tenant_number : $tenant_number;
	    }
    } else {
    	$tenant_number = $billing_account_data['NumberBk'];
    }
    
	if (!empty($billing_account_data)) {
		// New Billing
		$Billing->updateBillingAccount(
			array(
				$userPK,
				$billing_account_data['BillingAccountPk'],
				$billing_account_data['BuildingFk'],
				$billing_account_data['UnitFk'],
				$tenant_number,
				'', // ERPCode
				!empty($billing_deposit_required) ? $billing_deposit_required : 0,
				!empty($billing_isDeposit_refundable) ? 1 : 0,
				!empty($billing_isAgreement_received) ? 1 : 0,
				!empty($billing_isOwner) ? 1 : 0,
				$billing_owner_email,
				$isPrepaid,
				$billing_occupancy_date,
				!empty($billing_vacancy_date) ? $billing_vacancy_date : '2100-01-01'
			)     
		);
		
		$employer_data = $ContactPerson->getContactPerson(array($userPK, $customer_data['EmployerContactPersonFk']));
		$employer_data = $ContactPerson->getSingleRecord($employer_data);
		
		$reference_data = $ContactPerson->getContactPerson(array($userPK, $customer_data['ReferenceContactPersonFk']));
		$reference_data = $ContactPerson->getSingleRecord($reference_data);
		
		$contacts = array('employer', 'reference');
		$contactPKs = array($customer_data['EmployerContactPersonFk'], $customer_data['ReferenceContactPersonFk']);
		
		$addressPKs = array($employer_data['AddressFk'], $reference_data['AddressFk']);
		for ($i = 0; $i < 2; $i++) {
			$ContactPerson->updateContactPerson(
				array(
					$userPK,
					$contactPKs[$i], 
					${$contacts[$i] . '_name'},
					'',
					'',
					${$contacts[$i] . '_landline'},
					${$contacts[$i] . '_fax'},
					-1,
					$addressPKs[$i]      
				)
			);
			
		}
		
		$contacts[] = 'contact';
		$addressPKs[] = $customer_data['PostalAddressFk'];
		
		$contacts_len = count($contacts);
		for ($i = 0; $i < $contacts_len; $i++) {
			$Address->updateAddress(
				array(
					$userPK,
					$addressPKs[$i], 
					${$contacts[$i] . '_address1'},
					${$contacts[$i] . '_address2'},
					${$contacts[$i] . '_address3'},
					${$contacts[$i] . '_address4'},
					${$contacts[$i] . '_postal_code'}   
				)
			);
			
		} 

		$Customer->updateCustomer(
			array(
				$userPK,
				$customer_data['CustomerPk'],
				!empty($customer_title) && $customer_title > 0 ? $customer_title : -1,
				$customer_initials,
				$customer_surname,
				$customer_name,
				$contact_email,
				$contact_cellphone,
				$contact_landline,
				$contact_fax,
				!empty($contact_type) && $contact_type ? $contact_type : -1,
				$customer_idno,
				$customer_company_registration_no,
				$customer_company_name,
				!empty($customer_language) && $customer_language > 0 ? $customer_language : -1,
				$customer_data['PostalAddressFk'],
				$customer_data['EmployerContactPersonFk'],
				$customer_data['ReferenceContactPersonFk'],
				$customer_vat_no,
				$customer_data['BillingAccountFk'],
				$customer_comments, 
				$customer_arrangement_date 
			)
		);
	}
	
	
	/* if ($isUpdate) {
		$submit_result = 'success';
		$errmsg = 'Record updated successfully!';
	} else {
		$submit_result = 'error';
		$errmsg = 'Error occurred when updating the record!';
	} */
	
	$submit_result = 'success';
	$errmsg = 'Record updated successfully!';
	$error_class = 'show';
}


if (isset($_GET['View']) ) {
    $save_name = 'Update';

    $customer_data = $Customer->getCustomer(array($userPK, 0, $billing_accountPK));
    $customer_data = $Customer->getSingleRecord($customer_data);

    $contact_address_data = $Address->getAddress(array($userPK, $customer_data['PostalAddressFk']));
    $contact_address_data = $Address->getSingleRecord($contact_address_data);
    
    $billing_account_data = $Billing->getBillingAccount(array(0, $billing_accountPK, 0, 0));
    $billing_account_data = $Billing->getSingleRecord($billing_account_data);
    
    $employer_data = $ContactPerson->getContactPerson(array($userPK, $customer_data['EmployerContactPersonFk']));
    $employer_data = $ContactPerson->getSingleRecord($employer_data);
    $employer_address_data = $Address->getAddress(array($userPK, $employer_data['AddressFk']));
    $employer_address_data = $Address->getSingleRecord($employer_address_data);
    
    $reference_data = $ContactPerson->getContactPerson(array($userPK, $customer_data['ReferenceContactPersonFk']));
    $reference_data = $ContactPerson->getSingleRecord($reference_data);
    $reference_address_data = $Address->getAddress(array($userPK, $reference_data['AddressFk']));
    $reference_address_data = $Address->getSingleRecord($reference_address_data);
    
    // Billing Account
    $billing_occupancy_date = $billing_account_data['OccupancyDate'];
    $billing_vacancy_date = $billing_account_data['VacancyDate'];
    $billing_deposit_required = $billing_account_data['DepositRequired'];
    $billing_isDeposit_refundable = $billing_account_data['IsDepositRefundable'] ? 'checked':'';
    $billing_isAgreement_received = $billing_account_data['IsSignedAgreementReceived'] ? 'checked':'';
    $billing_isPrepaid = $billing_account_data['IsPrepaid'] ? 'checked':'';
    $billing_isOwner = $billing_account_data['IsOwner'] ? 'checked':'';
    $billing_owner_email = $billing_account_data['OwnerEmail'];
            
    // Customer
    $customer_title_typePk = $customer_data['TitleTypeFk'];
    $customer_initials = $customer_data['Initials'];
    $customer_surname = $customer_data['Surname'];
    $customer_name = $customer_data['Name'];
    $customer_idno = $customer_data['IDNumber'];
    $customer_language_typePK = $customer_data['LanguageTypeFk'];
    $customer_company_name = $customer_data['CompanyName'];
    $customer_company_registration_no = $customer_data['CompanyRegistrationNumber'];
    $customer_vat_no = $customer_data['VATNumber'];
    $customer_comments = $customer_data['Comments'];
    $customer_arrangement_date = $customer_data['ArrangementDate'];

    // Contact Details
    $contact_typePK = $customer_data['PreferredContactTypeFk'];
    $contact_name = $customer_data['Name'];
    $contact_email = $customer_data['Email'];
    $contact_cellphone = $customer_data['Cellphone'];
    $contact_landline = $customer_data['AlternatePhone'];
    $contact_fax = $customer_data['Fax'];
    $contact_address1 = $contact_address_data['Address1'];
    $contact_address2 = $contact_address_data['Address2'];
    $contact_address3 = $contact_address_data['Address3'];
    $contact_address4 = $contact_address_data['Address4'];
    $contact_postal_code = $contact_address_data['PostalCode'];
    
    // Employer
    $employer_name = $employer_data['Name'];
    $employer_landline = $employer_data['AlternatePhone'];
    $employer_fax = $employer_data['Fax'];
    $employer_address1 = $employer_address_data['Address1'];
    $employer_address2 = $employer_address_data['Address2'];
    $employer_address3 = $employer_address_data['Address3'];
    $employer_address4 = $employer_address_data['Address4'];
    $employer_postal_code = $employer_address_data['PostalCode'];
    
    // Reference
    $reference_name = $reference_data['Name'];
    $reference_landline = $reference_data['AlternatePhone'];
    $reference_fax = $reference_data['Fax'];
    $reference_address1 = $reference_address_data['Address1'];
    $reference_address2 = $reference_address_data['Address2'];
    $reference_address3 = $reference_address_data['Address3'];
    $reference_address4 = $reference_address_data['Address4'];
    $reference_postal_code = $reference_address_data['PostalCode'];
    
    $billing_vacancy_date = $billing_vacancy_date != '2100-01-01' ? $billing_vacancy_date : '';
    // $customer_arrangement_date = $customer_arrangement_date != '1900-01-01' ? $customer_arrangement_date : '';

    
} else if (isset($_GET['Create'])) {
    $save_name = 'Create';
    $billing_accountPK = 0;
	
}  

$building_list = $Building->getBuilding(array($userPK, 0));
if (!empty($buildingPK)) { 
	$unit_list = $Unit->getUnit(array($userPK, 0, $buildingPK));
}
if (!empty($unitPK)) {
	$billing_account_list = $Billing->getBillingAccount(array($userPK, 0, $buildingPK, $unitPK, 1));
}

$billing_accountPK = $new_billing_accountPK > 0 ? $new_billing_accountPK : $billing_accountPK;
?>
<form method="get" class="hover-cursor-pointer" >
<div class="sub-menu-title"><h1>Customer Master</h1></div>
<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<div id="customer-critera" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Customer Criteria</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Building:</li>
            <li>
                <select id="customer-selection-building" class="selection-required-input" name="choose_building">
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
                <select id="customer-selection-unit" class="selection-required-input" name="choose_unit">
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
            <li>Tenant Number:</li>
            <li>
                <select id="customer-selection-tenant" class="selection-required-input" name="choose_tenant">
                    <option value="0">Please choose a tenant no.</option>
                    <?php 
                    if (!empty($billing_account_list)) { 
                    foreach($billing_account_list as $billing_account) { 
                        $selected = $billing_accountPK == $billing_account['BillingAccountPk'] ? 'selected="' . $billing_account['BillingAccountPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $billing_account['BillingAccountPk']; ?>"><?php echo $billing_account['NumberBk']; ?></option>
                    <?php } } ?>
                </select>
            </li>
            <!-- <li>Billing Account:</li>
            <li><select><option value="0">Please choose a customer no.</option></select></li> -->
        </ul>
        <div class="selection-form-submit float-left">
            <input id="customer-selection-view" type="submit" value="View" name="View"/>
			<?php if($restriction_level > 0){ ?>
				<input id="customer-selection-create" type="submit" value="Create" name="Create"/> 
			<?php }?>
			<button id="customer-selection-move" class="hidden">Move</button>			
        </div>
		<div id="customer-selection-success-box" class="selection-error-box error-box float-left hidden" style="border-color:#4D9615;">Ready to move customer</div>
        <div id="customer-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of building selection -->
</form> <!-- end of get form -->

<?php if ($view_class === 'show') { ?>
<form method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
<div id="customer-agreement-details" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Agreement Detail</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Occupancy date:</li>
            <li><input type="date" id="billing_occupancy_date" value="<?php echo $billing_occupancy_date; ?>" name="billing_occupancy_date"/></li>
            <li>Vacancy date:</li>
            <li><input type="date" id="billing_vacancy_date" value="<?php echo $billing_vacancy_date; ?>" name="billing_vacancy_date"></li> 
            <li>Deposit required:</li>
            <li><input type="text" id="billing_deposit_required" class="fieldset-forms-1half-length-input input-decimal" value="<?php echo $billing_deposit_required; ?>" name="billing_deposit_required"/></li>
            <li>Deposit refundable to third party:</li>
            <li><input type="checkbox" <?php echo $billing_isDeposit_refundable; ?> name="billing_isDeposit_refundable"/></li>
            <li>Agreement received:</li>
            <li><input type="checkbox" <?php echo $billing_isAgreement_received; ?> name="billing_isAgreement_received"/></li>
            <li>Arrangement Date:</li>
            <li><input type="date" id="customer_arrangement_date" value="<?php echo $customer_arrangement_date; ?>" name="customer_arrangement_date"></li> 
            <li>Prepaid</li>
            <li><input type="checkbox" <?php echo $billing_isPrepaid; ?> name="billing_isPrepaid"/></li>
        </ul>
    </fieldset>
    <fieldset id="customer-agreement-comments" class="fieldset-forms">
        <legend>Comments</legend>
        <textarea maxlength="1000" name="customer_comments"><?php echo $customer_comments; ?></textarea>
    </fieldset>

    <div class="clear"></div>
</div> <!-- end of customer agreement details -->

<div id="customer-responsible-person" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Responsible Person</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Title:</li>
            <li>
                <select class="selection-required-input" name="customer_title">
                    <option value="0">Please choose a title</option>
                    <?php 
                    if (!empty($title_list)) { 
                    foreach($title_list as $title) { 
                        $selected = $customer_title_typePk == $title['TitleTypePk'] ? 'selected="' . $title['TitleTypePk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $title['TitleTypePk']; ?>"><?php echo $title['Value']; ?></option>
                    <?php } } ?>
                </select>
            </li>
            <li>Initials:</li>
            <li><input type="text" maxlength="10" value="<?php echo $customer_initials; ?>" name="customer_initials"/></li>
            <li>Surname:</li>
            <li><input id="customer_surname" type="text" maxlength="100" value="<?php echo $customer_surname; ?>" name="customer_surname"/></li> 
            <li>Name:</li>
            <li><input id="customer_name" type="text" maxlength="100" value="<?php echo $customer_name; ?>" name="customer_name"/></li> 
            <li>Identity number:</li>
            <li><input type="text" maxlength="20" class="fieldset-forms-1half-length-input" value ="<?php echo $customer_idno; ?>" name="customer_idno"/></li>
            <li>Language:</li>
            <li>
                <select class="selection-required-input" name="customer_language">
                    <option value="0">Please choose a language</option>
                    <?php 
                    if (!empty($language_list)) { 
                    foreach($language_list as $language) { 
                        $selected = $customer_language_typePK == $language['LanguageTypePk'] ? 'selected="' . $language['LanguageTypePk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $language['LanguageTypePk']; ?>"><?php echo $language['Value']; ?></option>
                    <?php } } ?>
                </select>
            </li>
        </ul>
    </fieldset>
    <fieldset class="fieldset-forms">
        <legend>Sundry Information</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Company name:</li>
            <li><input type="text" maxlength="100" value="<?php echo $customer_company_name; ?>" name="customer_company_name"/></li>
            <li>Registration number:</li>
            <li><input type="text" maxlength="50" value="<?php echo $customer_company_registration_no; ?>" name="customer_company_registration_no"/></li>
            <li>VAT registration:</li>
            <li><input type="text" maxlength="50" value="<?php echo $customer_vat_no; ?>" name="customer_vat_no"/></li> 
            <li>Owner:</li>
            <li><input type="checkbox" <?php echo $billing_isOwner; ?> name="billing_isOwner"/></li> 
            <li>Owner e-mail:</li>
            <li><input type="text" maxlength="100" value="<?php echo $billing_owner_email; ?>" name="billing_owner_email"/></li>  
        </ul>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of customer responsible person -->

<div id="customer-contact-details" class="wrapper-fieldset-forms">
    <fieldset id="provider-fieldset-contact-details" class="fieldset-forms">
        <legend>Contact Details</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Email:</li>
            <li><input type="text" maxlength="100" name="contact_email" value="<?php echo $contact_email; ?>"/></li> 
            <li>Cellphone:</li>
            <li><input type="text" maxlength="100" name="contact_cellphone" value="<?php echo $contact_cellphone; ?>"/></li>
            <li>Landline:</li>
            <li><input type="text" maxlength="100" name="contact_landline" value="<?php echo $contact_landline; ?>"/></li>
            <li>Fax:</li>
            <li><input type="text" maxlength="100" name="contact_fax" value="<?php echo $contact_fax; ?>"/></li>
            <li>Contact type:</li>
            <li>  
                <select name="contact_type">
                    <option value="0">Please choose a contact type</option>
                <?php foreach ($preferred_contact_types as $type) {   
                    $selected = $contact_typePK === $type['PreferredContactTypePk'] ? 'selected="' . $type['PreferredContactTypePk'] . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $type['PreferredContactTypePk']; ?>"><?php echo $type['Value']; ?></option>
                <?php } ?>
                </select>
            </li>
        </ul>
    </fieldset>
    <fieldset class="fieldset-forms">
        <legend>Postal Address</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Line 1:</li>
            <li><input type="text" maxlength="100" value="<?php echo $contact_address1; ?>" name="contact_address1"/></li>
            <li>Line 2:</li>
            <li><input type="text" maxlength="100" value="<?php echo $contact_address2; ?>" name="contact_address2"/></li>
            <li>Line 3:</li>
            <li><input type="text" maxlength="100" value="<?php echo $contact_address3; ?>" name="contact_address3"/></li> 
            <li>Line 4:</li>
            <li><input type="text" maxlength="100" value="<?php echo $contact_address4; ?>" name="contact_address4"/></li> 
            <li>Postal code:</li>
            <li><input type="text" maxlength="10" class="fieldset-forms-1half-length-input" value="<?php echo $contact_postal_code; ?>" name="contact_postal_code"/></li>  
        </ul>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of customer contact details -->

<div id="employer-details" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Employer Details</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Name:</li>
            <li><input type="text" maxlength="100" value="<?php echo $employer_name; ?>" name="employer_name"/></li>
            <li>Land Line:</li>
            <li><input type="text" maxlength="100" value="<?php echo $employer_landline; ?>" name="employer_landline" class="fieldset-forms-1half-length-input"/></li>
            <li>Fax:</li>
            <li><input type="text" maxlength="100" value="<?php echo $employer_fax; ?>" name="employer_fax" class="fieldset-forms-1half-length-input"/></li> 
            <li>Address 1:</li>
            <li><input type="text" maxlength="100" value="<?php echo $employer_address1; ?>" name="employer_address1"/></li> 
            <li>Address 2:</li>
            <li><input type="text" maxlength="100" value="<?php echo $employer_address2; ?>" name="employer_address2"/></li>
            <li>Address 3:</li>
            <li><input type="text" maxlength="100" value="<?php echo $employer_address3; ?>" name="employer_address3"/></li> 
            <li>Address 4:</li>
            <li><input type="text" maxlength="100"value="<?php echo $employer_address4; ?>" name="employer_address4"/></li>
            <li>Postal code:</li>
            <li><input type="text" maxlength="10"value="<?php echo $employer_postal_code; ?>" name="employer_postal_code" class="fieldset-forms-1half-length-input"/></li>
        </ul>
    </fieldset>
    <fieldset class="fieldset-forms">
        <legend>Reference Details</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Name:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_name; ?>" name="reference_name"/></li>
            <li>Land Line:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_landline; ?>" name="reference_landline" class="fieldset-forms-1half-length-input"/></li>
            <li>Fax:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_fax; ?>" name="reference_fax" class="fieldset-forms-1half-length-input"/></li> 
            <li>Address 1:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_address1; ?>" name="reference_address1"/></li> 
            <li>Address 2:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_address2; ?>" name="reference_address2"/></li>
            <li>Address 3:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_address3; ?>" name="reference_address3"/></li> 
            <li>Address 4:</li>
            <li><input type="text" maxlength="100" value="<?php echo $reference_address4; ?>" name="reference_address4"/></li>
            <li>Postal code:</li>
            <li><input type="text" maxlength="10"value="<?php echo $reference_postal_code; ?>" name="reference_postal_code" class="fieldset-forms-1half-length-input"/></li>  
        </ul>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of employer details -->
<?php if($restriction_level > 0){ ?>
	<div class="wrapper-fieldset-forms">
		<input type="hidden" name="customer_new_buildingPK" />
		<input type="hidden" name="customer_new_unitPK" />
		<div id="customer-submit-error-box" class="submit-error-box warning-box warning hidden"></div>
		<div class="form-submit">
			<input id="customer-save-button" class="submit-positive" type="submit" value="<?php echo $save_name; ?>" name="<?php echo $save_name; ?>" />
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