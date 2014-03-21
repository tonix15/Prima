<?php
require_once '../../init.php';
$page_name = 'Account Detail';
$companyPK =  $_SESSION['user_company_selection_key'];

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
$tenant_no = '';

$userCredentials = $User->getUserCredentials();
$userPK = $userCredentials['UserPk'];

// Building
$building_list = $dbhandler->getBuilding(array($userPK, 0));
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

// Unit
$Unit = new Unit($dbh);
$unitPK = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit'] : 0;
$unit_list = null;

// Customer
$Customer = new Customer($dbh);
$customer_data = null;
$customer_title_typePk = 0;
$customer_initials = '';
$customer_surname = '';
$customer_name = '';
$customer_idno = '';
$customer_language_typePK = 0;
$customer_company_name = '';
$customer_company_registration_no = '';
$customer_vat_no = '';
$customer_comments = '';
$customer_arrangement_date = '';

require DOCROOT . '/template/header.php';

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

$customer_list = $dbhandler->getCustomer(array($userPK, 0));
$customerPK = 0;

// Unit
$Unit = new Unit($dbh);
$unitPK = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit'] : 0;
$unit_list = null;

// Billing Account
$Billing = new Billing($dbh);
$billing_account_list = null;
$billing_account_data = null;
$billing_accountPK = !empty($_GET['choose_tenant']) ? (int) $_GET['choose_tenant'] : 0;
$billing_occupancy_date = '';
$billing_vacancy_date = '';
$billing_deposit_required = "0.00";
$billing_isDeposit_refundable = '';
$billing_isAgreement_received = '';
$billing_isPrepaid = '';
$billing_isOwner = '';
$billing_owner_email = '';
$new_billing_accountPK = 0;


if (!empty($buildingPK)) { 
	$unit_list = $Unit->getUnit(array($userPK, 0, $buildingPK));
}
if (!empty($unitPK)) {
	$billing_account_list = $Billing->getBillingAccount(array($userPK, 0, $buildingPK, $unitPK, 1));
}

$billing_accountPK = $new_billing_accountPK > 0 ? $new_billing_accountPK : $billing_accountPK;
$billing_accountFK = 0;

// UI
$view_class = 'hidden';
$save_name = '';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';

$import_account_detail = NULL;

if (isset($_GET['View'])) { 
	$billing_accountFK = !empty($_GET['choose_tenant']) ? $_GET['choose_tenant']:0;
	$import_account_detail = $dbhandler->sageImportAccountDetail(array($userPK, $billing_accountFK));	
	$errmsg = !empty($import_account_detail) ? '':'<h2>No Entries Found.</h2>';
	$tenant_no = $_GET['tenant_no'];
}
else if (isset($_POST['Cancel'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<form method="get" class="hover-cursor-pointer" >
<div class="sub-menu-title"><h1>Account Detail</h1></div>
<div class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<div id="customer-critera" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Customer Criteria</legend>
        <ul class="fieldset-forms-li-2-cols">
            <li>Building:</li>
            <li>
                <select id="customer-selection-building" class="selection-required-input" name="choose_building">
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
            <li>
                <select id="customer-selection-unit" class="selection-required-input" name="choose_unit">
                    <option value="0">Please choose a unit</option>
                    <?php 
                    if (!empty($unit_list)) { 
                    foreach($unit_list as $unit) { 
                        $selected = $unitPK == $unit['UnitPk'] ? 'selected="' . $unit['UnitPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $unit['UnitPk']; ?>"><?php echo $unit['NumberBk']; ?></option>
                    <?php } } ?>
                </select>
            </li>
            <li>Tenant Number:</li>
            <li>
                <select id="customer-selection-tenant1" class="selection-required-input" name="choose_tenant">
                    <option value="0">Please choose a tenant no.</option>
                    <?php 
                    if (!empty($billing_account_list)) { 
                    foreach($billing_account_list as $billing_account) { 
                        $selected = $billing_accountPK == $billing_account['BillingAccountPk'] ? 'selected="' . $billing_account['BillingAccountPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $billing_account['BillingAccountPk']; ?>"><?php echo $billing_account['NumberBk']; ?></option>
                    <?php } } ?>
                </select>
				<input type="hidden" name="tenant_no" value="<?php echo $tenant_no; ?>" />
            </li>
            <!-- <li>Billing Account:</li>
            <li><select><option value="0">Please choose a customer no.</option></select></li> -->
        </ul>
        <div class="selection-form-submit float-left">
            <input id="customer-selection-view" type="submit" value="View" name="View"/>
			<?php if($restriction_level > 0){ ?>
				<input id="customer-selection-create" type="submit" value="Create" name="Create"/> 
			<?php }?>
			<button id="customer-selection-move" class="hidden">Move</button>			
        </div>
		<div id="customer-selection-success-box" class="selection-error-box error-box float-left hidden" style="border-color:#4D9615;">Ready to move customer</div>
        <div id="customer-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of building selection -->

<?php if(!empty($import_account_detail)){ ?>
	<div class="wrapper-panel">			
		<table id="cut_notification" class="billing scrollable planning-table planning-table-striped planning-table-hover">
			<thead>
				<tr>
					<th>Transaction Date</th>
					<th>Entry Type</th>
					<th>Reference</th>
					<th>Description</th>
					<th>Amount</th>
					<th>Balance</th>	
				</tr>
			</thead>
			<tbody>			
				<?php foreach($import_account_detail as $account_detail){ 
					if ( $account_detail['EntryType'] == 'INV' ) {
						$ref = '<a title="view invoice detail" href="' . DOMAIN_NAME  . '/reporting/Customers/invoice_detail.php?'.
						'choose_building=' . $_GET['choose_building'] . '&' .
						'choose_unit=' . $_GET['choose_unit'] .  '&' .
						'choose_tenant=' . $_GET['choose_tenant'] .  '&' .
						'choose_invoice=' . $account_detail['Reference'] . '&' .
						'selected_invoice=' . $tenant_no . '&' .
						'View=View">' .  $account_detail['Reference'] . '</a>';
					} else {
						$ref = $account_detail['Reference'];
					}
				?>
				<tr>
					<td><?php echo $account_detail['TransactionDate'];?></td>
					<td><?php echo $account_detail['EntryType'];?></td>
					<td><?php echo $ref; ?></td>
					<td><?php echo $account_detail['Description'];?></td>
					<td class="table-column-text-align-right"><?php echo number_format($account_detail['Amount'], 2, '.', ',');?></td>
					<td class="table-column-text-align-right"><?php echo number_format($account_detail['Balance'], 2, '.', ',');?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>			
	</div>
<?php 
	  } 
	  else{ echo $errmsg; }
?>
</form> <!-- end of get form -->

<?php require DOCROOT . '/template/footer.php'; ?>