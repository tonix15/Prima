<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Provider = new Provider($dbh);
   
    switch($data->action) {
        case 'checkDuplicate':
            $result = $Provider->getProvider(array(0, 0));
            $response = array();
            if (!empty($data->provider_code)) {
	            foreach ($result as $rec) {
	                if (strtoupper($rec['NumberBk']) === strtoupper($data->provider_code)) {
						$response['checkProviderCode'] = 'duplicate';
						break;
					}
	            }
            }
            
            if (!empty($data->erpcode)) {
            	foreach ($result as $rec) {
            		if (strtoupper($rec['ERPCode']) === strtoupper($data->erpcode)) {
            			$response['checkERPCode'] = 'duplicate';
            			break;
            		}
            	}
            }
            
            echo json_encode($response);
            break;
    }
}
?>