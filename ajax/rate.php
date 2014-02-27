<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Rate = new Rate($dbh);
	
    switch($data->action) {
        case 'getRates':
			$rate_list = array();
            $result = $Rate->getRate(array(0, 0, $data->providerPK));
            foreach ($result as $rec) {
                $rate = array();
                $rate['ratePK'] = $rec['RatePk'];
                $rate['utilityPK'] = $rec['UtilityTypeFk'];
                $rate['name'] = $rec['Name'];
                $rate_list[] = $rate;
            }
			echo json_encode($rate_list);
            break;
		case 'checkDuplicate':
            $result = $Rate->getRate(array(0, 0, 0));
            foreach ($result as $rec) {
                if (strtoupper($rec['NumberBk']) === strtoupper($data->rate_code)) {
					echo 'duplicate';
					exit;
				}
            }
            break;
    }
     
}
?>