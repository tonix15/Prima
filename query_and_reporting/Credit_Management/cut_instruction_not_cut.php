<?php
$page_name = 'Cut Instruction not Cut';

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

$building_list = $dbhandler->getBuilding(array($userPK, 0));
$BuildingPK = 0;

$data_list = NULL;
//UI
$message = NULL;
if(isset($_GET['View'])) {
	$BuildingPK = $_GET['choose_building'];
	
	$params = array(
		$userPK,		
		$BuildingPK,
		$Session->read('user_company_selection_key')
	);	
	
	$data_list = $dbhandler->repCutInstructionNotCut($params);
	$message = !empty($data_list) ? '':'<h2>No Entries Found.</h2>';
}
?>

<div class="sub-menu-title"><h1>Cut Instruction not Cut Report</h1></div>
<form method="get">
	<div id="rate-selection" class="wrapper-fieldset-forms hover-cursor-pointer">
		<fieldset class="fieldset-forms">
			<legend>Property Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Building:</label></li>
				<li>
					<select id="rate-selection-provider" name="choose_building" class="rate-selection-input">
						<option value="0">Please select...</option>
					<?php foreach ($building_list as $building) { 
						$selected = $building['BuildingPk'] == $BuildingPK ? 'selected':'';
					?>
						<option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
					<?php } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input type="submit" value="View" name="View" />
			</div> 
			<div id="common-property-calculation-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div> <!-- end of rate selection -->
</form> <!-- end of get form -->

<?php if(!empty($data_list)): ?>
	<div class="table-wrapper planning-data-list-container">
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
			<table class="cut-instruction-not-notified planning-table planning-table-striped planning-table-hover reading-table">
				<thead>
					<tr>						
						<th>Surname</th>
						<th>Cellphone</th>
						<th>Email</th>
						<th>Account Number</th>				
						<th>Outstanding Amount</th>
						<th>Transaction Date</th>
						<th>SMS Date</th>
						<th>Email Date</th>
						<th>Effective Date</th>
					</tr>
				</thead>
				<tbody>	
					<?php foreach($data_list as $list): ?>
							<tr>
								<td><?php echo $list['Surname']; ?></td>
								<td class="table-column-text-align-center"><?php echo $list['Cellphone']; ?></td>
								<td><?php echo $list['Email']; ?></td>
								<td class="table-column-text-align-right"><?php echo $list['AccountNumber']; ?></td>
								<td class="table-column-text-align-right"><?php echo $list['OutstandingAmount']; ?></td>
								<td class="table-column-text-align-center"><?php echo $list['TransactionDate']; ?></td>
								<td class="table-column-text-align-center"><?php echo $list['SMSDate']; ?></td>
								<td class="table-column-text-align-center"><?php echo round($list['EmailDate'], 0); ?></td>
								<td class="table-column-text-align-center"><?php echo $list['EffDate']; ?></td>
							</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Cut Instruction Not Cut Report';
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require DOCROOT . '/widgets/query_and_reporting_pdf.php';
			?>			
		</div>
	</div>
<?php 
else: echo $message;
endif;
?>

<?php require DOCROOT . '/template/footer.php';?>
<script src="<?php echo DOMAIN_NAME; ?>/js/tab-script.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.cut-instruction-not-notified', 10); });</script>
<script type="text/javascript">
	$(function(){
		$('input[name="View"]').click(function(){
			var building = $('select[name="choose_building"]').val().trim();
			if(building <= 0){
				$('#common-property-calculation-selection-error-box')
					.html("Please select a Building.")
					.removeClass('hidden')
					.addClass('show');
				return false;
			}
			else{ 
				$('#common-property-calculation-selection-error-box')
					.html("")
					.removeClass('show')
					.addClass('hidden');
				return true; 
			}
		});
	});
</script>