<?php
ob_start();
$page_name = 'Overdue Accounts';

require_once '../../init.php';

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
$fail_accounts_sent_mail = array();

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
	$cut_notification_list = $dbhandler->getCutNotification(array($userPK, 0, $Session->read('user_company_selection_key'),$buildingPK));
} else {
	$cut_notification_list = NULL;
}

if (isset($_POST['IsSendNotificaton_isActive_values'])) {
	
	$isNotified = !empty($_POST['IsSendNotificaton_isActive_values']) ? $_POST['IsSendNotificaton_isActive_values'] : null;
	$bulk_sms = array();
	$today = Prima::getSaveDate();
	$default_date = '1900-01-01';
	$fail_records_created = array();

	if (!empty($isNotified) && !empty($cut_notification_list) && count($isNotified) === count($cut_notification_list)) {
		$len = count($cut_notification_list);
		
		for ($i = 0; $i < $len; $i++) {
			$notification = $cut_notification_list[$i];
			$isClientNotified = !empty($isNotified[$i]) ? true: false;
			$outstanding_amount = Prima::formatDecimal($notification['OutstandingAmount']);
			$account_number = $notification['AccountNumber'];
		
			if ($isClientNotified === true && !empty($notification['Email'])) {
				if (!Prima::mailCustomer($notification['Email'], $notification['AccountNumber'], $outstanding_amount)) {
				// if (false) {
					$fail_accounts_sent_mail[] = 'Account: ' . $notification['AccountNumber'] . ', Email: ' . $notification['Email'];
				} 
			}
			
			if ($isClientNotified === true && !empty($notification['Cellphone'])) {	
				$text_message = 'Good day, your account ' . $account_number . ' with Triple M Metering is in arrears by R' . $outstanding_amount . ' ,please settle immediately to avoid disconnection. Thank you Tel: 012 653 0600';
				$bulk_sms[] = array(Prima::formatCellphoneNumber($notification['Cellphone']), $text_message);
			}
			
			if (!empty($notification['Cellphone']) || !empty($notification['Email'])) {	
				$mail_date = $isClientNotified === true ? $today : $default_date;
				
				$credit_managementPK = $dbhandler->createCreditManagement(array(
					$userPK,
					0,
					$notification['CustomerPk'],
					$notification['Surname'],
					$notification['Cellphone'],
					$notification['Email'],
					$notification['AccountNumber'],
					$notification['OutstandingAmount'],
					$notification['TransactionDate'],
					$mail_date,
					$mail_date,
					$default_date,
					$default_date,
					$default_date,
					$default_date,
					$default_date,
					$notification['IsAgreement']
				));
				
				if ($credit_managementPK > 0) {
					$dbhandler->createCutNotification(array(
						$userPK,
						$credit_managementPK,
						$notification['CustomerPk'],
						$notification['Surname'],
						$notification['Cellphone'],
						$notification['Email'],
						$notification['AccountNumber'],
						$notification['OutstandingAmount'],
						$notification['TransactionDate'],
						$mail_date,
						$mail_date,
						$notification['IsAgreement'],
						$notification['BillingAccountPk']
					));
				} else {
					$fail_records_created[] = $notification;
				}
			}
		}
		
		$hasNoError = true;
		
		if (!empty($fail_accounts_sent_mail)) {
			$Session->write('Fail2', 'Some accounts did not recieve the mail<br />');
			$hasNoError = false;
		}
		
		if (!empty($fail_records_created)) {
			$Session->write('Fail3', 'One or more records did not saved in the database<br />');
			$hasNoError = false;
		}
		
		if (!empty($bulk_sms)) {
			if (!Prima::send_csv_mail($bulk_sms, null)) {
			// if (false) {
				$Session->write('Fail', 'Fail to send Bulk SMS.');
				$hasNoError = false;
			}
		} else {
			$Session->write('Fail', 'There are no accounts to send.');
			$hasNoError = false;
		}
		
		if ($hasNoError) {
			$Session->write('Success', '<strong>Cut notifications</strong> sent.');
			header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF'] . '?choose_building=' . $buildingPK . '&' . $view_type);
			exit;
		}
	} else {
		$Session->write('Fail', 'An error occured when sending notifications.');
		// possible error is data that are posted are not equal to the data that are retrieved.
	}
}

if (isset($_POST['Cancel'])) {
	header('Location: ' . DOMAIN_NAME);
	exit;
}

?>

<div class="sub-menu-title"><h1>Cut Notification</h1></div>
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
						<th>Send Notification</th>	
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($cut_notification_list)) { 
					foreach ($cut_notification_list as $customer) { 
						$isChecked = $customer['IsSendNotification'] == 1 ? 'checked' : ''; ?>
					<tr>
						<td><?php echo $customer['AccountNumber']; ?></td> 
						<td><?php echo $customer['Surname']; ?></td>
						<td><?php echo $customer['Cellphone']; ?></td>
						<td><?php echo $customer['Email']; ?></td>
						<td class="text-align-right"><?php echo Prima::formatDecimal($customer['OutstandingAmount']); ?></td>
						<td style="text-align: center;">
							<input type="checkbox" <?php echo $isChecked; ?> name="IsSendNotificaton_isActive_temp[]">
                    		<input type="hidden" name="IsSendNotificaton_isActive_values[]" value="<?php echo $customer['IsSendNotification']; ?>"/>
						</td> 
					</tr>
					<?php } } ?>				
				</tbody>
			</table>			
		</div>
	</div>
	<?php if (isset($cut_notification_list) && empty($cut_notification_list)) { ?>
	<h3>No Records Found!</h3>
	<?php } if (!empty($cut_notification_list)) { ?>
	<div class="wrapper-fieldset-forms ">
		<div id="parameter-submit-error-box" class="submit-error-box error-box hidden"></div>
		<div class="form-submit" style="margin-left:35%;">
			<input type="submit" id="parameter-save-button" value="Notify Customers" class="submit-positive" name="Notify" style="width: 130px;"/>
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
