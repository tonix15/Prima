<?php
$page_name = 'Reading Current Negative Consumption';

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
$data_list = $dbhandler->getReadingNegative($params);
$errmsg = !empty($data_list) ? '':'<strong>No Entries Found.</strong>';	
?>

<div class="sub-menu-title"><h1>Reading Current Negative Consumption</h1></div>
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
						<th>Meter Number</th>
						<th>Last Reading Date</th>							
						<th>Previous Reading Date</th>
						<th>Last Reading</th>
						<th>Previous Reading</th>
						<th>Consumption</th>
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
								<td><?php echo $report['Meter']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['LastReadingDate']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['PreviousReadingDate']; ?></td>
								<td class="table-column-text-align-right"><?php echo round($report['LastReading'], 0); ?></td>
								<td class="table-column-text-align-right"><?php echo round($report['PreviousReading'], 0); ?></td>
								<td class="table-column-text-align-right"><?php echo round($report['Consumption'], 0); ?></td>
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
				
				$title = 'Reading Current Negative Consumption Report';
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
