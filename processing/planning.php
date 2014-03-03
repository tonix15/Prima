<?php
$page_name = 'PLANNING';

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

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

// Building
$Building = new Building($dbh);

// Team
$Team = new Team($dbh);
$selected_year = date('Y');
$selected_month = date('m');
$selected_date = $selected_year . '-' . $selected_month . '-01';
$building_termination_period_list = $dbhandler->getBuildingTerminationPeriod(array($userPK, 0));
$team_list = $Team->getTeam(array($userPK, 0));

if (isset($_REQUEST['Update'])) {
	$timestamp = strtotime(date(Prima::FORMAT_SAVE_DATE)); 
	$selected_year = $_POST['billing_period_year'];
	$selected_month = $_POST['billing_period_month'];
	$billing_period = $selected_year . '-' . $selected_month . '-01';
	$building_billing_period = array('period_PK', 'building_PK', 'team_PK', 'reading_day', 'sequence');
	$len = count($building_billing_period);

	// Billing
	for ($i = 0; $i < $len; $i++) {
		${'billing_' . $building_billing_period[$i]} = !empty($_REQUEST['billing_' . $building_billing_period[$i]]) ? $_REQUEST['billing_' . 		$building_billing_period[$i]] : null;
	}	
	
	// Termination
	for ($i = 0; $i < $len; $i++) {
		${'termination_' . $building_billing_period[$i]} = !empty($_REQUEST['termination_' . $building_billing_period[$i]]) ? $_REQUEST['termination_' . $building_billing_period[$i]] : null;
	}
	
	$len = count($billing_building_PK);
	
	for ($i = 0; $i < $len; $i++) { // Billing
			$dbhandler->updateBuildingBillingPeriod(array(
			$userPK,
			$billing_period_PK[$i], // update building billing period
			$billing_building_PK[$i],
			$billing_team_PK[$i] > 0 ? $billing_team_PK[$i] : -1,
			$billing_reading_day[$i],
			$billing_period,
			$billing_sequence[$i]
		));
	}
	
	$len = count($building_termination_period_list);
	$x = array();
	if ($len === count($termination_period_PK)) {
		for ($i = 0; $i < $len; $i++) { // Termination
			if ($building_termination_period_list[$i]['BuildingTerminationPeriodPk'] == $termination_period_PK[$i]) {
				if ($dbhandler->updateBuildingTerminationPeriod(array(
					$userPK,
					$building_termination_period_list[$i]['BuildingTerminationPeriodPk'],
					$building_termination_period_list[$i]['BuildingFk'],
					$building_termination_period_list[$i]['UnitFk'],
					$termination_team_PK[$i] > 0 ? $termination_team_PK[$i] : -1,
					$termination_reading_day[$i],
					$building_termination_period_list[$i]['BillingPeriod'],
					$termination_sequence[$i]
				))) {
					$x[$i] = $i.' - Updated';
				} else {
					$x[$i] = $i.' - Not Updated';
				}
			} 
		} 
	} else {
		$Session->write('Fail', 'Building Termination Period did not update successfully.');
	}

	if (!$Session->read('Fail')) {
		$Session->write('Success', '<strong>Planning and Termination</strong> updated successfully.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF'] . '?billing_period_month=' . $selected_month . '&billing_period_year=' . $selected_year . '&View=View');
		exit; 
	} 
}

if(isset($_REQUEST['View'])){
	$selected_month = $_REQUEST['billing_period_month'];
	$selected_year = $_REQUEST['billing_period_year'];
	$selected_date = $selected_year . '-' . $selected_month . '-01';
}

//$last_date = ($selected_month - 1 == 0 ? $selected_year - 1 : $selected_year) . '-' . ($selected_month - 1 == 0 ? '12' : sprintf("%02d",$selected_month - 1)) . '-01';
//$last_building_billing_period_list = $dbhandler->getBuildingBillingPeriod(array($userPK, 0, $last_date));
$building_billing_period_list = $dbhandler->getBuildingBillingPeriod(array($userPK, 0, $selected_date));
// if (isset($_SESSION['xx']) )
	// var_dump($_SESSION['xx']);
?>
<form id="form-planning" action="" method="post" name="">
<div class="sub-menu-title"><h1>Planning</h1></div>
<?php 
	if ($Session->check('Success')) { 
		echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
		$Session->sessionUnset('Success');
	} else if ($Session->check('Fail')) {
		echo '<div class="warning warning-box">' . $Session->read('Fail') . '</div>';
		$Session->sessionUnset('Fail');
	}
	
	$month_names = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$current_year = date('Y');
	$period = $month_names[$selected_month-1].' '.$current_year;
