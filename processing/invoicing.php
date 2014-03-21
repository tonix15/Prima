<?php
ob_start();
$page_name = 'Invoicing';

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

require DOCROOT . '/template/header.php';

//Building
$Building = new Building($dbh);
$building_list = $Building->getBuilding(array($userPK, 0));

$buildingPK = 0;
$buildingFK = 0;
$building_names = NULL;

$building_billing_list = NULL;
$building_billing_date = NULL;

$billing_table_columns = array();

//UI
$view_class = 'hidden';
$current_month = date('m');
$current_day = date('d');
$current_year = date('Y');
$result_message = '';

if(isset($_GET['View'])){
	//UI
	$view_class = 'show';
	
	$current_month = $_GET['billing_period_month'];
	$current_day = isset($_GET['billing_period_day']) ? $_GET['billing_period_day']:'-01';
	$current_year = $_GET['billing_period_year'];

	if(isset($_GET['choose_building'])){
		$buildingPK = $buildingFK = $_GET['choose_building'];
		$building_names = $Building->getBuilding(array($userPK, $buildingFK ));
		$building_names = $Building->getSingleRecord($building_names);		
	}
	
	$building_billing_date = $current_year . '-' . $current_month . $current_day;
	$building_billing_list = $dbhandler->getBuildingBilling(array($userPK, $buildingFK, $building_billing_date));
	
	if(!empty($building_billing_list)){ 
		//UI
		$result_message = ''; 
		
		//get Column names from Result Set
		$key_names = $Building->getSingleRecord($building_billing_list);
		foreach(array_keys($key_names) as $key){ $billing_table_columns[] = $key; }
	}
	else{ $result_message = '<h3>No Records Found!</h3>'; }	
}

if(isset($_POST['Confirm-Invoice'])){
	$buildingPK = $_GET['choose_building'];
	$billing_period = $_GET['billing_period_year'] . '-' . $_GET['billing_period_month'] . '-01';
	
	$invoice_params = array(
		$userPK,
		$_GET['choose_building'],
		$billing_period
	);
	$invoiceStatus = $Building->setBuildingBilling($invoice_params);
	if(!empty($invoiceStatus)){
		$Session->write('Success', '<strong>Invoice</strong> added successfully.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF']);
		exit();
	}
}
?>


<form method="get" class="hover-cursor-pointer">
	<?php
		if($Session->check('Success')){ 
			echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
			$Session->sessionUnset('Success');
		}
	?>
		
	<div class="sub-menu-title">
		<h1>Invoicing</h1>
	</div>

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
			
			<ul class="fieldset-forms-li-2-cols">
				<?php $month_names = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>
				<li><label>Date:</label></li>
				<li>
					<!-- Month -->
					<select name="billing_period_month" style="width:95px;">
					<option value="0">Select a month...</option>
					<?php
						$count = 0;
						$month_numeric = 0;								
						foreach($month_names as $months){
							if($count < 9){ $month_numeric = '0' . ++$count; }
							else{ $month_numeric = ++$count; }
							$selected = $current_month == $month_numeric ? 'selected' : '';
					?>
					<option value="<?php echo $month_numeric; ?>" <?php echo $selected; ?>><?php echo $months; ?></option>
					<?php  }//end of foreach loop ?>
					</select><!-- end of Month -->
					
					<!-- Year -->
					<select name="billing_period_year" style="width:95px;">
					<option value="0">Select a year...</option>
					<?php 
						for($year = ($current_year - 10); $year <= ($current_year + 10); $year++){ 
							$selected = $year == $current_year ? 'selected' : '';
					?>
					<option value="<?php echo $year; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
					<?php   }//end of foreach ?>
					</select>
					<!-- end of Year -->
				</li>
			</ul>
			
			<div class="selection-form-submit float-left">
				<input id="building-selection-view" type="submit" value="View" name="View" /> 				
			</div>
			<div id="building-selection-error-box"
				class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div>
	<!-- end of building selection -->
</form>

<!-- Building Name -->
<?php
echo !empty($building_names['Name']) ? '<h2>' . $building_names['Name'] . '</h2>' : NULL;

if(!empty($building_billing_list)){						
	if ($view_class === 'show' ) {
?>
<div class="table-wrapper billing-data-list-container">
	<form method="post">		
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
						<?php 
							for($i = 0; $i < count($billing_table_columns); $i++){
								echo '<th>' . ucwords(strtolower($billing_table_columns[$i])) . '</th>';
							}
						?>					
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($building_billing_list as $billing_list) {
							echo '<tr>';
							for($i = 0; $i < count($billing_table_columns); $i++){								
								echo '<td class="column-text-aligned-right">' . $billing_list[$billing_table_columns[$i]] . '</td>';								
							}
							echo '</tr>';
						}
					?>										
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();	
				
				$title = 'Invoicing for  ' . ucwords(strtolower($building_names['Name'])) . ' ' . $building_billing_date;
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/query_and_reporting_pdf.php'
			?>			
			
			<div class="wrapper-fieldset-forms">
				<div style="margin-left:40%;" class="form-submit">
					<input type="button" name="Invoice" class="submit-positive" value="Invoice" >
				</div>
				
				<!-- Modal Confirm -->	
				<div class="overlay"></div>
				<div class="modal-container">
					<div class="modal-header clearfix">
						<div class="title"><label>Invoice Confirmation<label></div>
						<div class="close"><a href="#"></a></div>
					</div>
					<div class="modal-body">
						<div class="message">
							<label>Are you sure you want to submit an Invoice?</label>
						</div>
						<div class="actions">
							<input type="submit" name="Confirm-Invoice" value="Yes" />
							<input type="button" name="Cancel-Invoice" value="No" />
						</div>
					</div>
				</div>
			</div>
		</div>		
	</form>		
</div>	

<?php
	}//end if statement
}//end if statement
else{ echo $result_message; }
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">
	$(function(){ TABLE.paginate('.billing', 10); });
</script>
