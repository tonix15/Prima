<?php
$page_name = 'Manual Reading Adjust';
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

$view_class = 'hidden';

$Building = new Building($dbh);
$building_list = $Building->getBuilding(array($userPK, 0));
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

$building_names = NULL;

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);


$Reading = new Meter($dbh);
$meter_list = $Reading->getMeter(array($userPK, 0, $buildingPK, 0));
$meterPK = !empty($_GET['choose_meter']) ? (int) $_GET['choose_meter'] : 0;
$meter_list = null;
$rmeter_list = null;


//Utility Type
$utility_type = new UtilityType($dbh);
$utility_type_list = null;
$utilityPk = 0;

//Unit
$unit = new Unit($dbh);
$unit_list = null;
$unitPK = !empty($_GET['choose_unit']) ? (int) $_GET['choose_unit'] : 0;

$message = NULL;
if(isset($_GET['View'])){
	//UI
	$view_class = 'show';
	
	$buildingFK = $_GET['choose_building'];
	$building_names = $Building->getBuilding(array($userPK, $buildingFK));
	$building_names = $Building->getSingleRecord($building_names);
		
	if (!empty($buildingFK)) { 
		$unit_list = $unit->getUnit(array($userPK, 0, $buildingFK)); 
		$meter_list = $Reading->getMeter(array($userPK, 0, $buildingFK, $unitPK));
	}	
	
	$meterPK = $_GET['choose_meter'];
	$reading_list = $dbhandler->getReadingAdjust(array($userPK, 0, $buildingFK, $meterPK));
	$message = !empty($reading_list) ? '':'<h2>No Entries Found.</h2>';
}
require DOCROOT . '/template/header.php';
?>

<form method="get" class="hover-cursor-pointer">
	<div class="sub-menu-title"><h1>Manual Reading Adjust</h1></div>
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
							$selected = $buildingPK == $building['BuildingPk'] ? 'selected="' . $building['BuildingPk'] . '"':''; 
						?>
							<option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
						<?php } } ?>
					</select>
				</li>
				<li>Unit:</li>
				<li>
					<select id="meter-selection-unit" class="selection-required-input" name="choose_unit">
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
				<li>Meter:</li>
				<li>
					<select id="meter-selection-meter" class="selection-required-input" name="choose_meter">
						<option value="0">Please choose a meter</option>
						<?php 
						if (!empty($meter_list)) { 
						foreach($meter_list as $meter) { 
							$selected = $meterPK  == $meter['MeterPk'] ? 'selected="' . $meter['MeterPk'] . '"':''; ?>
							<option <?php echo $selected; ?> value="<?php echo $meter['MeterPk']; ?>"><?php echo $meter['NumberBk']; ?></option>
						<?php } } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="meter-selection-view" type="submit" value="View" name="View"/>
				<?php if($restriction_level > 0){?>
					<input id="meter-selection-create" type="submit" value="Create" name="Create"/>   
				<?php }?>
			</div> 
			<div id="meter-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div> <!-- end of building selection -->
</form> <!-- end of get form -->

<?php if(!empty($reading_list)): ?>
<h2><?php echo $building_names['Name']; ?></h2>
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
			<table class="billing scrollable planning-table planning-table-striped planning-table-hover" style="width: 50%;">
				<thead>
					<tr>
						<th>Reading Date</th>
						<th>Reading Amount</th>
						<th>Is Billed</th>
						<th>Geo Location</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($reading_list as $report): ?>
						<tr>
							<?php $temp = explode(' ', $report['ReadingDate']); ?>
							<td class="table-column-text-align-center"><?php echo $temp[0]; ?></td>								
							<td class="table-column-text-align-right"><?php echo number_format($report['ReadingAmount'], 0, '', ','); ?></td>
							<td class="table-column-text-align-center"><?php echo $report['IsBilled']; ?></td>
							<td class="table-column-text-align-right"><?php echo $report['GeoLocation']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Manual Reading Adjust';
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/convert_pdf_spreadsheet.php'
			?>			
		</div>
	</div>
<?php else: echo $message; ?>
<?php endif; ?>
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>