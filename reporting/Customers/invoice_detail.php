<?php
require_once '../../init.php';

$page_name = 'Invoice Detail';

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
	else if($Session->read('user_company_selection_key') <= 0){
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

// Building
$Building = new Building($dbh);
$rate_account_list = null;
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

// Unit
$Unit = new Unit($dbh);
$unitPK = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit'] : 0;
$unit_list = null;

// Billing Account
$Billing = new Billing($dbh);
$billing_account_list = null;

//Invoice
$invoice_list = NULL;
$invoice_reference = NULL;
$invoice_number = NULL;
$customerNumberBk  = NULL;

$line_amount_ex_vat_total = 0;
$vat_total = 0;
$line_amount_in_vat = 0;

// UI
$msg = NULL;

if (isset($_GET['View'])){ 
	$buildingPK = $_GET['choose_building'];
	$unitPK = $_GET['choose_unit'];
	$billing_accountPK = $_GET['choose_tenant'];
	$invoice_number = $_GET['choose_invoice'];
	
	$customerNumberBk = $_GET['selected_invoice'];
	if(!empty($customerNumberBk)){
		$params = array(
			(int)$userPK,
			(int)$Session->read('user_company_selection_key'),
			(int)$buildingPK,
			(int)$unitPK,
			$customerNumberBk
		);
		$invoice_reference = $dbhandler->getInvoice($params);
		unset($params);
	}
	
	$params = array(
		$userPK,
		$billing_accountPK,
		$invoice_number
	);
	$invoice_list = $dbhandler->sageImportInvoiceDetail($params);
	$msg = !empty($invoice_list) ? NULL:'<h2>No Entries Found.</h2>';
}

$building_list = $dbhandler->getBuilding(array($userPK, 0));
if (!empty($buildingPK)) { 
	$unit_list = $dbhandler->getUnit(array($userPK, 0, $buildingPK));
}
if (!empty($unitPK)) {
	$billing_account_list = $dbhandler->getBillingAccount(array($userPK, 0, $buildingPK, $unitPK, 1));
}
if(!empty($billing_account_list)){
	$customerNumberBk = $_GET['selected_invoice'];
	$params = array(
		(int)$userPK,
		(int)$Session->read('user_company_selection_key'),
		(int)$buildingPK,
		(int)$unitPK,
		$customerNumberBk
	);
	$invoice_reference = $dbhandler->getInvoice($params);
	unset($params);
}
?>
<form method="get" class="hover-cursor-pointer" >
<div class="sub-menu-title"><h1>Invoice Details</h1></div>
<div id="customer-critera" class="wrapper-fieldset-forms">
    <fieldset class="fieldset-forms">
        <legend>Invoice Detail Selection</legend>
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
                <select id="customer-selection-tenant" class="selection-required-input" name="choose_tenant">
                    <option value="0">Please choose a tenant no.</option>
                    <?php 
                    if (!empty($billing_account_list)) { 
                    foreach($billing_account_list as $billing_account) { 
                        $selected = $billing_accountPK == $billing_account['BillingAccountPk'] ? 'selected="' . $billing_account['BillingAccountPk'] . '"':''; 
                    ?>
                        <option <?php echo $selected; ?> value="<?php echo $billing_account['BillingAccountPk']; ?>"><?php echo $billing_account['NumberBk']; ?></option>
                    <?php } } ?>
                </select>
                <input type="hidden" name="selected_invoice" value="<?php echo $customerNumberBk; ?>" />
            </li>
			<li>Invoice Number:</li>
            <li>
                <select id="customer-selection-invoice" class="selection-required-input" name="choose_invoice">
                    <option value="0">Please choose an invoice no.</option>   
                    <?php 
                    	if(!empty($invoice_reference)):
                    		foreach ($invoice_reference as $invoice):
							$selected = $invoice['InvNumber'] == $invoice_number ? 'selected':'';
                    ?>                 
                    <option value="<?php echo $invoice['InvNumber']?>" <?php echo  $selected  ?> ><?php echo $invoice['InvNumber']?></option>
                    <?php 
                    		endforeach;
                    	endif;
                    ?>
                </select>                
            </li>
            <!-- <li>Billing Account:</li>
            <li><select><option value="0">Please choose a customer no.</option></select></li> -->
        </ul>
        <div class="selection-form-submit float-left">
            <input id="customer-selection-view" type="submit" value="View" name="View"/>			
        </div>
		<div id="customer-selection-success-box" class="selection-error-box error-box float-left hidden" style="border-color:#4D9615;">Ready to move customer</div>
        <div id="customer-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of building selection -->
</form> <!-- end of get form -->

<?php if(!empty($invoice_list)): ?>
		<div class="table-wrapper billing-data-list-container">
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
							<th>Transaction Date</th>
							<th>Line Description</th>
							<th>Meter Number</th>
							<th>Start Reading</th>
							<th>End Reading</th>
							<th>Consumption</th>
							<th>Price Ex Vat</th>
							<th>Line Amount Ex Vat</th>
							<th>Vat</th>
							<th>Price In Vat</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($invoice_list as $invoice):?>
							<tr>
								<td class="table-column-text-align-center"><?php echo $invoice['TxDate']; ?></td>
								<td class="table-column-text-align-center"><?php echo $invoice['cDescription']; ?></td>
								<td class="table-column-text-align-center"><?php echo $invoice['ucIDInvTxSTMeterNo']; ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['ufIDInvTxSTLastRead'], 0, '', ','); ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['ufIDInvTxSTThisRead'], 0, '', ','); ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['fQuantity'], 0, '', ','); ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['fUnitPriceExcl'], 2, '.', ','); ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['fQuantityLineTotExcl'], 2, '.', ','); ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['fQuantityLineTaxAmount'], 2, '.', ','); ?></td>
								<td class="table-column-text-align-right"><?php echo number_format($invoice['fQuantityLineTotIncl'], 2, '.', ','); ?></td>
							</tr>							
						<?php 
							$line_amount_ex_vat_total += $invoice['fQuantityLineTotExcl'];
							$vat_total += $invoice['fQuantityLineTaxAmount'];
							$line_amount_in_vat = $invoice['fQuantityLineTotIncl'];
							endforeach; 
						?>
						<tr>
							<td><strong>Total:</strong></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="table-column-text-align-right"><?php echo number_format($line_amount_ex_vat_total, 2, '.', ','); ?></td>
							<td class="table-column-text-align-right"><?php echo number_format($vat_total, 2, '.', ','); ?></td>
							<td class="table-column-text-align-right"><?php echo number_format($line_amount_in_vat, 2, '.', ','); ?></td>
						</tr>
					</tbody>
				</table>
				<?php
					//Collect the output buffer into a variable
					$html = ob_get_contents();
					ob_end_flush();
					
					$title = 'Invoice Detail Report';
					$Session->write('title', $title);
					$Session->write('content', $html);
					
					unset($title);
					unset($html);
				?>
				<label>
					Export as: 
					<a title="PDF" href="<?php echo DOMAIN_NAME . '/processing/exportAsPdf.php';?>">PDF</a>
					<a title="SpreadSheet" href="<?php echo DOMAIN_NAME . '/processing/exportAsCSV.php';?>">SpreadSheet</a>
				</label>
			</div>
		</div>

<?php 
	else: echo $msg;		
	endif; 
require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>