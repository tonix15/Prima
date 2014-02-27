<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Unit = new Unit($dbh);
    $unit_list = array();
    
    switch($data->action) {
        case 'getUnits':
            $result = $Unit->getUnit(array(0, 0, $data->buildingPK));
            foreach ($result as $rec) {
                $unit_list[] = array('unitPK' => $rec['UnitPk'], 'unitNo' => $rec['NumberBk']);
            }
            break;
    }
    
    echo json_encode($unit_list); 
}
?>