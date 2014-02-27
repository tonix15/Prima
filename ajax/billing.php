<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Billing = new Billing($dbh);
    $billing_account_list = array();
    
    switch($data->action) {
        case 'getBillingAccounts':
            $result = $Billing->getBillingAccount(array(0, 0, $data->buildingPK, $data->unitPK, 1));
            foreach ($result as $rec) {
                $billing_account_list[] = array('billing_accountPK' => $rec['BillingAccountPk'], 'billing_accountNo' => $rec['NumberBk']);
            }
            break;
    }
    echo json_encode($billing_account_list); 
}
?>