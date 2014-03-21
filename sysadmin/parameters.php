<?php
$page_name = 'PARAMETERS';//to differentiate between maintenance-->parameters and system administration parameters

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

// Allocation
$Allocation = new AllocationType($dbh);

// Meter
$Meter = new MeterType($dbh);

// Parameters
$parameters = array('allocation', 'meter', 'utility');
$param_len = count($parameters);
if (isset($_POST['Save'])) {  
    // Get all posted values using dynamic variable creation
    for ($i = 0; $i < $param_len; $i++) {
         ${$parameters[$i] . '_PK'} = !empty($_POST[$parameters[$i] . '_PK']) ? $_POST[$parameters[$i] . '_PK'] : null;
         ${$parameters[$i] . '_value'} = !empty($_POST[$parameters[$i] . '_value']) ? $_POST[$parameters[$i] . '_value'] : null;
         ${$parameters[$i] . '_isActive'} = !empty($_POST[$parameters[$i] . '_isActive_values']) ? $_POST[$parameters[$i] . '_isActive_values'] : null;
		
		 if ($parameters[$i] === 'utility') {
		 	${$parameters[$i] . '_erpcode_value'} = !empty($_POST[$parameters[$i] . '_erpcode_value']) ? $_POST[$parameters[$i] . '_erpcode_value'] : null;
		 	${$parameters[$i] . '_isMetered_values'} = !empty($_POST[$parameters[$i] . '_isMetered_values']) ? $_POST[$parameters[$i] . '_isMetered_values'] : null;
		 }
		 
         if ($parameters[$i] === 'allocation') {  
             ${$parameters[$i] . '_isServiceMeter'} = !empty($_POST[$parameters[$i] . '_isServiceMeter_values']) ? $_POST[$parameters[$i] . '_isServiceMeter_values'] : null;
             ${$parameters[$i] . '_isCommonProperty'} =  !empty($_POST[$parameters[$i] . '_isCommonProperty_values']) ? $_POST[$parameters[$i] . '_isCommonProperty_values'] : null;
             ${$parameters[$i] . '_isBulkWater'} = !empty($_POST[$parameters[$i] . '_isBulkWater_values']) ? $_POST[$parameters[$i] . '_isBulkWater_values'] : null;
         }
     }

    // Allocation types
    $len = count($allocation_PK);
    for ($i = 0; $i < $len; $i++) {
        $value = trim($allocation_value[$i]);
        if (!empty($value)) {       
            if ($allocation_PK[$i] > 0) {
                // Update Allocation types
                $Allocation->updateAllocationType(
                    array(
                        $userPK, 
                        $allocation_PK[$i], 
                        $value, 
                        $allocation_isServiceMeter[$i],
                        $allocation_isCommonProperty[$i],
                        $allocation_isBulkWater[$i],
                        $allocation_isActive[$i],
                     )
                );
            }
        } 
    }
    
    // Meter types
    $len = count($meter_PK);
    for ($i = 0; $i < $len; $i++) {
        if (!empty($meter_value[$i])) { 
            $value = trim($meter_value[$i]);
            if ($meter_PK[$i] > 0) {
                // Update meter types
                $Meter->updateMeterType(array($userPK, $meter_PK[$i], $value, $meter_isActive[$i]));
            }
        } 
    }
    
    // Utility Types
    $len = count($utility_PK);
    for ($i = 0; $i < $len; $i++) {
    	if (!empty($utility_value[$i])) {
    		$value = trim($utility_value[$i]);
    		$erpcode = trim($utility_erpcode_value[$i]);
    		
    		if ($utility_PK[$i] > 0) {
    			// Update meter types
    			$dbhandler->updateUtilityType(array($userPK, $utility_PK[$i], $value, $erpcode, $utility_isMetered_values[$i], $utility_isActive[$i]));
    		}
    	}
    }
	
} else if (isset($_POST['Cancel'])) {
    echo 'Cancelled';
}

$allocation_list = $Allocation->getAllocationType(array($userPK, 0));
$meter_list = $Meter->getMeterType(array($userPK, 0));
$utility_list = $dbhandler->getUtilityType(array($userPK, 0))

?>

