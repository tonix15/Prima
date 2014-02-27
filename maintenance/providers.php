<?php	
$page_name = 'PROVIDERS';

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

// Address
$Address = new Address($dbh);
$addressPK = 0;

// Provider
$Provider = new Provider($dbh);
$provider_list = null;
$providerPK = 0;
$business_code = '';
$provider_name = '';
$provider_erpcode = '';
$provider_isInternal_provider = '';
$provider_isActive = 'checked';
$provider_comment = '';
$provider_data = null;

// Contact Person
$ContactPerson = new ContactPerson($dbh);
$contact_name = '';
$contact_email = '';
$contact_cellphone = '';
$contact_landline = '';
$contact_fax = '';
$contact_type = '';
$contact_person_data = null;

// Preffered Contact Type
$PreferedContactType = new PreferredContactType($dbh);
$preferred_contact_types = null;

// UI
$view_class = 'hidden';
$save_name = '';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_GET['View'], $_GET['choose_provider'])) {
    $save_name = 'Update';
    $view_class = 'show';
    $providerPK = $_GET['choose_provider'];
    $provider_data = $Provider->getProvider(array($userPK, $providerPK));
    $provider_data = $Provider->getSingleRecord($provider_data);
    $provider_name = $provider_data['Name'];
    $provider_erpcode = $provider_data['ERPCode'];
    $business_code = $provider_data['NumberBk'];
    $provider_comment = $provider_data['Comments'];
	$provider_isInternal_provider = $provider_data['IsInternalProvider'] == 1 ? 'checked':'';
    $provider_isActive = $provider_data['IsActive'] == 1 ? 'checked':'';

    $contact_person_data = $ContactPerson->getContactPerson(array($userPK, $provider_data['ProviderContactPersonFk']));
    $contact_person_data = $ContactPerson->getSingleRecord($contact_person_data);
    $contact_name = $contact_person_data['Name'];
    $contact_email = $contact_person_data['Email'];
    $contact_cellphone = $contact_person_data['Cellphone'];
    $contact_landline = $contact_person_data['AlternatePhone'];
    $contact_fax = $contact_person_data['Fax'];
    $contact_type = $contact_person_data['PreferredContactTypeFk'];
    
} else if (isset($_GET['Create'])) {
    $save_name = 'Create';
    $view_class = 'show';
}

if (isset($_POST['Create']) || isset($_POST['Update'])) {
              
    $business_code = $_POST['business_code'];
    $provider_name = $_POST['provider_name'];
    $provider_erpcode = $_POST['provider_erpcode'];
    $provider_isActive = isset($_POST['provider_isActive']) ? 1: 0;
	$provider_isInternal_provider = isset($_POST['provider_isInternal_provider']) ? 1: 0;
    $provider_comment = $_POST['provider_comment'];  

    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];
    $contact_cellphone = $_POST['contact_cellphone'];
    $contact_landline = $_POST['contact_landline'];
    $contact_fax = $_POST['contact_fax'];
    $contact_type = $_POST['$contact_type'] <= 0 ? -1 : (int) $_POST['$contact_type'];
}

