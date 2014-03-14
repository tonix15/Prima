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

//UI
$view_class = 'hidden';
$error_class = 'hidden';
$submit_result = '';
$errmsg = '';
$prepaid_transactions = NULL;
$file = NULL;
$csv = NULL;
$a = Array();
$b = Array();
if(isset($_POST['Import'])) {
	if ( !empty($_FILES['csv']['tmp_name']) ) {
		$csv = $_FILES['csv'];
		$file = fopen($csv['tmp_name'],"r");
		$_SESSION['CSV_FILE'] = $csv;
		$view_class = 'show';
		copy($csv['tmp_name'], 'c:\wamp\tmp\temp.csv');
	} else {
		unset($_SESSION['CSV_FILE']);
	}
}

if(isset($_POST['Save']) && isset($_SESSION['CSV_FILE'])) {
	$csv_for_saving = $_SESSION['CSV_FILE'];
	$file_for_saving =  fopen('c:\wamp\tmp\temp.csv',"r");
	$hasNoError = true;
	$ctr = 0;
	$receipt_no = '';
	while(! feof($file_for_saving)) { 
		$row_for_saving = (fgetcsv($file_for_saving));
		$account = str_replace(chr(0), '',$row_for_saving[0]);
		$account= preg_replace("/[^a-zA-Z0-9]/", "", $account);
		$reference = str_replace(chr(0), '',$row_for_saving[1]);
		$meter_serial_no = str_replace(chr(0), '',$row_for_saving[2]);
		$receipt_no = str_replace(chr(0), '',$row_for_saving[3]);
		$transaction_date = str_replace(chr(0), '',$row_for_saving[4]);
		$total_amount = str_replace(chr(0), '',$row_for_saving[5]);
		$tariff_charge = str_replace(chr(0), '',$row_for_saving[6]);
		$tax_amount = str_replace(chr(0), '',$row_for_saving[7]);
		$units_kwh = str_replace(chr(0), '',$row_for_saving[8]);
		$arrears_amount = str_replace(chr(0), '',$row_for_saving[9]);
		$fixed_charge = str_replace(chr(0), '',$row_for_saving[10]);
		$transaction_levy = str_replace(chr(0), '',$row_for_saving[11]);
		$b[$ctr] = $dbhandler->createPrepaidTransactions(array($userPK,0,$account,$reference,$meter_serial_no,$receipt_no,$transaction_date, $total_amount, $tariff_charge, $tax_amount, $units_kwh, $arrears_amount, $fixed_charge, $transaction_levy, $userPK));
		if(!$b[$ctr]) {
			$hasNoError = false;
			break;
		}
		$a[$ctr] = array($userPK,'0',$account,$reference,$meter_serial_no,$receipt_no,$transaction_date, $total_amount, $tariff_charge, $tax_amount, $units_kwh, $arrears_amount, $fixed_charge, $transaction_levy, $userPK);
		$ctr++;
	}
	unset($_SESSION['CSV_FILE']);

	if ($hasNoError) {
		// $_SESSION['A'] = $a;
		// $_SESSION['B'] = $b;
		$records = $ctr == 1 ? ' record)' : ' records)' ;
		$Session->write('Success', '<strong>CSV File</strong> successfully imported to database. (' . $ctr . $records);
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF']);
		exit;
	} else {
		$rec = $ctr + 1;
		$Session->write('Fail', 'Importing stops at record ' . $rec  . '.  Receipt No.: ' . $receipt_no);
	}
}

if (isset($_POST['Cancel'])) {
	unset($_SESSION['CSV_FILE']);
	header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<div class="sub-menu-title"><h1>Import Prepaid Transactions</h1></div>
<div id="parameter-submit-result-error-box" class="warning insert-success submit-result <?php echo 'submit-result-', $submit_result, ' ', $error_class; ?>"><?php echo $errmsg; ?></div>
<form method="post" class="hover-cursor-pointer" enctype="multipart/form-data">
	<div id="building-selection" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>File Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li style="width:130px; "><label>CSV File:</label></li>
				<li style="height: 37px; width: 270px; padding-top:0px"><input name="csv" type="file" id="csv" /></li>
			</ul>

			<div class="selection-form-submit float-left">
				<input id="building-selection-view" type="submit" value="Import" name="Import" /> 				
			</div>
			<div id="building-selection-error-box"
				class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div>
</form>
	<!-- end of building selection -->
<?php 
if ($Session->check('Success')) {
	echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
	$Session->sessionUnset('Success');
} 	/*
	if (isset($_SESSION['A'] )) {
	var_dump($_SESSION['A']); 
	var_dump($_SESSION['B']);
	unset($_SESSION['A']); 
	unset($_SESSION['B']);
	}*/
	if ($Session->check('Fail')) {
	echo '<div class="warning warning-box">' . $Session->read('Fail') . '</div>';
	$Session->sessionUnset('Fail');
}
?>
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
			<table class="billing scrollable planning-table planning-table-striped planning-table-hover">
				<thead>
					<tr>
						<th>Account</th>
						<th>Reference</th>
						<th>Meter Serial No.</th>
						<th>Receipt No.</th>
						<th>Transaction Date</th>
						<th>Total Amount</th>
						<th>Tariff Charge</th>			
						<th>Tax Amount</th>
						<th>Units KWH</th>
						<th>Arrears Amount</th>
						<th>Fixed Charge</th>
						<th>Transaction Levy</th>		
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($file)) { 
						while(! feof($file)) {
							$row = fgetcsv($file, 1000, ",");
							$account = str_replace(chr(0), '',$row [0]);
							$account= preg_replace("/[^a-zA-Z0-9]/", "", $account);
							$reference = str_replace(chr(0), '',$row [1]);
							$meter_serial_no = str_replace(chr(0), '',$row [2]);
							$receipt_no = str_replace(chr(0), '',$row [3]);
							$transaction_date = str_replace(chr(0), '',$row [4]);
							$total_amount = str_replace(chr(0), '',$row [5]);
							$tariff_charge = str_replace(chr(0), '',$row [6]);
							$tax_amount = str_replace(chr(0), '',$row [7]);
							$units_kwh = str_replace(chr(0), '',$row [8]);
							$arrears_amount = str_replace(chr(0), '',$row [9]);
							$fixed_charge = str_replace(chr(0), '',$row [10]);
							$transaction_levy = str_replace(chr(0), '',$row [11]); ?>
							<tr>
								<td><?php echo $account; ?></td>
								<td><?php echo $reference; ?></td>
								<td><?php echo $meter_serial_no; ?></td>
								<td><?php echo $receipt_no; ?></td>
								<td><?php echo $transaction_date; ?></td>
								<td><?php echo $total_amount; ?></td>
								<td><?php echo $tariff_charge; ?></td>
								<td><?php echo $tax_amount; ?></td>
								<td><?php echo $units_kwh; ?></td>
								<td><?php echo $arrears_amount ; ?></td>
								<td><?php echo $fixed_charge; ?></td>
								<td><?php echo $transaction_levy; ?></td>
							</tr>
					<?php  } } ?>				
				</tbody>
			</table>
					
		</div>
	</div>
	<div class="wrapper-fieldset-forms <?php echo $view_class; ?>">
		<?php if(!empty($file)) { ?>
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
