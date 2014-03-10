<?php
$page_name = 'Common Property Calculation';

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

$selected_date = date('Y-m-d');

$no_previous_reading_list = NULL;
$negative_consumption_list = NULL;
$common_property_calculation_list = NULL;
$common_property_allocation_list = NULL;


//UI
$message = NULL;
$show = FALSE;

if (isset($_GET['View'])) {
	$BuildingPK = $_GET['choose_building'];
	$selected_date = $_GET['choose_date'];
	
	$params = array(
		$userPK,
		$Session->read('user_company_selection_key'),
		$BuildingPK,
		$selected_date
	);
	$no_previous_reading_list = $dbhandler->repCommonPropertyNoPreviousReading($params);
	$negative_consumption_list = $dbhandler->repCommonPropertyNegativeConsumption($params);
	$common_property_calculation_list = $dbhandler->repCommonPropertyUnderRecoveryDetail($params);
	$common_property_allocation_list = $dbhandler->repCommonPropertyAllocation($params);
	
	$message = !empty($no_previous_reading_list) || 
			   !empty($negative_consumption_list) || 
			   !empty($common_property_calculation_list) || 
			   !empty($common_property_allocation_list) ? '':'<h2>No Entries Found.</h2>';
			   
	$show = !empty($no_previous_reading_list) || 
			!empty($negative_consumption_list) || 
			!empty($common_property_calculation_list) || 
			!empty($common_property_allocation_list) ? TRUE:FALSE;
}
?>

<div class="sub-menu-title"><h1>Common Property Calculation Report</h1></div>
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
            <li>Date:</li>
            <li><input type="date" id="rate-selection-date" name="choose_date" class="selection-required-input" value="<?php echo $selected_date; ?>"/></li>	
        </ul>
        <div class="selection-form-submit float-left">
            <input id="rate-selection-view" type="submit" value="View" name="View"/>
        </div> 
        <div id="common-property-calculation-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
    </fieldset>