if (isset($_POST['Create'])) {
    $view_class = 'hidden';
    
    // create a dummy address for the new contact person
    $addressPK = $Address->createAddress(
        array(
            $userPK, 
            0, // new addressPK
            'test', 
            'test', 
            'test', 
            'test', 
            'test'
        )
    );
    
    $contact_personPK = $ContactPerson->createContactPerson(
        array(
            $userPK, // userPK
            0, // new contact person
            $contact_name, 
            $contact_email, 
            $contact_cellphone, 
            $contact_landline, 
            $contact_fax, 
            $contact_type, 
            $addressPK // address PK
        )
    );
    
    if (!empty($contact_personPK) && $contact_personPK > 0) {
        $lastInsertedId = $Provider->createProvider(
            array(
                $userPK, // userPK
                0, // new provider 
                $business_code, 
                $provider_name,
            	$provider_erpcode, 
                $contact_personPK,
                $provider_isActive,
				$provider_isInternal_provider,				
                $provider_comment
            )
        );
    }
    
    if (!empty($lastInsertedId) && $lastInsertedId > 0) {
		$providerPK = $lastInsertedId;
        $submit_result = 'success';
		$errmsg = 'Record created successfully!';
    } else {
        $submit_result = 'error';
		$errmsg = 'Error occurred when creating a new record!';
    }
	$error_class = 'show';
} else if (isset($_POST['Update'])) {
   
    $view_class = 'hidden';
    if (!$ContactPerson->updateContactPerson(
        array(
            $userPK, // userPK
            $contact_person_data['ContactPersonPk'], // contact person PK
            $contact_name, 
            $contact_email, 
            $contact_cellphone, 
            $contact_landline, 
            $contact_fax, 
            $contact_type, 
            $contact_person_data['AddressFk'] // address PK
        )
    )) {
        $submit_result = 'error';
		$errmsg = 'Error occurred when updating the record!';
    } 

    if ($Provider->updateProvider(
        array(
            $userPK, // userPK
            $providerPK, // provider PK
            $business_code, 
            $provider_name, 
        	$provider_erpcode,
            $contact_person_data['ContactPersonPk'], 
            $provider_isActive, 
			$provider_isInternal_provider,
            $provider_comment
        )
    )) {
        // $submit_result = 'success';
		// $errmsg = 'Record updated successfully!';
    } else {
        // $submit_result = 'error';
		// $errmsg = 'Error occurred when updating the record!';
    }
    
    $submit_result = 'success';
    $errmsg = 'Record updated successfully!';
	$error_class = 'show';
} else if (isset($_POST['Cancel'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$provider_list  = $Provider->getProvider(array($userPK, 0));
$preferred_contact_types = $PreferedContactType->getPreferredContactType(array($userPK, 0));

?>
<form id="provider-selection-form" method="get" class="hover-cursor-pointer">
<div class="sub-menu-title"><h1>Provider Master</h1></div>
<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<div id="provider-selection" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Provider Selection</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li><label>Provider code:</label></li>
            <li>  
                <select id="provider-selection-provider" name="choose_provider">
                    <option value="0">Please choose a provider</option>
                <?php foreach ($provider_list as $provider) { 
                    $selected = $provider['ProviderPk'] === $providerPK ? 'selected="' . $providerPK . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $provider['ProviderPk']; ?>"><?php echo $provider['NumberBk']; ?></option>
                <?php } ?>
                </select>
            </li>
        </ul>
		<div class="selection-form-submit float-left">
            <input id="provider-selection-view" type="submit" value="View" name="View" />
			<?php if($restriction_level > 0){ ?>
					 <input type="submit" value="Create" name="Create"/>   
			<?php }?>
        </div> 
        <div id="provider-selection-error-box" class="selection-error-box error-box float-left hidden"></div>	
    </fieldset>
</div> <!-- end of provider selection -->
</form> <!-- end of get form -->

<?php if ($view_class === 'show') { ?>
<form id="provider-data-form" method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
<div id="provider-contact-details" class="wrapper-fieldset-forms">
    <fieldset id="provider-fieldset-contact-details" class="fieldset-forms">
        <legend>Provider Details</legend>
        <ul class="fieldset-forms-li-2-cols">       
            <li>Provider code:</li>
            <li><input type="text" id="provider_code" name="business_code" class="fieldset-forms-1half-length-input"  maxlength="20" value="<?php echo $business_code; ?>"/></li>
            <li>Name:</li>
            <li><input type="text" id="provider_name" name="provider_name" value="<?php echo $provider_name; ?>" maxlength="100"/></li>
            <li>ERP Code:</li>
            <li><input type="text" id="provider_erpcode" name="provider_erpcode" value="<?php echo $provider_erpcode; ?>" maxlength="100"/></li>
            <li>Internal Provider:</li>
            <li><input name="provider_isInternal_provider" type="checkbox" <?php echo $provider_isInternal_provider; ?> /></li> 
			<li>Active:</li>
            <li><input name="provider_isActive" type="checkbox" <?php echo $provider_isActive; ?> /></li> 
        </ul>
    </fieldset>
    <fieldset id="provider-fieldset-details-comments" class="fieldset-forms">
        <legend>Comments</legend>
        <textarea name="provider_comment" maxlength="1000"><?php echo $provider_comment; ?></textarea>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of provider details -->
<div id="provider-contact-details" class="wrapper-fieldset-forms">
    <fieldset id="provider-fieldset-contact-details" class="fieldset-forms">
        <legend>Contact Details</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li><label>Name:</label></li>
            <li><input type="text" maxlength="100" id="contact-name" name="contact_name" value="<?php echo $contact_name; ?>"/></li>
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
                <select name="$contact_type">
                    <option value="0">Please choose a contact type</option>
                <?php foreach ($preferred_contact_types as $type) {   
                    $selected = $contact_type === $type['PreferredContactTypePk'] ? 'selected="' . $type['PreferredContactTypePk'] . '"':'';
                ?>
                    <option <?php echo $selected; ?> value="<?php echo $type['PreferredContactTypePk']; ?>"><?php echo $type['Value']; ?></option>
                <?php } ?>
                </select>
            </li>
        </ul>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of contact details -->
<?php if($restriction_level > 0){?>
	<div class="wrapper-fieldset-forms">
		<div id="provider-submit-error-box" class="submit-error-box warning-box warning hidden"></div>
		<div class="form-submit">
			<input id="provider-save-button" class="submit-positive" type="submit" value="<?php echo $save_name; ?>" name="<?php echo $save_name; ?>" />
			<input class="submit-netagive" type="submit" value="Cancel" name="Cancel"/>
		</div>
	</div> <!-- end of form submit buttons -->
<?php }?>
</form> <!-- end of post form -->
<?php
}
require DOCROOT . '/template/footer.php';
?>