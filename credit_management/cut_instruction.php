<?php
ob_start();
$page_name = 'BILLING';

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

$view_class = 'hidden';
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;
$Building = new Building($dbh);
$building_list = $Building->getBuilding(array($userPK, 0));
//Business Function User Menu
$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

// UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

// Cut Notification
$buildingPK = 0;
if(isset($_GET['View_All'])){
	$view_class = 'show';
	$view_type = 'View_All=View All';
}
if(isset($_GET['View'])){
	$view_class = 'show';
	$view_type = 'View=View';
	$buildingPK = $_GET['choose_building'];
}
if ($view_class === 'show' ) {
	$cut_instruction_list = $dbhandler->getCutInstruction(array($userPK, 0, $Session->read('user_company_selection_key'),$buildingPK ));
} else {
	$cut_instruction_list = NULL;
}

if (isset($_POST['Instruct'])) {
	$today = Prima::getSaveDate();
	$len = count($cut_instruction_list);
	$hasNoError = true;
	for ($i = 0; $i < $len; $i++) {
		$instruction = $cut_instruction_list[$i];
		if (isset($_POST['IsSendInstruction_isActive_temp'.$i])) {
			if ( !$dbhandler->updateCutInstruction(array($userPK,$instruction['CreditManagementPk'],$today))) {}
				//$hasNoError = false;
		}
	}
	
	if ($hasNoError) {
		$Session->write('Success', '<strong>Cut instruction</strong> sent.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF'] . '?choose_building=' . $buildingPK . '&' . $view_type);
		exit;
	} 
}

if (isset($_POST['Cancel'])) {
	header('Location: ' . DOMAIN_NAME);
	exit;
}

?>

<div class="sub-menu-title"><h1>Cut Instruction</h1></div>
<form method="get" class="hover-cursor-pointer">
	<div id="meter-critera" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms clear">
			<legend>Building Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li>Building:</li>
				<li>
					<select id="meter-selection-building" class="selection-required-input" name="choose_building">
						<option value="0">Please choose a building</option>
						<?php 
						if (!empty($building_list)) { 
						foreach($building_list as $building) { 
							$selected = $buildingPK == $building['BuildingPk'] ? 'selected="' . $building['BuildingPk'] . '"':''; ?>
							<option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
						<?php } } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="meter-selection-view" type="submit" value="View" name="View"/>
				<input id="meter-selection-view_all" type="submit" value="View All" name="View_All"/>
			</div> 
			<div id="meter-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div> <!-- end of building selection -->
</form> <!-- end of get form -->
<?php 
if ($Session->check('Success')) {
	echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
	$Session->sessionUnset('Success');
} 
?>
<?php if ($view_class === 'show' ) { ?>   
<div id="parameter-submit-result-error-box" class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>

<form method="post" >
	<div class="table-wrapper">
		<div class="wrapper-paging">
			<ul>
				<li><a class="paging-back">&lt;</a></li>
				<li><a class="paging-this"><b>0</b><span>x</span></a></li>
				<li><a class="paging-next">&gt;</a></li>
			</ul>
		</div>
		<div class="wrapper-panel">			
			<table id="cut_notification" class="billing scrollable planning-table planning-table-striped planning-table-hover">
				<thead>
					<tr>
						<th>Customer No.</th>
						<th>Customer Name</th>
						<th>Cellphone</th>
						<th>Email</th>
						<th>Amount Outstanding</th>
						<th>Send Instruction</th>	
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($cut_instruction_list)) { 
					$c = 0;
					foreach ($cut_instruction_list as $customer) { 
						//$isChecked = $customer['CutInstructionDate'] != '1900-01-01' ? 'checked' : ''; ?>
						<tr>
							<td><?php echo $customer['AccountNumber']; ?></td>
							<td><?php echo $customer['Surname']; ?></td>
							<td><?php echo $customer['Cellphone']; ?></td>
							<td><?php echo $customer['Email']; ?></td>
							<td class="text-align-right"><?php echo Prima::formatDecimal($customer['OutstandingAmount']); ?></td>
							<td style="text-align: center;">
								<input type="checkbox" name="IsSendInstruction_isActive_temp<?php echo $c++; ?>">
							</td> 
						</tr>
					<?php } } ?>				
				</tbody>
			</table>			
		</div>
	</div>
	<?php if (isset($cut_instruction_list) && empty($cut_instruction_list)) { ?>
	<h3>No Records Found!</h3>
	<?php } if (!empty($cut_instruction_list)) { ?>
	<div class="wrapper-fieldset-forms ">
		<div id="parameter-submit-error-box" class="submit-error-box error-box hidden"></div>
		<div class="form-submit" style="margin-left:35%;">
			<input type="submit" id="parameter-save-button" value="Send Instruction" class="submit-positive" name="Instruct" style="width: 130px;"/>
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel"/>
		</div>
	</div> 
	<?php } ?>
</form>

<?php
	}
?>

<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">
	$(function(){ TABLE.paginate('.billing', 10); });
</script>
