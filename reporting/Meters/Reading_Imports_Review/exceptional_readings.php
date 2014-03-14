<?php
$page_name = 'Exceptional Readings';

require_once '../../../init.php';

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

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

$selectedDate = date('Y-m-d');

$data_list = NULL;

// UI
$errmsg = '';

if (isset($_GET['View'])) { 	    
	$selectedDate = $_GET['choose_date'];
	$params = array(
		$userPK,
		$Session->read('user_company_selection_key'),
		$selectedDate
	);
	$data_list = $dbhandler->repReadingExceptional($params);
	$errmsg = !empty($data_list) ? '':'<h2>No Entries Found.</h2>';	
} 
else if (isset($_POST['Cancel'])) {
    $view_class = 'hidden';
}
?>

<div class="sub-menu-title"><h1>Exceptional Readings Report</h1></div>
<form method="get">
<div id="rate-selection" class="wrapper-fieldset-forms hover-cursor-pointer">
    <fieldset class="fieldset-forms">
        <legend>Exceptional Reading</legend>
        <ul class="fieldset-forms-li-2-cols">            
            <li>Reading Date:</li>
            <li><input type="date" id="exceptional-reading-selection-date" name="choose_date" class="selection-required-input" value="<?php echo $selectedDate; ?>"/></li>	
        </ul>
        <div class="selection-form-submit float-left">
			<button type="submit" name="View">View</button>
        </div>         
    </fieldset>
</div> <!-- end of rate selection -->
</form> <!-- end of get form -->
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
							<th>Reading Date</th>							
							<th>Meter Reader</th>
							<th>Building</th>
							<th>Unit Number</th>
							<th>Utility Type</th>
							<th>Meter Number</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($data_list as $report):?>
							<tr>
								<td class="table-column-text-align-center"><?php echo $report['ReadingDay']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['DisplayName']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['Name']; ?></td>
								<td class="table-column-text-align-right"><?php echo $report['UnitNumber']; ?></td>
								<td><?php echo $report['UtilityType']; ?></td>
								<?php $reading_anchor = DOMAIN_NAME . '/query_and_reporting/Meter/reading.php?choose_building=' . $report['BuildingFk'] . '&choose_unit=' . $report['UnitFk'] . '&choose_meter=' . $report['MeterPk'] . '&View=View'; ?>
								<td class="table-column-text-align-right"><?php echo '<a href="' .$reading_anchor . '" target="_blank">' . $report['MeterNumber'] . '</a>'; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php
					//Collect the output buffer into a variable
					$html = ob_get_contents();
					ob_end_flush();
					
					$title = 'Exceptional Readings Report as of ' . $selectedDate;
					$Session->write('title', $title);
					$Session->write('content', $html);
					
					unset($title);
					unset($html);
					
					require_once DOCROOT . '/widgets/convert_pdf_spreadsheet.php'
				?>				
			</div>
		</div>
<?php 
	else: echo $errmsg;
	endif; 

require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>