<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Building = new Building($dbh);
	
    switch($data->action) {
		case 'checkDuplicate':
            $result = $Building->getBuilding(array(0, 0));
            foreach ($result as $rec) {
                if (strtoupper($rec['NumberBk']) === strtoupper($data->building_code)) {
					echo 'duplicate';
					exit;
				}
            }
            break;
    }
     
}
?>