?>
<div id="parameters">
<?php //var_dump($building_termination_period_list); ?>
    <ul id="planning-tab-menu" class="tab-menu">                                                                       
        <li id="planning-planning" class="tab-menu-selected"><a href="#tab-planning">Planning</a></li>
        <li id="planning-terminations" class="tab-menu-item"><a href="#tab-terminations">Terminations</a></li>
        </ul>
    <div class="tab-extra-line" style="width: 681px;"></div>
    <div id="planning-planning-content" class="show tab-content planning-tab-content planning-menu-hover">
	
<div class="planning-data-list-container">
	<div class="billing_period">
		<label><strong style="margin-right:20px;">Current Planning Period:</strong></label>
		<label>
			<span class="_billing_period caret"></span>
			<select id="billing_period_month" name="billing_period_month" class="billing_period_month planning-period-selection" style="width: 135px;">
				<option value="0">Select a month...</option>
				<?php
					$count = 0;
					$month_numeric = 0;								
					foreach($month_names as $months){
						if($count < 9){ $month_numeric = '0' . ++$count; }
						else{ $month_numeric = ++$count; }
						$selected = $selected_month == $month_numeric ? 'selected' : '';
				?>
				<option value="<?php echo $month_numeric; ?>" <?php echo $selected; ?>><?php echo $months; ?></option>
			<?php   }//end of foreach loop ?>
			</select>
		</label>
		
		<label>
			<span class="_billing_period caret"></span>
			<select id="billing_period_year" name="billing_period_year" class="planning_period_year planning-period-selection" style="width: 70px;">
				<option value="0">Select a year...</option>
				<?php 
					for($year = ($current_year - 10); $year <= ($current_year + 10); $year++){ 
						$selected = $year == $selected_year ? 'selected' : '';
				?>
				<option value="<?php echo $year; ?>" <?php echo $selected; ?>><?php echo $year; ?></option>
			<?php   }//end of foreach ?>
			</select>
		</label>
        <input style="margin-left: 10px;" type="submit" name="View" value="View" class="button-billing-period" />
        <!-- <input id="billing-create-button" type="submit" name="Create" value="Create" class="button-billing-period" /> -->
        <div id="billing-selection-error-box" class="selection-error-box error-box hidden" style="float: right; width: 210px; margin-top: -42px; text-align: center;vertical-align: middle;height: 35px;"></div>
		&nbsp;&nbsp;
		<label>
			Export as: 
			<a title="PDF" href="<?php echo DOMAIN_NAME . '/processing/exportAsPdf.php?tab=planning&period='.$period; ?>">PDF</a>
			<a title="SpreadSheet" href="<?php echo DOMAIN_NAME . '/processing/exportAsCSV.php?tab=planning&period='.$period; ?>">SpreadSheet</a>
		</label>
	</div>
	<?php 
		//Buffer the html table with PHP to be stored in variable
		ob_start(); 
	?>
	<table class="planning-table planning-table-striped planning-table-hover">
		<thead>
			<tr>
				<th>Building</th>
				<th>Team</th>
				<th>Reading Day</th>
				<th>Sequence</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($building_billing_period_list)) { 
				$ctr = 0;
			foreach ($building_billing_period_list as $billing_period) { 
				$disabled = 'disabled';
				$disabled_class = 'disabled-input';
				if ($billing_period['ReadingDay'] === '2100-01-01' || strtotime($billing_period['ReadingDay']) >= strtotime(Prima::getSaveDate())) {
					$disabled = '';
					$disabled_class = '';
				} 
				
				//if ($billing_period['TeamFk'] == -1) {
					//$team_fk = $last_building_billing_period_list[$ctr]['TeamFk'];
				//} else {
					$team_fk = $billing_period['TeamFk'];
				//} 
				?>
			<tr>
				<td>
					<?php echo $billing_period['BuildingName']; ?>
					<input type="hidden" name="billing_building_PK[]" value="<?php echo $billing_period['BuildingFk']; ?>">
					<input type="hidden" name="billing_period_PK[]" value="<?php echo $billing_period['BuildingBillingPeriodPk']; ?>">
				</td>
				<td>						
					<select <?php echo $disabled; ?> name="billing_team_PK[]" id="billing_team_<?php echo $ctr; ?>" class="billing_team <?php echo $disabled_class; ?>" style="width: 135px;">
						<option value="0">Please select...</option>
						<?php if (!empty($team_list)) { 
							foreach ($team_list as $team) {

							$selected = $team_fk == $team['TeamPk'] ? 'selected="' . $team['TeamPk'] . '"':''; ?>
							<option <?php echo $selected; ?> value="<?php echo $team['TeamPk']; ?>"><?php echo $team['Value']; ?></option>
						<?php } }?>
					</select>
				</td>
				<td><input <?php echo $disabled; ?> type="date" id="billing_reading_day_<?php echo $ctr; ?>" class="billing_reading_day <?php echo $disabled_class; ?>" name="billing_reading_day[]" value="<?php echo $billing_period['ReadingDay'];?>" ></td>
				<td><input <?php echo $disabled; ?> type="text" id="billing_sequence_<?php echo $ctr; ?>" class="input-integer billing_sequence <?php echo $disabled_class; ?>" name="billing_sequence[]" value="<?php echo $billing_period['Sequence']; ?>" style="margin-top: 5px;width: 55px;"/></td>					
			</tr>
			<?php $ctr++; } } ?>
		</tbody>
	</table>
	<?php if (empty($building_billing_period_list)) { ?>
		<h3>No Records Found!</h3>
	<?php } ?>
