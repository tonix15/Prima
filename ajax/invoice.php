<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);	
	
    require_once '../init.php';
	$userCredentials = $User->getUserCredentials();
	$userPK = $userCredentials['UserPk'];
	
	$buildingPk = $data->buildingPK;
	$unitPk = $data->unitPK;	
	$customerNumberBK = $data->customerNumberBK;
	
	$params = array(
		(int)$userPK,
		(int)$Session->read('user_company_selection_key'),
		(int)$buildingPk,
		(int)$unitPk,
		$customerNumberBK
	);
	
	$invoice_list = $dbhandler->getInvoice($params);
	/*$invoice_list = array(
		0 => array(
			'InvNumber' => 'INV0046',
			'BuildingFk' => 108,
			'UnitFk' => 4812,
			'CustomerNumberBk' => '01',
			'BillingAccountPk' => 3743,
			'DCLink' => 6249
		),
		1 => array(
			'InvNumber' => 'INV0128',
			'BuildingFk' => 108,
			'UnitFk' => 4812,
			'CustomerNumberBk' => '01',
			'BillingAccountPk' => 3743,
			'DCLink' => 6249
		),
		2 => array(
			'InvNumber' => 'INV0129',
			'BuildingFk' => 108,
			'UnitFk' => 4812,
			'CustomerNumberBk' => '01',
			'BillingAccountPk' => 3743,
			'DCLink' => 6249
		),
		3 => array(
			'InvNumber' => 'INV0130',
			'BuildingFk' => 108,
			'UnitFk' => 4812,
			'CustomerNumberBk' => '01',
			'BillingAccountPk' => 3743,
			'DCLink' => 6249
		),
		4 => array(
			'InvNumber' => 'INV0385',
			'BuildingFk' => 108,
			'UnitFk' => 4812,
			'CustomerNumberBk' => '01',
			'BillingAccountPk' => 3743,
			'DCLink' => 6249
		),
		5 => array(
			'InvNumber' => 'INV11667',
			'BuildingFk' => 108,
			'UnitFk' => 4812,
			'CustomerNumberBk' => '01',
			'BillingAccountPk' => 3743,
			'DCLink' => 6249
		)
	);*/
	
	$ResultSet = array();
    switch($data->action) {
        case 'getInvoice':
            if(!empty($invoice_list)){
				foreach($invoice_list as $list){
					$ResultSet[] = array(
						'InvoiceNumber' => $list['InvNumber'],
						'buildingFK' => $list['BuildingFk'],
						'unitFK' => $list['UnitFk'],
						'customerNumberBK' => $list['CustomerNumberBk'],
						'BillingAccountPK' => $list['BillingAccountPk'],
						'DCLink' => $list['DCLink']
					);
				}				
			}
            break;
    }
    echo json_encode($ResultSet);
}
?>