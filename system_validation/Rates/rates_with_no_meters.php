<?php
$page_name = 'Rates with no Meters';

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
$data_list = $dbhandler->valRatesNoMeters($params);
$errmsg = !empty($data_list) ? '':'<strong>No Entries Found.</strong>';	
?>

<div class="sub-menu-title"><h1>Rates with no Meters Report</h1></div>
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
						<th>Rate Number</th>
						<th>Rate Name</th>						
						<th>Utility Type</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($data_list)):
							foreach($data_list as $report):
					?>
							<tr>
								<td><?php echo $report['BuildingName']; ?></td>
								<td><?php echo $report['RateNumber']; ?></td>
								<td><?php echo $report['RateName']; ?></td>
								<td><?php echo $report['UtilityType']; ?></td>
							</tr>
					<?php endforeach; 
						else: echo '<tr><td colspan="4"' . $errmsg . '</td></tr>';
						endif;
					?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Rates with no Meters Report';
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/convert_pdf_spreadsheet.php'
			?>
		</div>
	</div>
		
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>
