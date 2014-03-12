<?php

$page_name = 'DEPOSIT LIST';

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
	else if ($Session->read('user_company_selection_key') <= 0 ){
		$Session->write('select_company', '<h2>Please Select a Company</h2>');
		header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
		exit();
	}	
}

$userCredentials = $User->getUserCredentials();
$userPK = $userCredentials['UserPk'];

$view_class = 'hidden';
$buildingPK = !empty($_GET['choose_building']) ? (int) $_GET['choose_building'] : 0;

$Building = new Building($dbh);
$Sage = new Sage($dbh);

$building_list = $Building->getBuilding(array($userPK, 0));

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

if(isset($_GET['View'])){
	//UI
	$view_class = 'show';
	$buildingPK = $_GET['choose_building'];
	$building_names = $Building->getBuilding(array($userPK, $buildingPK));
	$building_names = $Building->getSingleRecord($building_names);
}

if(isset($_GET['View_All'])){
	//UI
	$view_class = 'show';
	$buildingPK = 0;
	$building_names = $Building->getBuilding(array($userPK, $buildingPK));
	$building_names = $Building->getSingleRecord($building_names);
}

$deposit_list = $Sage->importDeposit(array($Session->read('user_company_selection_key'), $buildingPK));

require DOCROOT . '/template/header.php';
?>
<form method="get" class="hover-cursor-pointer">
	<div class="sub-menu-title"><h1>Deposit List</h1></div>
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
							$selected = $buildingPK == $building['BuildingPk'] ? 'selected="' . $building['BuildingPk'] . '"':''; ?>
							<option <?php echo $selected; ?> value="<?php echo $building['BuildingPk']; ?>"><?php echo $building['Name']; ?></option>
						<?php } } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="meter-selection-view" type="submit" value="View" name="View"/>
				<input id="meter-selection-view_all" type="submit" value="View All" name="View_All"/>
			</div> 
			<div id="meter-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
		</fieldset>
	</div> <!-- end of building selection -->
</form> <!-- end of get form -->

<?php if ($view_class === 'show' ) { ?>   

         
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
		<table class="billing planning-table planning-table-striped planning-table-hover">
			<thead>
				<tr>
					<th width="91">Account</th>
					<th width="108">Name</th>
					<th width="108">Deposit Requirement</th>
					<th width="108">Actual Deposit</th>
					<th width="108">Variance</th>
				</tr>
			</thead>
			<tbody>	
			<?php if (!empty($deposit_list)) { 
				foreach ($deposit_list as $deposit) { ?>
					<tr>
						<td><?php echo $deposit['Account']; ?></td>
						<td><?php echo $deposit['Name']; ?></td>
						<td class="table-column-text-align-right"><?php echo number_format ($deposit['DepositRequired'], 2 , '.', ','); ?></td> 
						<td class="table-column-text-align-right"><?php echo number_format ($deposit['ActualDeposit'], 2 , '.', ','); ?></td> 
						<td class="table-column-text-align-right"><?php echo number_format ($deposit['Variance'], 2 , '.', ','); ?></td>
					</tr>
			<?php } } ?>	
			</tbody>
		</table>

		<?php
			//Collect the output buffer into a variable
			$html = ob_get_contents();
			ob_end_flush();
			
			$title = 'Deposit list on Building ' . ucwords(strtolower($building_names['Name'])) . ' as of ' . date('Y-m-d');
			$Session->write('title', $title);
			$Session->write('content', $html);
			
			unset($title);
			unset($html);
				
			require_once DOCROOT . '/widgets/convert_pdf_spreadsheet.php'
		?>		
	</div>
</div>
<?php
	}
?>

<?php
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">
	$(function(){ TABLE.paginate('.billing', 10); });
</script>


