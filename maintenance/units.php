<?php
$page_name = 'UNITS';

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

if (isset($_POST['Cancel'])) {
    header('Location: ' . DOMAIN_NAME . '/maintenance/units.php');
    exit;
}

$view_class = 'hidden';

// Building
$Building = new Building($dbh);
$rate_account_list = null;
$buildingPK = 0;

// Unit
$Unit = new Unit($dbh);
$unitNo = 0;
$unit_list = null;

// UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';


if (isset($_GET['List'])) {
    $view_class = 'show';
    $buildingPK = $_GET['choose_building'];
    $unitNo = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit']: 0;
    $unit_list = $Unit->getUnit(array($userPK, 0, $buildingPK));
    $unit_list_temp = array();
    
    foreach ($unit_list as $unit) {
        if ($unit['NumberBk'] >= $unitNo) {
            $unit_list_temp[] = $unit;
        }
    }
    
    $unit_list = $unit_list_temp;  
}

if (isset($_POST['Save']) || isset($_POST['Cancel'])) {
    $view_class = 'hidden';
    
    $unit_PK = !empty($_POST['unit_PK']) ? $_POST['unit_PK'] : null;
    $unit_numbers = !empty($_POST['unit_numbers']) ? $_POST['unit_numbers'] : null;
    // $unit_isOccupied_values = !empty($_POST['unit_isOccupied_values']) ? $_POST['unit_isOccupied_values'] : null;
    $unit_square_meters = !empty($_POST['unit_square_meters']) ? $_POST['unit_square_meters'] : null;
    
    $unit_len = count($unit_PK);
    $result = true;
    for ($i = 0; $i < $unit_len; $i++) {
    	$method = !empty($unit_PK[$i]) && $unit_PK[$i] > 0 ? 'updateUnit' : 'createUnit';
        $res = $Unit->$method(
            array(
                $userPK,
                $unit_PK[$i],
                $unit_numbers[$i],
                $buildingPK,
                // !empty($unit_isOccupied_values[$i]) ? 1 : 0,
                !empty($unit_square_meters[$i]) ? $unit_square_meters[$i] : 0
            )
        );
        
        $result = $result && (!$res ? false : true); 
    }
    
    if ($result === true) {
    	// $submit_result = 'success';
    	// $errmsg = 'Unit record(s) saved sucessfully';
   
    } else {
    	// $submit_result = 'error';
    	// $errmsg = 'An error occured when saving unit record(s)';
    }
    
    $submit_result = 'success';
    $errmsg = 'Unit record(s) saved sucessfully';
    $error_class = 'show';
} 

$building_list = $Building->getBuilding(array($userPK, 0));
?>
<form method="get" class="hover-cursor-pointer">
<div class="sub-menu-title"><h1>Unit Master</h1></div>
<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<div id="unit-criteria" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Selection Criteria</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Building:</li>
            <li>
                <select id="unit-selection-building" class="selection-required-input" name="choose_building">
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
            <li><input type="text" name="choose_unit" value="<?php echo $unitNo; ?>"/></li>
        </ul>
        <div class="selection-form-submit float-left">  
            <input id="unit-selection-list" type="submit" value="List" name="List"/>   
        </div> 
        <div id="unit-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of building selection -->
</form>
<?php if ($view_class === 'show') { ?>
<form method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
<div id="unit-list" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Contact Details</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li class="center-li-contents">Unit no:</li>
            <!-- <li class="center-li-contents">Occupied:</li> -->
            <li class="center-li-contents">Square Meters:</li>
        </ul>
        <div id="unit-list-add-content">
        <?php if (!empty($unit_list)) { 
            foreach($unit_list as $unit) { 
                // $isOccupied = $unit['IsOccupied'] ? 'checked':''; ?>       
        <ul class="fieldset-forms-li-2-cols">
            <li class="center-li-contents">
                <input maxlength="20" name="unit_numbers[]" type="text" class="unit-unique-value fieldset-forms-1fourth-length-input" value="<?php echo $unit['NumberBk']; ?>"/>
                <input type="hidden" name="unit_PK[]" value="<?php echo $unit['UnitPk']; ?>"/>
            </li>
            <!-- <li class="center-li-contents">
                <input type="checkbox" name="unit_isOccupied_temp[]" <?php // echo $isOccupied; ?>/>
                <input type="hidden" name="unit_isOccupied_values[]" value="<?php // echo $unit['IsOccupied']; ?>"/>
            </li> -->     
            <li class="center-li-contents"><input maxlength="10" name="unit_square_meters[]" type="text" class="fieldset-forms-1fourth-length-input input-integer" value="<?php echo $unit['SquareMeters']; ?>"/></li>   
        </ul>
        <?php } } else { ?>
        <ul class="fieldset-forms-li-2-cols">
            <li class="center-li-contents">
                <input maxlength="20" name="unit_numbers[]" type="text" class="unit-unique-value fieldset-forms-1fourth-length-input"/>
                <input type="hidden" name="unit_PK[]" value="0"/>
            </li>
            <!-- <li class="center-li-contents">
                <input type="checkbox" name="unit_isOccupied_temp[]" />
                <input type="hidden" name="unit_isOccupied_values[]" value="1" checked/>
            </li> -->        
            <li class="center-li-contents"><input maxlength="10" name="unit_square_meters[]" type="text" class="fieldset-forms-1fourth-length-input input-integer" /></li>   
        </ul>    
        <?php } ?>
        </div>
		<div class="selection-form-submit float-left">
			<button id="unit-list-add-button" class="addline-button">Add</button>
		</div> 
		<div id="unit-list-addline-error-box" class="addline-error-box error-box float-left hidden"></div>
		<div class="clear"></div>
    </fieldset>
    <div class="clear"></div>
</div> <!-- end of unit list -->

<?php if($restriction_level > 0){?>
	<div class="wrapper-fieldset-forms">
		<div id="unit-submit-error-box" class="submit-error-box error-box hidden"></div>
		<div class="form-submit">
			<input id="unit-save-button" class="submit-positive" type="submit" value="Save" name="Save" />
			<input class="submit-netagive" type="submit" value="Cancel" name="Cancel"/>
		</div>
	</div> <!-- end of form submit buttons -->
<?php }?>
</form> <!-- end of post form -->
<?php
}
require DOCROOT . '/template/footer.php';
?>