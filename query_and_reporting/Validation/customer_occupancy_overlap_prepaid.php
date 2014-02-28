<?php
$page_name = 'Customers Occupancy Overlap Prepaid';

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

$params = array(
	$userPK,
	$Session->read('user_company_selection_key')
);
$data_list = $dbhandler->getCustomerOccupancyOverlapPrepaid($params);
$errmsg = !empty($data_list) ? '':'<strong>No Entries Found.</strong>';	
?>

<div class="sub-menu-title"><h1>Customers Occupancy Overlap Prepaid Report</h1></div>
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
						<th>Building Name</th>
						<th>Unit Number</th>
						<th>Current Customer</th>
						<th>Occupancy Date</th>							
						<th>Previous Customer</th>
						<th>Vacancy Date</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($data_list)):
							foreach($data_list as $report):
					?>
							<tr>
								<td><?php echo $report['Building']; ?></td>								
								<td class="table-column-text-align-center"><?php echo $report['Unit']; ?></td>
								<td class="table-column-text-align-right"><?php echo $report['LastERPCode']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['OccupancyDate']; ?></td>
								<td class="table-column-text-align-right"><?php echo $report['PreviousERPCode']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['VacancyDate']; ?></td>
							</tr>
					<?php endforeach; 
						else: echo '<tr><td colspan="8"' . $errmsg . '</td></tr>';
						endif;
					?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Customers Occupancy Overlap Prepaid Report';
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/query_and_reporting_pdf.php'
			?>
		</div>
	</div>
		
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>
