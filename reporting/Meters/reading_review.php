<?php
$page_name = 'Reading Review';
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
	$reading_list = $Reading->getReading(array($userPK, 0, $buildingFK, $meterPK));	
}

$import_fancy_box = true;
require DOCROOT . '/template/header.php';
?>

<form method="get" class="hover-cursor-pointer">
	<div class="sub-menu-title"><h1>Reading Review</h1></div>
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

<?php
	echo !empty($building_names['Name']) ? '<strong style="font-size:24px;">' . $building_names['Name'] . '</strong> ' : NULL;
	echo '<br /><br />';

	if ($view_class === 'show' ) {
?>    
<div class="tab-container">
	<ul>
		<li class="tabs"><a href="#reading" class="current-tab">Reading</a></li>
		<li class="tabs"><a href="#consumption-graph">Consumption Graph</a></li>
	</ul>
</div>  
<div class="tab-contents-container">  
	<div id="reading" class="tab-contents">
		<div class="planning-data-list-container">
			<?php 
				//Buffer the html table with PHP to be stored in variable
				ob_start(); 
			?>
			<table class="planning-table planning-table-striped planning-table-hover reading-table">
				<thead>
					<tr>
						<th></th>
						<th>Date</th>
						<th>Reading Amount</th>
						<th>Consumption</th>
						<th>Geolocation</th>				
					</tr>
				</thead>
				<tbody>	
					<?php if (!empty($reading_list)) { 
							foreach ($reading_list as $reading) {  
								$image_title = Prima::formatToSaveDate($reading['ReadingDate']);
								$image_file = Prima::getMeterPhoto($reading['MeterFk'], $image_title);
						
								$geolocation = $reading['GeoLocation'];
								if (!empty($geolocation)) {
									$geo = explode(',', $geolocation);
									$geoCount = count($geo);
							
									if ($geoCount == 2) {
										$lat = $geo[0];
										$long = $geo[1];
									}
							
								}           
					?>
								<tr>
									<td><a href="<?php echo $image_file; ?>" class="fancybox" title="<?php echo $image_title; ?>"><img style="width:40px; height:40px;" src="<?php echo $image_file; ?>"></a></td>
									<td class="table-column-text-align-center"><?php echo $reading['ReadingDate'];?></td>
									<td class="table-column-text-align-right"><?php echo number_format($reading['ReadingAmount'], 0, '', ',');?></td>							
									<td class="table-column-text-align-right"><?php echo number_format($reading['Consumption'], 0, '', ',');?></td>
									<td class="table-column-text-align-center"><?php echo !empty($geolocation) && !empty($geoCount) && $geoCount == 2 ? "<a href=\"https://maps.google.com/maps?q=$lat,$long&t=m&z=15\" target=\"_new\" style=\"text-decoration:none;\">View Location</a>" : '';?></td>
								</tr>
					<?php } } ?>	
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Reading Review for Building ' . ucwords(strtolower($building_names['Name'])) . ' as of ' . date('Y-m-d');
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/convert_pdf_spreadsheet.php'
			?>			
		</div>
	</div>
	<div id="consumption-graph" class="tab-contents">
		<div id="chart_div" style="height:500px;width:1100px;margin:0 auto;"></div>
	</div>
</div>
<?php
	}
?>

<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/tab-script.js"></script>
<?php if(!empty($reading_list)): ?>
	<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/excanvas.min.js"></script><![endif]-->	
	<script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/jquery.jqplot.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/plugins/jqplot.barRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/plugins/jqplot.pointLabels.min.js"></script>	
	<script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/plugins/jqplot.highlighter.min.js"></script>	
	<script language="javascript" type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/res/jqplot/plugins/jqplot.cursor.min.js"></script>	
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/res/jqplot/jquery.jqplot.css" />
	<script type="text/javascript">
		$(function(){			
			var date = [];			
			var amount = [];
			var consumption = [];
			var temp;
			var counter = 0;
			//Get all the Reading Dates
			$('.reading-table > tbody > tr > td:nth-child(2)').each(function(){
				if(counter > 24){ return false; }
				temp = $(this).text().split(' ');
				date.push(temp[0].toString());
				counter++;
			});	
			
			//Get all Reading Amount Date
			counter = 0;
			$('.reading-table > tbody > tr > td:nth-child(3)').each(function(){
				if(counter > 24){ return false; }
				temp = $(this).text().replace(',', '');
				amount.push(temp);
				counter++;
			});	
						
			//Get all the Consumption data
			counter = 0;
			$('.reading-table > tbody > tr > td:nth-child(4)').each(function(){
				if(counter > 24){ return false; }
				temp = $(this).text().replace(',', '');
				consumption.push(temp);
				counter++;
			});			
			var biggestConsumtionValue = Math.max.apply(Math, consumption);
			var interval = 0;
			if(biggestConsumtionValue >= 500 && biggestConsumtionValue < 1000){ interval = 100; }
			else if(biggestConsumtionValue >= 1000 && biggestConsumtionValue < 10000){ interval = 500; }
			else if(biggestConsumtionValue >= 10000){ interval = 1000; }
			
			var ticks = date;
			var plot1 = $.jqplot('chart_div',[consumption],{
				seriesDefaults:{
					renderer:$.jqplot.BarRenderer,
					rendererOptions: {fillToZero: true}
				},
				series:[ {label:'Consumption'} ],
				legend: {
					show: true,
					placement: 'outsideGrid'
				},
				axes: {
					// Use a category axis on the x axis and use our custom ticks.
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer,
						ticks: ticks
					},
					// Pad the y axis just a little so bars can get close to, but
					// not touch, the grid boundaries.  1.2 is the default padding.
					yaxis: {						
						//tickOptions: {formatString: '%d'}						
						pad: 1.05,
						min: 0,
						tickInterval: interval,
						max: (biggestConsumtionValue + 100)
					}
				},
				highlighter: {
					show: true,
					sizeAdjust: 7.5
				},
				cursor: { show: true }
			});					
			$('.tab-container > ul > li:last-child >a').click(function(){
				plot1.replot();
			});						
		});
	</script>	
<?php endif; ?>