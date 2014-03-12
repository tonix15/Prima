<?php
$page_name = 'Outstanding Agreement List';

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
$data_list = $dbhandler->repOutstandingAgreement($params);
$errmsg = !empty($data_list) ? '':'<strong>No Entries Found.</strong>';	
?>

<div class="sub-menu-title"><h1>Outstanding Agreement List Report</h1></div>
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
						<th>Account Number</th>
						<th>Surname</th>
						<th>Initials</th>							
						<th>Name</th>
						<th>Cellphone</th>
						<th>Occupance Date</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($data_list)):
							foreach($data_list as $report):
					?>
							<tr>
								<td><?php echo $report['Building']; ?></td>								
								<td class="table-column-text-align-center"><?php echo $report['ERPCode']; ?></td>
								<td><?php echo $report['Surname']; ?></td>
								<td><?php echo $report['Initials']; ?></td>
								<td><?php echo $report['Customer']; ?></td>
								<td><?php echo $report['Cellphone']; ?></td>
								<td class="table-column-text-align-center"><?php echo $report['OccupancyDate']; ?></td>
							</tr>
					<?php endforeach; 
						else: echo '<tr><td colspan="7"' . $errmsg . '</td></tr>';
						endif;
					?>
				</tbody>
			</table>
			<?php
				//Collect the output buffer into a variable
				$html = ob_get_contents();
				ob_end_flush();
				
				$title = 'Outstanding Agreement List Report';
				$Session->write('title', $title);
				$Session->write('content', $html);
								
				unset($title);
				unset($html);
				
				require_once DOCROOT . '/widgets/convert_pdf_spreadsheet.php'
			?>			
		</div>
	</div>
		
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/pagination.js"></script>
<script type="text/javascript">$(function(){ TABLE.paginate('.billing', 10); });</script>
