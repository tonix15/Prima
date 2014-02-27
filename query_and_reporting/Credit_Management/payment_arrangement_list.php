<?php
$page_name = 'Buildings with Active Rates';

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
$data_list = $dbhandler->getArrangementList($params);
$errmsg = !empty($data_list) ? '':'<strong>No Entries Found.</strong>';	
?>

<div class="sub-menu-title"><h1>Payment Arrangement List</h1></div>
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
						<th>Portfolio Manager</th>
						<th>Building Name</th>
						<th>Unit Number</th>
						<th>Account Number</th>
						<th>Surname</th>							
						<th>Initials</th>
						<th>Name</th>
						<th>Arrangement Date</th>
						<th>Outstanding Balance</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($data_list)):
							foreach($data_list as $report):
					?>
							<tr>
								<td><?php echo $report['PortfolioManager']; ?></td>								
								<td><?php echo $report['Building']; ?></td>
								<td><?php echo $report['Unit']; ?></td>
								<td><?php echo $report['Account']; ?></td>
								<td><?php echo $report['Surname']; ?></td>
								<td><?php echo $report['Initials']; ?></td>
								<td><?php echo $report['Name']; ?></td>
								<td><?php echo $report['ArrangementDate']; ?></td>
								<td class="table-column-text-align-right table-column-width-50"><?php echo number_format($report['Outstanding'], 2, '.', ','); ?></td>
							</tr>
					<?php endforeach; 
						else: echo '<tr><td colspan="9"' . $errmsg . '</td></tr>';
						endif;
					?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Payment Arrangement List';
				$Session->write('title', $title);
				$Session->write('content', $html);
				
				unset($title);
				unset($html);
			?>
			<label>
				Export as: 
				<a title="PDF" href="<?php echo DOMAIN_NAME . '/query_and_reporting/export_as_pdf.php';?>">PDF</a>
				<a title="SpreadSheet" href="<?php echo DOMAIN_NAME . '/query_and_reporting/export_as_csv.php';?>">SpreadSheet</a>
			</label>
		</div>
	</div>
		
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.date.sniffer.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>
