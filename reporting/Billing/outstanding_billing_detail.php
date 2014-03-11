<?php
$page_name = 'Outstanding Billing Detail';

require_once '../../init.php';

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

$userCredentials = $User->getUserCredentials();
$userPK = $userCredentials['UserPk'];

require DOCROOT . '/template/header.php';

$Building = $dbhandler->getBuilding(array($userPK, 0));
$buildingPK = 0;

$selectedDate = date('Y-m-d');

$data_list = NULL;
//UI 
$message = NULL;
if(isset($_GET['View'])){
	$buildingPK = $_GET['choose_building'];
	$selectedDate = $_GET['choose_date'];
	$params = array(
		$userPK,
		$Session->read('user_company_selection_key'),
		$selectedDate,
		$buildingPK
	);
	$data_list = $dbhandler->repOutstandingBillingDetail($params);
	$message = !empty($data_list) ? '':'<h2>No Entries Found.</h2>' ;
}

?>

<div class="sub-menu-title">
	<h1>Outstanding Billing List Report</h1>
	<div id="rate-selection" class="wrapper-fieldset-forms hover-cursor-pointer">
		<form method="get">
			<fieldset class="fieldset-forms">
				<legend>Outstanding Billing Detail Selection</legend>
				<ul class="fieldset-forms-li-2-cols">
					<li><label>Building:</label></li>
					<li>
						<select id="rate-selection-provider" name="choose_building" class="rate-selection-input">
							<option value="0">Please select...</option>
						<?php foreach ($Building as $building) { 
							$selected = $building['BuildingPk'] === $buildingPK ? 'selected':'';
						?>
							<option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
						<?php } ?>
						</select>
					</li>				
					<li>Reading Date:</li>
					<li><input type="date" id="rate-selection-date" name="choose_date" class="selection-required-input" value="<?php echo $selectedDate; ?>"/></li>	
				</ul>
				<div class="selection-form-submit float-left">
					<input id="rate-selection-view" type="submit" value="View" name="View"/>				
				</div> 
				<div id="rate-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
			</fieldset>
		</form>
	</div> <!-- end of rate selection -->
</div>
<?php if(!empty($data_list)): ?>
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
						<th>Building</th>
						<th>Unit Number</th>
						<th>Meter Number</th>
						<th>Utility Type</th>
						<th>Reading Day</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data_list as $data): ?>
							<tr>
								<td><?php echo $data['BuildingName'] ?></td>
								<td><?php echo $data['UnitNo'] ?></td>
								<td><?php echo $data['MeterNo'] ?></td>
								<td><?php echo $data['UtilityType'] ?></td>
								<td><?php echo $data['readingday'] ?></td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Outstanding Billing Detail Report';
				$Session->write('title', $title);
				$Session->write('content', $html);
								
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/query_and_reporting_pdf.php'
			?>			
		</div>
	</div>
<?php 
	else: echo $message;
	endif; 
?>	
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>
