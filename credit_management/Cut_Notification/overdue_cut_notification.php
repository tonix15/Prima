<?php
ob_start();
$page_name = 'BILLING';

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

//Business Function User Menu
$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

// Cut Notification
$cut_notification_list = $dbhandler->getCutNotification(array($userPK, 0, $Session->read('user_company_selection_key')));

//UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';
$fail_accounts_sent_mail = array();

if (isset($_POST['Notify'])) {
	$isNotified = !empty($_POST['IsSendNotificaton_isActive_values']) ? $_POST['IsSendNotificaton_isActive_values'] : null;
	$bulk_sms = array();
	
	if (!empty($isNotified) && !empty($cut_notification_list) && count($isNotified) === count($cut_notification_list)) {
		$len = count($cut_notification_list);
		
		for ($i = 0; $i < $len; $i++) {
			$notification = $cut_notification_list[$i];
			$isClientNotified = !empty($isNotified[$i]) ? true: false;
		
			if ($isClientNotified === true) {
				$outstanding_amount = Prima::formatDecimal($notification['OutstandingAmount']);
				$account_number = $notification['AccountNumber'];
					
				$text_message = 'Good day, your account ' . $account_number . ' with Triple M Metering is in arrears by ' . $outstanding_amount . ' ,please settle immediately to avoid disconnection. Thank you Tel: 012 653 0600' . Prima::getFileDateTime();
				$bulk_sms[] = array(Prima::formatCellphoneNumber($notification['Cellphone']), $text_message);
				
				if (!Prima::notifyCustomer($notification['Email'], $notification['AccountNumber'], $outstanding_amount)) {
					$fail_accounts_sent_mail[] = $notification;
				} 
			}
		}
		
		if (!empty($fail_accounts_sent_mail)) {
			$Session->write('Fail2', 'Some accounts did not recieve the mail<br />');
		}
		
		if (!empty($bulk_sms)) {
			if (!Prima::send_csv_mail($bulk_sms, null)) {
				$Session->write('Fail', 'Fail to send Bulk SMS.');
			}
		} else {
			$Session->write('Fail', 'There are no accounts to send.');
		}
		
		
		$Session->write('Success', '<strong>Cut notifications</strong> sent.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF']);
		exit;
	} else {
		$Session->write('Fail', 'An error occured when sending notifications.');
	}
	
	if (!empty($cut_notification_list)) {
		foreach ($cut_notification_list as $notification) {
			$dbhandler->createCutNotification(array(
				$userPK,
				0,
				$notification['CustomerFk'],
				$notification['Surname'],
				$notification['Cellphone'],
				$notification['Email'],
				$notification['AccountNumber'],
				$notification['OutstandingAmount'],
				$notification['TransactionDate'],
				$notification['SMSDate'],
				$notification['EmailDate'],
				$notification['IsSendNotification'],
				$notification['IsOverdue'],
				$notification['IsAgreement'],
			));
		}
	}
}

if (isset($_POST['Cancel'])) {
	header('Location: ' . DOMAIN_NAME);
	exit;
}

?>

<div class="sub-menu-title"><h1>Cut Notification</h1></div>
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
						<th>Send Notification</th>	
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($cut_notification_list)) { 
					foreach ($cut_notification_list as $customer) { 
						$isChecked = $customer['IsSendNotification'] == 1 ? 'checked' : ''; ?>
					<tr>
						<td>
							<?php echo $customer['AccountNumber']; ?>
							<input type="hidden" name="credit_management_PK[]" value="<?php echo $customer['CreditManagementPk']; ?>"/></td>
						<td><?php echo $customer['Surname']; ?></td>
						<td><?php echo $customer['Cellphone']; ?></td>
						<td><?php echo $customer['Email']; ?></td>
						<td class="text-align-right"><?php echo Prima::formatDecimal($customer['OutstandingAmount']); ?></td>
						<td style="text-align: center;">
							<input type="checkbox" <?php echo $isChecked; ?> name="IsSendNotificaton_isActive_temp[]">
                    		<input type="hidden" name="IsSendNotificaton_isActive_values[]" value="<?php echo $utility['IsActive']; ?>"/>
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
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">
	$(function(){ TABLE.paginate('.billing', 10); });
</script>
