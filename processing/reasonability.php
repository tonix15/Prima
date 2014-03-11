<?php
ob_start();
$page_name = 'Reasonability';

require_once '../init.php';

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
$import_fancy_box = true;
require DOCROOT . '/template/header.php';

//Business Function User Menu
$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

// Billing
$Billing = new Billing($dbh);
$Building = new Building($dbh);
$Utility = new UtilityType($dbh);

$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

//UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_POST['Save'])) {
	$readingPKS = $_POST['readingPKS'];
	$readingAmouns = $_POST['readingAmounts'];
	$len = count($readingPKS);
	
	for ($i = 0; $i < $len; $i++) {
		$Billing->updateBillingReasonability(array($userPK, $readingPKS[$i], $readingAmouns[$i]));
	}
	
	$submit_result = 'success';
	$errmsg = 'Meter readings(s) updated sucessfully';
	$error_class = 'show';
}

if(isset($_GET['View'])) {
	//UI
	
	$reasonable_list = $Billing->getBillingReasonability(array($userPK, $_GET['choose_building']));
	$utility_type_list = $Utility->getUtilityType(array($userPK, 0));
	
	if (!empty($reasonable_list)) {
		$view_class = 'show';
	}
}

if (isset($_POST['Cancel'])) {
	header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$building_list = $Building->getBuilding(array($userPK, 0));
?>

<div class="sub-menu-title"><h1>Reasonability</h1></div>
<div id="parameter-submit-result-error-box" class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<form method="get" class="hover-cursor-pointer">
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
						<option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
					<?php } } ?>
				</select></li>
			</ul>

			<div class="selection-form-submit float-left">
				<input id="building-selection-view" type="submit" value="View" name="View" /> 				
			</div>
			<div id="building-selection-error-box"
				class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div>
</form>
	<!-- end of building selection -->
<form method="post">
	<div class="table-wrapper">
		<div class="wrapper-paging">
			<ul>
				<li><a class="paging-back">&lt;</a></li>
				<li><a class="paging-this"><b>0</b><span>x</span></a></li>
				<li><a class="paging-next">&gt;</a></li>
			</ul>
		</div>
		<div class="wrapper-panel">
			<?php 
				//Buffer the html table with PHP to be stored in variable
				ob_start(); 
			?>
			<table class="billing scrollable planning-table planning-table-striped planning-table-hover">
				<thead>
					<tr>
						<th rowspan="2">Unit No.</th>
						<th rowspan="2">Utility Type</th>
						<th rowspan="2">Meter No.</th>
						<th rowspan="2">Reading</th>
						<th colspan="3">Daily Average Consumption</th>
						<th colspan="2">Monthly Consupmtion</th>
						<th rowspan="2">Photo</th>			
					</tr>
					<tr>	
						<th>Prior Month</th>
						<th width="100">This Month</th>
						<th>% Deviation</th>
						<th>Prior Month</th>
						<th>This Month</th>		
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($reasonable_list)) { 
					foreach ($reasonable_list as $reasonable) { 
						$image_title = $reasonable['ReadingDay'];
						$image_file = Prima::getMeterPhoto($reasonable['MeterFk'], $image_title);	
						$image_title = Prima::isFileExists($reasonable['MeterFk'], $image_title) ? $image_title : '';
						$utility_type = Prima::getUtilityType($utility_type_list, $reasonable['UtilityTypeFk']); 
						$meter_reading_url = DOMAIN_NAME . '/query_and_reporting/reading.php?' . 'choose_building=' . $reasonable['BuildingFk'];
						$meter_reading_url .= '&choose_unit=' . $reasonable['UnitFk'] . '&choose_meter=' . $reasonable['MeterFk'] . '&View=View';  ?>
					<tr>
						<td><?php echo $reasonable['UnitNumber']; ?><input type="hidden" name="readingPKS[]" value="<?php echo $reasonable['ReadingPk']; ?>"/></td>
						<td><?php echo $utility_type['Value']; ?></td>
						<td><a style="color: #727272;" href="<?php echo $meter_reading_url; ?>" target="blank"><?php echo $reasonable['MeterNumber']; ?></a></td>
						<td width="100"><input type="text" class="input-integer reasonability-readings" name="readingAmounts[]" value="<?php echo (int) $reasonable['ReadingAmount']; ?>"/></td>
						<td class="text-align-right"><?php echo number_format ($reasonable['LastReadingAvg'], 2 , '.', ','); ?></td>
						<td class="text-align-right"><?php echo number_format ($reasonable['CurrentReadingAvg'], 2 , '.', ','); ?></td>
						<td class="text-align-right"><?php echo number_format ($reasonable['LastReadingDevPerc'], 2 , '.', ','); ?></td>
						<td class="text-align-right"><?php echo number_format ((int) $reasonable['PreviousConsumption'], 0 , '.', ','); ?></td>
						<td class="text-align-right"><?php echo number_format ((int) $reasonable['CurrentConsumption'], 0 , '.', ','); ?></td>
						<td class="text-align-center"><a href="<?php echo $image_file; ?>" class="fancybox" title="<?php echo $image_title; ?>"><img style="width:40px; height:40px;" src="<?php echo $image_file; ?>"></a></td>
					</tr>
					<?php } } ?>				
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();	
				$key = isset($_GET['choose_building']) ? $_GET['choose_building']:0;
				$building_name = $Building->getBuilding(array($userPK, $key));
				
				$title = 'Reasonability for  ' . ucwords(strtolower($building_name[0]['Name']));
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/query_and_reporting_pdf.php'
			?>
			
		</div>
	</div>
	<?php if (isset($reasonable_list) && empty($reasonable_list)) { ?>
	<h3>No Records Found!</h3>
	<?php } ?>
	<div class="wrapper-fieldset-forms <?php echo $view_class; ?>">
		<?php if($restriction_level > 0) { ?>
		<div id="parameter-submit-error-box" class="submit-error-box error-box hidden"></div>
		<div class="form-submit" style="margin-left:40%;">
			<input type="submit" id="parameter-save-button" value="Save" class="submit-positive" name="Save"/>
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel"/>
		</div>
		<?php } ?>
	</div> 
</form>
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">
	$(function() { TABLE.paginate('.billing', 5); });
</script>