<div class="sub-menu-title"><h1>Parameters</h1></div>
<form method="post" id="sysad-parameters" class="hover-cursor-pointer">
<div id="parameters">
    <ul id="parameters-tab-menu" class="tab-menu">
        <li id="parameters-allocation" class="tab-menu-selected"><a href="#tab-allocation">Allocation</a></li>
        <li id="parameters-meter" class="tab-menu-item"><a href="#tab-meter">Meter</a></li>
		<li id="parameters-utility" class="tab-menu-item"><a href="#tab-utility">Utility</a></li>
    </ul>
    <div class="tab-extra-line" style="width: 647px;"></div>
     
    <div id="parameters-allocation-content" class="tab-content parameters-menu-hover">
        <ul class="parameters-content-li-5-cols">
             <li><label>Description</label></li>
             <li class="center-li-contents">Service meter</li>
             <li class="center-li-contents">Common property</li>
             <li class="center-li-contents">Bulk water</li> 
             <li class="center-li-contents">Active</li> 
        </ul>
        <div id="parameters-allocation-add-content">

        <?php 
        if (!empty($allocation_list)) {
            foreach ($allocation_list as $allocation) { 
              $isServiceMeter = $allocation['IsServiceMeter'] == 1 ? 'checked':'';
              $isCommonProperty = $allocation['IsCommonProperty'] == 1 ? 'checked':'';
              $isBulkWater = $allocation['IsBulkWater'] == 1 ? 'checked':'';
              $isActive = $allocation['IsActive'] == 1 ? 'checked':'';
        ?>
        <ul class="parameters-content-li-5-cols">
           <li>
               <input type="text" name="allocation_value[]" value="<?php echo $allocation['Value']; ?>"/>
               <input type="hidden" name="allocation_PK[]" value="<?php echo $allocation['AllocationTypePk']; ?>"/>
           </li>
           <li class="center-li-contents">
               <input name="allocation_isServiceMeter_temp[]" type="checkbox" <?php echo $isServiceMeter; ?>/>
               <input type="hidden" name="allocation_isServiceMeter_values[]" value="<?php echo $allocation['IsServiceMeter']; ?>"/>
           </li>
           <li class="center-li-contents">
               <input name="allocation_isCommonProperty_temp[]" type="checkbox" <?php echo $isCommonProperty; ?>/>
               <input type="hidden" name="allocation_isCommonProperty_values[]" value="<?php echo $allocation['IsCommonProperty']; ?>"/>
           </li>
           <li class="center-li-contents">
               <input name="allocation_isBulkWater_temp[]" type="checkbox" <?php echo $isBulkWater; ?>/>
               <input type="hidden" name="allocation_isBulkWater_values[]" value="<?php echo $allocation['IsBulkWater']; ?>"/>
           </li>
           <li class="center-li-contents">
               <input name="allocation_isActive_temp[]" type="checkbox" <?php echo $isActive; ?>/>
               <input type="hidden" name="allocation_isActive_values[]" value="<?php echo $allocation['IsActive']; ?>"/>
           </li>
           </ul>
        <?php } } ?>            
         </div>
    </div> <!-- end of allocation tab -->
    
    <div id="parameters-meter-content" class="hidden tab-content">
        <ul class="parameters-content-li-2-cols">
            <li><label>Description</label></li>
            <li class="center-li-contents">Active</li>
            <li></li>	
        </ul>
        <div id="parameters-meter-add-content">
        <?php 
            if (!empty($meter_list)) {
            foreach ($meter_list as $meter) { 
            $isActive = $meter['IsActive'] == 1 ? 'checked':'';
        ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="meter_value[]" value="<?php echo $meter['Value']; ?>"/></li>
                <li class="center-li-contents">
                    <input type="checkbox" name="meter_isActive_temp[]" <?php echo $isActive; ?> />
                    <input type="hidden" name="meter_isActive_values[]" value="<?php echo $meter['IsActive']; ?>"/>
                </li>
                <li><input type="hidden" name="meter_PK[]" value="<?php echo $meter['MeterTypePk']; ?>" /></li>	
            </ul>
        <?php  } } ?>
        </div>
    </div>  <!-- end of meter tab -->

	<div id="parameters-utility-content" class="hidden tab-content parameters-tab-content parameters-menu-hover">
        <ul class="parameters-content-li-2-cols">
            <li>Description</li>
            <li>ERP Code</li>
            <li class="center-li-contents">Metered</li>
            <li class="center-li-contents">Active</li>
        </ul>
        <div id="parameters-utility-add-content">
        <?php 
            if (!empty($utility_list)) {
            foreach ($utility_list as $utility) { 
            $isActive = $utility['IsActive'] == 1 ? 'checked':'';
            $isMetered = $utility['IsMetered'] == 1 ? 'checked':'';
        ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="utility_value[]" maxlength="50" value="<?php echo $utility['Value']; ?>" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                	<input type="text" name="utility_erpcode_value[]" maxlength="50" value="<?php echo $utility['ERPCode']; ?>" class="utility-erpcode-value"/>
                    <input type="hidden" name="utility_PK[]" value="<?php echo $utility['UtilityTypePk']; ?>" class="parameter-pk-value"/>
                </li>
                <li class="center-li-contents">
                	<input type="checkbox" name="utility_isMetered_temp[]" <?php echo $isMetered; ?> />
                    <input type="hidden" name="utility_isMetered_values[]" value="<?php echo $utility['IsMetered']; ?>"/>         
                </li>
                <li class="center-li-contents">
                	<input type="checkbox" name="utility_isActive_temp[]" <?php echo $isActive; ?> />
                    <input type="hidden" name="utility_isActive_values[]" value="<?php echo $utility['IsActive']; ?>"/>         
                </li>	
            </ul>
        <?php  } } else { ?>
            <ul class="parameters-content-li-2-cols">
                <li><input type="text" name="utility_value[]" maxlength="50" class="utility-descr-value"/></li>
                <li class="center-li-contents">
                	<input type="text" name="utility_erpcode_value[]" maxlength="50" class="utility-erpcode-value"/>
                    <input type="hidden" name="utility_PK[]" class="parameter-pk-value"/>
                </li>
                <li class="center-li-contents">
                	<input type="checkbox" name="utility_isActive_temp[]" checked />
                    <input type="hidden" name="utility_isActive_values[]" value="1"/>         
                </li>	
            </ul>
        <?php } ?>
        </div>
        <div class="clear"></div>		
		<div id="parameters-utility-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>
    </div> <!-- end of utility tab -->
</div> <!-- parameters -->

<div class="wrapper-fieldset-forms">
    <?php if($restriction_level > 0){ ?>
	<div class="form-submit">
        <input type="submit" value="Save" class="submit-positive" name="Save"/>
        <input type="submit" value="Cancel" class="submit-netagive" name="Cancel"/>
    </div>
	<?php } ?>
</div> <!-- end of form submit buttons -->
</form> <!-- end of post form -->

<?php
require DOCROOT . '/template/footer.php';
?>