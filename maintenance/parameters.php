<?php
$page_name = 'PARAMETERS';

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

// Utility
// $Utility = new UtilityType($dbh);

// Title
// $Title = new TitleType($dbh);

// Languagae
// $Language = new LanguageType($dbh);

// Preferred Contact
// $Building = new BuildingType($dbh);

// Preferred Contact
// $PreferredContact = new PreferredContactType($dbh);

// Reason Code
// $Reason = new ReasonType($dbh);

// Team
// $Team = new Team($dbh);

// Parameters
$parameters = array('title', 'building', 'team', 'language', 'contact', 'estimate_reason', 'test_meter_result');
$functions = array('TitleType', 'BuildingType', 'Team', 'LanguageType', 'PreferredContactType', 'ReasonType', 'ReasonType');
$param_len = count($parameters);

// UI
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_POST['Save'])) { 		
    // Get all posted values using dynamic variable creation
    for ($i = 0; $i < $param_len; $i++) {
         ${$parameters[$i] . '_PK'} = !empty($_POST[$parameters[$i] . '_PK']) ? $_POST[$parameters[$i] . '_PK'] : null;
         ${$parameters[$i] . '_value'} = !empty($_POST[$parameters[$i] . '_value']) ? $_POST[$parameters[$i] . '_value'] : null;
         ${$parameters[$i] . '_isActive'} = !empty($_POST[$parameters[$i] . '_isActive_values']) ? $_POST[$parameters[$i] . '_isActive_values'] : null;
         
         if ($parameters[$i] === 'utility') {
         	${$parameters[$i] . '_erpcode_value'} = !empty($_POST[$parameters[$i] . '_erpcode_value']) ? $_POST[$parameters[$i] . '_erpcode_value'] : null;
         	${$parameters[$i] . '_isMetered'} = !empty($_POST[$parameters[$i] . '_isMetered_values']) ? $_POST[$parameters[$i] . '_isMetered_values'] : null;
         }
     }

    for ($i = 0; $i < $param_len; $i++) {
        $param = $parameters[$i];
    	$param_PK = ${$parameters[$i] . '_PK'};
        $param_value = ${$parameters[$i] . '_value'};
        $param_isActive = ${$parameters[$i] . '_isActive'};
        
        $len = count($param_PK);
        for ($j = 0; $j < $len; $j++) {
        	$value = trim($param_value[$j]);
                
            if (!empty($value)) {    
            	if ($param_PK[$j] > 0) { // Update	
                	$update_function = 'update' . $functions[$i];	
                
	                if ($param === 'estimate_reason' || $param === 'test_meter_result') {
	                	$system_typeFK = $param === 'estimate_reason' ? PrimaDB::SYSTEM_TYPE_ESTIMATE_REASON_PK : PrimaDB::SYSTEM_TYPE_TEST_METER_RESULT_PK;               
	                    $dbhandler->$update_function(array($userPK, $param_PK[$j], $system_typeFK, $value, $param_isActive[$j]));
	                } else {
	                	$dbhandler->$update_function(array($userPK, $param_PK[$j], $value, $param_isActive[$j]));        
	                }
             	} else { // Create         
	             	$create_function = 'create' . $functions[$i];
	            
	                if ($param === 'estimate_reason' || $param === 'test_meter_result') {
	                	$system_typeFK = $param === 'estimate_reason' ? PrimaDB::SYSTEM_TYPE_ESTIMATE_REASON_PK : PrimaDB::SYSTEM_TYPE_TEST_METER_RESULT_PK;
	                    $dbhandler->$create_function(array($userPK, 0, $system_typeFK, $value, $param_isActive[$j]));
	                } else {
	                    $dbhandler->$create_function(array($userPK, 0, $value, $param_isActive[$j])); 
	             	}
	         	}	
      		}
    	}      
    } 
    
    $submit_result = 'success';
    $errmsg = 'Parameter record(s) saved sucessfully';
    $error_class = 'show';
} else if (isset($_POST['Cancel'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// get all parameter list
for ($i = 0; $i < $param_len; $i++) {
    $function = 'get' . $functions[$i];
    ${$parameters[$i] . '_list'} = $dbhandler->$function(array($userPK, 0));
}
?>

<div class="sub-menu-title"><h1>Parameters</h1></div>
<div id="parameter-submit-result-error-box" class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<form method="post" class="hover-cursor-pointer">
<div id="parameters">
    <ul id="parameters-tab-menu" class="tab-menu">                                                                               
        <li id="parameters-title" class="tab-menu-selected"><a href="#tab-title">Title</a></li>
        <li id="parameters-language" class="tab-menu-item"><a href="#tab-language">Language</a></li>
		<li id="parameters-preferred-contact" class="tab-menu-item"><a href="#tab-preferred-contact">Preferred Contact</a></li>
        <li id="parameters-building" class="tab-menu-item"><a href="#tab-building">Building Type</a></li>		
        <li id="parameters-team" class="tab-menu-item"><a href="#tab-team">Teams</a></li>        
        <li id="parameters-reason-code" class="tab-menu-item"><a href="#tab-reason-code">Movendus Estimate Reason</a></li>
		<li id="parameters-meter-test-result" class="tab-menu-item"><a href="#tab-meter-test-result">Movendus Test Meter Result</a></li>
    </ul>
    <div class="tab-extra-line" style="width: 7px;"></div>   

    <div id="parameters-title-content" class="show tab-content">
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-title-add-content">
        <?php 
            if (!empty($title_list)) {
            foreach ($title_list as $title) { 
            $isActive = $title['IsActive'] == 1 ? 'checked':'';
        ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="title_value[]" maxlength="50" value="<?php echo $title['Value']; ?>" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="title_isActive_temp[]" <?php echo $isActive; ?> />
                    <input type="hidden" name="title_isActive_values[]" value="<?php echo $title['IsActive']; ?>"/>
                </li>
                <li><input type="hidden" name="title_PK[]" value="<?php echo $title['TitleTypePk']; ?>" class="parameter-pk-value"/></li>	
            </ul>
        <?php  } } else { ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="title_value[]" maxlength="50" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="title_isActive_temp[]" checked/>
                    <input type="hidden" name="title_isActive_values[]" value="1"/>
                </li>
                <li><input type="hidden" name="title_PK[]" class="parameter-pk-value"/></li>
            </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-title-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-title-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>
    </div> <!-- end of title tab -->
   
    <div id="parameters-language-content" class="hidden tab-content">  
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-language-add-content">
        <?php 
            if (!empty($language_list)) {
            foreach ($language_list as $language) { 
            $isActive = $language['IsActive'] == 1 ? 'checked':'';
        ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="language_value[]" maxlength="50" value="<?php echo $language['Value']; ?>" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="language_isActive_temp[]" <?php echo $isActive; ?> />
                    <input type="hidden" name="language_isActive_values[]" value="<?php echo $language['IsActive']; ?>"/>
                </li>
                <li><input type="hidden" name="language_PK[]" value="<?php echo $language['LanguageTypePk']; ?>" class="parameter-pk-value"/></li>	
            </ul>
        <?php  } } else { ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="language_value[]" maxlength="50" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="language_isActive_temp[]" checked/>
                    <input type="hidden" name="language_isActive_values[]" value="1"/>
                </li>
                <li><input type="hidden" name="language_PK[]" class="parameter-pk-value"/></li>
            </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-language-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-language-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>
    </div> <!-- end of language tab -->
    
	<div id="parameters-preferred-contact-content" class="hidden tab-content">
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-contact-add-content">
        <?php 
            if (!empty($contact_list)) {
            foreach ($contact_list as $contact) { 
            $isActive = $contact['IsActive'] == 1 ? 'checked':'';
        ?>
        <ul class="parameters-content-li-2-cols">
            <li><input type="text" name="contact_value[]" maxlength="50" value="<?php echo $contact['Value']; ?>" class="utility-descr-value"/></li>
            <li class="center-li-contents">
                <input type="checkbox" name="contact_isActive_temp[]" <?php echo $isActive; ?> />
                <input type="hidden" name="contact_isActive_values[]" value="<?php echo $contact['IsActive']; ?>"/>
            </li>
            <li><input type="hidden" name="contact_PK[]" value="<?php echo $contact['PreferredContactTypePk']; ?>" class="parameter-pk-value"/></li>	
        </ul>
        <?php  } } else { ?>
        <ul class="parameters-content-li-2-cols">
            <li><input type="text" name="contact_value[]" maxlength="50" class="utility-descr-value"/></li>
            <li class="center-li-contents">
                <input type="checkbox" name="contact_isActive_temp[]" checked/>
                <input type="hidden" name="contact_isActive_values[]" value="1"/>
            </li>
            <li><input type="hidden" name="contact_PK[]" class="parameter-pk-value"/></li>
        </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-contact-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-contact-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>
    </div> <!-- end of preferred contact tab -->
	
    <div id="parameters-building-content" class="hidden tab-content">  
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-building-add-content">
        <?php 
            if (!empty($building_list)) {
            foreach ($building_list as $building) { 
            $isActive = $building['IsActive'] == 1 ? 'checked':'';
        ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="building_value[]" maxlength="50" value="<?php echo $building['Value']; ?>" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="building_isActive_temp[]" <?php echo $isActive; ?> />
                    <input type="hidden" name="building_isActive_values[]" value="<?php echo $building['IsActive']; ?>"/>
                </li>
                <li><input type="hidden" name="building_PK[]" value="<?php echo $building['BuildingTypePk']; ?>" class="parameter-pk-value"/></li>	
            </ul>
        <?php  } } else { ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="building_value[]" maxlength="50" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="building_isActive_temp[]" checked/>
                    <input type="hidden" name="building_isActive_values[]" value="1"/>
                </li>
                <li><input type="hidden" name="building_PK[]" class="parameter-pk-value"/></li>
            </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-building-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-building-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>  
    </div> <!-- end of building tab -->
    
    <div id="parameters-team-content" class="hidden tab-content">  
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-team-add-content">
        <?php 
            if (!empty($team_list)) {
            foreach ($team_list as $team) { 
            $isActive = $team['IsActive'] == 1 ? 'checked':'';
        ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="team_value[]" maxlength="50" value="<?php echo $team['Value']; ?>" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="team_isActive_temp[]" <?php echo $isActive; ?> />
                    <input type="hidden" name="team_isActive_values[]" value="<?php echo $team['IsActive']; ?>"/>
                </li>
                <li><input type="hidden" name="team_PK[]" value="<?php echo $team['TeamPk']; ?>" class="parameter-pk-value"/></li>	
            </ul>
        <?php  } } else { ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="team_value[]" maxlength="50" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="team_isActive_temp[]" checked/>
                    <input type="hidden" name="team_isActive_values[]" value="1"/>
                </li>
                <li><input type="hidden" name="team_PK[]" class="parameter-pk-value"/></li>
            </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-team-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-team-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>  
    </div> <!-- end of team tab -->
    
    <div id="parameters-reason-code-content" class="hidden tab-content parameters-menu-hover">    
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-reason-add-content">
        <?php 
        	$hasNoEstimateReason = true;
        	
	        if (!empty($estimate_reason_list)) {
	        	foreach ($estimate_reason_list as $reason) {
	        		if ($reason['SystemTypeFK'] == PrimaDB::SYSTEM_TYPE_ESTIMATE_REASON_PK) {
	        			$hasNoEstimateReason = false;
	        			break;
	        		}
	        	}
	        }
            if (!$hasNoEstimateReason) {
            foreach ($estimate_reason_list as $reason) { 
			if ($reason['SystemTypeFK'] == PrimaDB::SYSTEM_TYPE_ESTIMATE_REASON_PK) {
            $isActive = $reason['IsActive'] == 1 ? 'checked':'';
        ?>
        <ul class="parameters-content-li-2-cols">
            <li><input type="text" name="estimate_reason_value[]" maxlength="50" value="<?php echo $reason['Value']; ?>" class="utility-descr-value"/></li>
            <li class="center-li-contents">
                <input type="checkbox" name="estimate_reason_isActive_temp[]" <?php echo $isActive; ?> />
                <input type="hidden" name="estimate_reason_isActive_values[]" value="<?php echo $reason['IsActive']; ?>"/>
            </li>
            <li><input type="hidden" name="estimate_reason_PK[]" value="<?php echo $reason['ReasonTypePk']; ?>" class="parameter-pk-value"/></li>	
        </ul>
        <?php  } } } else { ?>
        <ul class="parameters-content-li-2-cols">
            <li><input type="text" name="estimate_reason_value[]" maxlength="50" class="utility-descr-value"/></li>
            <li class="center-li-contents">
                <input type="checkbox" name="estimate_reason_isActive_temp[]" checked/>
                <input type="hidden" name="estimate_reason_isActive_values[]" value="1"/>
            </li>
            <li><input type="hidden" name="estimate_reason_PK[]" class="parameter-pk-value"/></li>
        </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-reason-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-reason-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>       
    </div> <!-- end of reason code tab -->
	
	<div id="parameters-meter-test-result-content" class="hidden tab-content parameters-menu-hover">    
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-testmeter-add-content">
        <?php 
			$hasNoTestMeterResult = true;
			
			if (!empty($test_meter_result_list)) {
				foreach ($test_meter_result_list as $reason) {
					if ($reason['SystemTypeFK'] == PrimaDB::SYSTEM_TYPE_TEST_METER_RESULT_PK) {
						$hasNoTestMeterResult = false;
						break;
					}
				}
			}  
        
            if (!$hasNoTestMeterResult) {
            foreach ($test_meter_result_list as $reason) { 
				if ($reason['SystemTypeFK'] == PrimaDB::SYSTEM_TYPE_TEST_METER_RESULT_PK) {
            $isActive = $reason['IsActive'] == 1 ? 'checked':'';
        ?>
        <ul class="parameters-content-li-2-cols">
            <li><input type="text" name="test_meter_result_value[]" maxlength="50" value="<?php echo $reason['Value']; ?>" class="utility-descr-value"/></li>
            <li class="center-li-contents">
                <input type="checkbox" name="test_meter_result_isActive_temp[]" <?php echo $isActive; ?> />
                <input type="hidden" name="test_meter_result_isActive_values[]" value="<?php echo $reason['IsActive']; ?>"/>
            </li>
            <li><input type="hidden" name="test_meter_result_PK[]" value="<?php echo $reason['ReasonTypePk']; ?>" class="parameter-pk-value"/></li>	
        </ul>
        <?php  } } } else { ?>
        <ul class="parameters-content-li-2-cols">
            <li><input type="text" name="test_meter_result_value[]" maxlength="50" class="utility-descr-value"/></li>
            <li class="center-li-contents">
                <input type="checkbox" name="test_meter_result_isActive_temp[]" checked/>
                <input type="hidden" name="test_meter_result_isActive_values[]" value="1"/>
            </li>
            <li><input type="hidden" name="test_meter_result_PK[]" class="parameter-pk-value"/></li>
        </ul>
        <?php } ?>
        </div>
		<div class="clear"></div>
		<div class="selection-form-submit float-left">
            <button id="parameters-testmeter-add-button" class="parameters-add-button addline-button">Add</button>
        </div> 
		<div id="parameters-testmeter-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>       
    </div> <!-- end of reason code tab -->
</div> <!-- parameters -->
<?php if($restriction_level > 0){?>
	<div class="wrapper-fieldset-forms">
		<div id="parameter-submit-error-box" class="submit-error-box warning-box warning hidden"></div>
		<div class="form-submit">
			<input type="submit" id="parameter-save-button" value="Save" class="submit-positive" name="Save"/>
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel"/>
		</div>
	</div> <!-- end of form submit buttons -->
<?php }?>
</form> <!-- end of post form -->

<?php
require DOCROOT . '/template/footer.php';
?>