</div> <!-- end of rate selection -->
</form> <!-- end of get form -->
<?php if(empty($message) && $show):?>
	<div class="tab-container">
		<ul>
			<li class="tabs"><a href="#no-previous-reading" class="current-tab">No Previous Reading</a></li>
			<li class="tabs"><a href="#negative-consumption">Negative Consumption</a></li>
			<li class="tabs"><a href="#calculation">Common Property Calculation</a></li>
			<li class="tabs"><a href="#allocation">Common Property Allocation</a></li>
		</ul>
	</div>  
	<div class="tab-contents-container">  
		<div id="no-previous-reading" class="tab-contents">
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
					<table class="no-previous-reading planning-table planning-table-striped planning-table-hover reading-table">
						<thead>
							<tr>						
								<th>Building</th>
								<th>Unit</th>
								<th>Utility Type</th>
								<th>Meter Type</th>				
								<th>Meter</th>
								<th>Commission Date</th>
								<th>Decommission Date</th>
								<th>Start Reading</th>
								<th>Current Reading</th>
								<th>Previous Read</th>
								<th>Reading Current</th>
								<th>Reading Previous</th>
								<th>Consumption</th>
							</tr>
						</thead>
						<tbody>	
							<?php
								if(!empty($no_previous_reading_list)):
									foreach($no_previous_reading_list as $list):
							?>
										<tr>
											<td><?php echo $list['Building']; ?></td>
											<td><?php echo $list['Unit']; ?></td>
											<td><?php echo $list['UtilityType']; ?></td>
											<td><?php echo $list['MeterType']; ?></td>
											<td class="table-column-text-align-right"><?php echo $list['Meter']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['CommissionDate']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['DecommissionDate']; ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['StartReading'], 0); ?></td>
											<td class="table-column-text-align-center"><?php echo $list['CurrentRead']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['PreviousRead']; ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ReadingCurrent'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ReadingPrevious'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['Consumption'], 0); ?></td>
										</tr>
							<?php 
									endforeach;
								else: echo '<tr><td colspan="13"><strong>No Entries Found.</strong></td></tr>';
								endif;
							?>
						</tbody>
					</table>
					<?php
						//Collect the output buffer into a variable
						$html = ob_get_contents();
						ob_end_flush();
						
						$title = 'No Previous Reading Report';
						$Session->write('title', $title);
						$Session->write('content', $html);
						
						unset($title);
						unset($html);
						
						require DOCROOT . '/widgets/query_and_reporting_pdf.php';
					?>			
				</div>
			</div>
		</div>
		<div id="negative-consumption" class="tab-contents">
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
					<table class="negative-consumption planning-table planning-table-striped planning-table-hover reading-table">
						<thead>
							<tr>						
								<th>Building</th>
								<th>Unit</th>
								<th>Utility Type</th>
								<th>Meter Type</th>				
								<th>Meter</th>
								<th>Commission Date</th>
								<th>Decommission Date</th>
								<th>Start Reading</th>
								<th>Current Reading</th>
								<th>Previous Read</th>
								<th>Reading Current</th>
								<th>Reading Previous</th>
								<th>Consumption</th>
							</tr>
						</thead>
						<tbody>	
							<?php
								if(!empty($negative_consumption_list)):
									foreach($negative_consumption_list as $list):
							?>
										<tr>
											<td><?php echo $list['Building']; ?></td>
											<td><?php echo $list['Unit']; ?></td>
											<td><?php echo $list['UtilityType']; ?></td>
											<td><?php echo $list['MeterType']; ?></td>
											<td class="table-column-text-align-right"><?php echo $list['Meter']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['CommissionDate']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['DecommissionDate']; ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['StartReading'], 0); ?></td>
											<td class="table-column-text-align-center"><?php echo $list['CurrentRead']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['PreviousRead']; ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ReadingCurrent'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ReadingPrevious'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['Consumption'], 0); ?></td>
										</tr>
							<?php 
									endforeach;
								else: echo '<tr><td colspan="13"><strong>No Entries Found.</strong></td></tr>';
								endif;
							?>
						</tbody>
					</table>
					<?php
						//Collect the output buffer into a variable
						$html = ob_get_contents();
						ob_end_flush();
						
						$title = 'Negative Consumption Report';
						$Session->write('title', $title);
						$Session->write('content', $html);
						
						unset($title);
						unset($html);
						
						require DOCROOT . '/widgets/query_and_reporting_pdf.php';
					?>			
				</div>
			</div>
		</div>
		<div id="calculation" class="tab-contents">
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
					<table class="calculation planning-table planning-table-striped planning-table-hover reading-table">
						<thead>
							<tr>						
								<th>Building</th>
								<th>Unit</th>
								<th>Meter Type</th>				
								<th>Electricity Consumption</th>
								<th>Water Consumption</th>
							</tr>
						</thead>
						<tbody>	
							<?php
								if(!empty($common_property_calculation_list)):
									foreach($common_property_calculation_list as $list):
							?>
										<tr>
											<td><?php echo $list['Building']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['unit']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['MeterType']; ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ElecConsumption'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['WaterConsumption'], 0); ?></td>
										</tr>
							<?php 
									endforeach;
								else: echo '<tr><td colspan="5"><strong>No Entries Found.</strong></td></tr>';
								endif;
							?>
						</tbody>
					</table>
					<?php
						//Collect the output buffer into a variable
						$html = ob_get_contents();
						ob_end_flush();
						
						$title = 'Common Property Calculation Report';
						$Session->write('title', $title);
						$Session->write('content', $html);
						
						unset($title);
						unset($html);
						
						require DOCROOT . '/widgets/query_and_reporting_pdf.php';
					?>			
				</div>
			</div>
		</div>
		<div id="allocation" class="tab-contents">
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
					<table class="allocation planning-table planning-table-striped planning-table-hover reading-table">
						<thead>
							<tr>						
								<th>Building</th>
								<th>Unit</th>
								<th>Current Read</th>				
								<th>Previous Read</th>
								<th>Allocation Type</th>
								<th>Meter Type</th>
								<th>Electricity Consumption</th>
								<th>Electricity Allocation</th>
								<th>Water Consumption</th>
								<th>Water Allocation</th>
							</tr>
						</thead>
						<tbody>	
							<?php
								if(!empty($common_property_allocation_list)):
									foreach($common_property_allocation_list as $list):
							?>
										<tr>
											<td><?php echo $list['Building']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['Unit']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['CurrentRead']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['PreviousRead']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['AllocationType']; ?></td>
											<td class="table-column-text-align-center"><?php echo $list['MeterType']; ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ElecConsumption'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['ElecAllocation'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['WaterConsumption'], 0); ?></td>
											<td class="table-column-text-align-right"><?php echo round($list['WaterAllocation'], 0); ?></td>
										</tr>
							<?php 
									endforeach;
								else: echo '<tr><td colspan="10"><strong>No Entries Found.</strong></td></tr>';
								endif;
							?>
						</tbody>
					</table>
					<?php
						//Collect the output buffer into a variable
						$html = ob_get_contents();
						ob_end_flush();
						
						$title = 'Common Property Allocation Report';
						$Session->write('title', $title);
						$Session->write('content', $html);
						
						unset($title);
						unset($html);
						
						require DOCROOT . '/widgets/query_and_reporting_pdf.php';
					?>			
				</div>
			</div>
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
<script type="text/javascript">$(function(){ TABLE.paginate('.no-previous-reading', 10); });</script>
<script type="text/javascript">$(function(){ TABLE.paginate('.negative-consumption', 10); });</script>
<script type="text/javascript">$(function(){ TABLE.paginate('.calculation', 10); });</script>
<script type="text/javascript">$(function(){ TABLE.paginate('.allocation', 10); });</script>
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
		});
	});
</script>