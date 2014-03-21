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

// Reconnection Notification
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
	$reconnection_instruction_list = $dbhandler->getInstantReconnectionInstruction(array($userPK, 0, $Session->read('user_company_selection_key'),$buildingPK ));
} else {
	$reconnection_instruction_list = NULL;
}

if (isset($_POST['Update'])) {
	$reconnection_instruction_list = $dbhandler->getInstantReconnectionInstruction(array($userPK, 0, $Session->read('user_company_selection_key'),$buildingPK ));
	$user_list = $dbhandler->getUser(array($userPK, 0, $Session->read('user_company_selection_key')));
	$bulk_sms = array();
	$today = Prima::getSaveDate();
	$fail_records_updated = array();
	$hasNoError = true;
	$c = 0;
	$pass = 'Failed';
	foreach ( $reconnection_instruction_list as $instruction ) {
		if (isset($_POST['IsSendInstruction_isActive_temp'.$c]) && $instruction['ReconnectionInstructionDate'] == '1900-01-01') {	
			if ( !$dbhandler->updateInstantReconnectionInstruction(array($userPK,$instruction['CreditManagementPk'],$today))) 
				$fail_records_updated[] = $instruction['CreditManagementPk'];
			else { 
				$pass = 'Success';
				//BillingAccount: Get  Biiling Account Row
				$billing_account_list = $dbhandler->getBillingAccount(array($userPK, $instruction['BillingAccountFk']));
				$billing_account = $billing_account_list[0];
				//Building Termination Period: Get  Team
				$building_termination_period_list = $dbhandler->getBuildingTerminationPeriod(array($userPK, 0, $billing_account['BuildingFk'], $billing_account['UnitFk']));
				$building_termination = $building_termination_period_list[0];
				//Unit: Get all users under that team
				foreach ( $user_list as $user ) {
					if ($user['TeamFk'] == $building_termination['TeamFk']) {
					$text_message = 'Hi ' . $user['DisplayName'] . '. This is for reconnection: \n\n Client:  ' . $instruction['Surname'] . '\nBuilding:  ' . $building_termination['BuildingName'] . '\nUnit:  ' . $building_termination['UnitNumberBk'];
					$bulk_sms[] = array(Prima::formatCellphoneNumber($user['Cellphone']), $text_message);
					}
				}
			}
		}
		$c++;
	}
	$_SESSION['p'] = $pass ;
	if (!empty($fail_records_updated)) {
		$Session->write('Fail1', 'One or more records did not updated in the database<br />');
		$hasNoError = false;
	}
	
	if (!empty($bulk_sms)) {
		if (!Prima::send_csv_mail($bulk_sms, null)) {
			$Session->write('Fail2', 'Fail to send Bulk SMS.');
			$hasNoError = false;
		}
	} else {
		$Session->write('Fail3', 'There are no users to send.');
		$hasNoError = false;
	}
			
	if ($hasNoError) {
		$_SESSION['smstest'] = $bulk_sms;
		
		$Session->write('Success', '<strong>Reconnection instruction</strong> sent.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF'] . '?choose_building=' . $buildingPK . '&' . $view_type);
		exit;
	} 
}

if (isset($_POST['Cancel'])) {
	header('Location: ' . DOMAIN_NAME);
	exit;
}

?>

<div class="sub-menu-title"><h1>Instant Reconnection Instruction</h1></div>
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
						echo 'Final Pass = ' . $_SESSION['p'] . '<br >';
					unset($_SESSION['p']);
} 
if ($Session->check('Fail1')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail1') . '<br />';
	var_dump($fail_records_updated);
	echo '</div>';
	$Session->sessionUnset('Fail1');
}
if ($Session->check('Fail2')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail2') . '<br />';
	var_dump($bulk_sms);
	echo '</div>';
	$Session->sessionUnset('Fail2');
}
if ($Session->check('Fail3')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail3') . '<br />';
	var_dump($bulk_sms);
						echo 'Final Pass = ' . $_SESSION['p'] . '<br >';
					unset($_SESSION['p']);
	echo '</div>';
	$Session->sessionUnset('Fail3');
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
			<table id="Reconnection_notification" class="billing scrollable planning-table planning-table-striped planning-table-hover">
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
					<?php if (!empty($reconnection_instruction_list)) { 
					$c = 0;
					foreach ($reconnection_instruction_list as $customer) { 
						//$isChecked = $customer['ReconnectionInstructionDate'] != '1900-01-01' ? 'checked' : ''; ?>
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
	<?php if (isset($reconnection_instruction_list) && empty($reconnection_instruction_list)) { ?>
	<h3>No Records Found!</h3>
	<?php } if (!empty($reconnection_instruction_list)) { ?>
	<div class="wrapper-fieldset-forms ">
		<div id="parameter-submit-error-box" class="submit-error-box error-box hidden"></div>
		<div class="form-submit" style="margin-left:35%;">
			<input type="submit" id="parameter-save-button" value="Update" class="submit-positive" name="Update" style="width: 130px;"/>
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