</div>
<?php
	//Collect the output buffer into a variable
	$planning_contents = ob_get_contents();
	ob_end_flush();
?>
</div>
<div id="planning-terminations-content" class="hidden tab-content">
	<label>
		Export as: 
		<a title="PDF" href="<?php echo DOMAIN_NAME . '/processing/exportAsPdf.php?tab=termination'; ?>">PDF</a> 
		<a title="SpreadSheet" href="<?php echo DOMAIN_NAME . '/processing/exportAsCSV.php?tab=termination';?>">SpreadSheet</a>
	</label>
	<?php 
		//Buffer the html table with PHP to be stored in variable
		ob_start(); 
	?>
 	<table class="planning-table planning-table-striped planning-table-hover paginate-termination" >
		<thead>
			<tr>
				<th>Building</th>
				<th>Unit</th>
				<th>Team</th>
				<th>Reading Day</th>
				<th>Sequence</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($building_termination_period_list)) { 
				$ctr = 0;
			foreach ($building_termination_period_list as $termination_period) { ?>
			<tr>
				<td>
					<?php echo $termination_period['BuildingName']; ?>
					<input type="hidden" name="termination_period_PK[]" value="<?php echo $termination_period['BuildingTerminationPeriodPk']; ?>">
				</td>
				<td><?php echo $termination_period['UnitNumberBk']; ?></td>					
				<td>						
					<select name="termination_team_PK[]" id="termination_team_<?php echo $ctr; ?>" class="termination_team">
						<option value="0">Please select...</option>
						<?php if (!empty($team_list)) { 
							foreach ($team_list as $team) { 
							$selected = $termination_period['TeamFk'] == $team['TeamPk'] ? 'selected="' . $team['TeamPk'] . '"':''; ?>
							<option <?php echo $selected; ?> value="<?php echo $team['TeamPk']; ?>"><?php echo $team['Value']; ?></option>
						<?php } }?>
					</select>
				</td>
				<td><input type="date" id="termination_reading_day_<?php echo $ctr; ?>" class="termination_reading_day" name="termination_reading_day[]" value="<?php echo $termination_period['ReadingDay'];?>"></td>
				<td><input type="text" class="input-integer billing_sequence" name="termination_sequence[]" value="<?php echo $termination_period['Sequence']; ?>" style="margin-top: 5px;width: 55px;"/></td>
			</tr>
			<?php $ctr++; } } ?>
		</tbody>
	</table>
	<?php if (empty($building_termination_period_list)) { ?>
		<h3>No Records Found!</h3>
	<?php } ?>
</div>
<?php
	//Collect the output buffer into a variable
	$termination_contents = ob_get_contents();
	ob_end_flush();
?>
<div class="wrapper-fieldset-forms">
		<?php if($restriction_level > 0){ ?>
		<div id="parameter-submit-error-box" class="submit-error-box warning-box warning hidden"></div>
		<div class="form-submit" style="margin-left:25%;">
			<input type="submit" id="planning-save-button" value="Update" class="submit-positive" name="Update"/>
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel"/>
		</div>
		<?php } ?>
	</div>    
 </div>   
</form> 

<?php	
	$Session->write('planning_content', $planning_contents);
	$Session->write('termination_content', $termination_contents);
	$Session->write('planning_excel', $building_billing_period_list);
	$Session->write('termination_excel', $building_termination_period_list);
	$Session->write('teamlist_export', $team_list);
	//var_dump($team_list);
?>
	
<?php
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>
