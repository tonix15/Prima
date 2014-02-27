<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Billing = new Billing($dbh);
    
    switch($data->action) {
        case 'checkOccupancyDate':
			// last parameter is set to zero to get the latest billing account
			$latest_billing_account = $Billing->getBillingAccount(array(0, 0, $data->buildingPK, $data->unitPK, 0));
            $latest_billing_account = $Billing->getSingleRecord($latest_billing_account);
			if (empty($latest_billing_account) || strtotime($data->occupancy_date) >= strtotime($latest_billing_account['VacancyDate'])) {
				echo 'true';
			} else {
				echo 'false';
			}
		break;
    }
}
?>