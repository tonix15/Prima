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

//Business Function User Menu
$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

// Cut Notification
$cut_instruction_list = $dbhandler->getCutInstruction(array($userPK, 0, $Session->read('user_company_selection_key')));

// UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

if (isset($_POST['Instruct'])) {
	
	$today = Prima::getSaveDate();

	$len = count($cut_instruction_list);
	$hasNoError = true;
	for ($i = 0; $i < $len; $i++) {
		$instruction = $cut_instruction_list[$i];
		if (isset($_POST['IsSendInstruction_isActive_temp'.$i]) && $instruction['CutInstructionDate'] == '1900-01-01') {
			if ( !$dbhandler->updateCutInstruction(array($userPK,$instruction['CreditManagementPk'],$today))) {}
				//$hasNoError = false;
		}
	}
	
	if ($hasNoError) {
		$Session->write('Success', '<strong>Cut instruction</strong> sent.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF']);
		exit;
	} 
	/*
	else {
		$Session->write('Fail', 'Error occured during update.');
		$hasNoError = false;
	} */
}

if (isset($_POST['Cancel'])) {
	header('Location: ' . DOMAIN_NAME);
	exit;
}

?>

<div class="sub-menu-title"><h1>Cut Instruction</h1></div>

<?php 
if ($Session->check('Success')) {
	echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
	$Session->sessionUnset('Success');
} else if ($Session->check('Fail')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail') . '</div>';
	$Session->sessionUnset('Fail');
} else if ($Session->check('Fail2')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail2') . '<br />';
	var_dump($fail_accounts_sent_mail);
	echo '</div>';
	$Session->sessionUnset('Fail2');
}

if ($Session->check('Fail3')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail3') . '<br />';
	var_dump($fail_records_created);
	echo '</div>';
	$Session->sessionUnset('Fail3');
}
?>
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
						$isChecked = $customer['CutInstructionDate'] != '1900-01-01' ? 'checked' : ''; ?>
						<tr>
							<td><?php echo $customer['AccountNumber']; ?></td>
							<td><?php echo $customer['Surname']; ?></td>
							<td><?php echo $customer['Cellphone']; ?></td>
							<td><?php echo $customer['Email']; ?></td>
							<td class="text-align-right"><?php echo Prima::formatDecimal($customer['OutstandingAmount']); ?></td>
							<td style="text-align: center;">
								<input type="checkbox" <?php echo $isChecked; ?> name="IsSendInstruction_isActive_temp<?php echo $c++; ?>">
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
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">
	$(function(){ TABLE.paginate('.billing', 10); });
</script>
