<?php
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    require_once '../init.php';
    $Meter = new Meter($dbh);
    $meter_list = array();
    
    switch($data->action) {
    	case 'getUnitsWithMeter':
    		$Unit = new Unit($dbh);
    		$unit_list = array();
    		$unit_recordset = $Unit->getUnit(array(0, 0, $data->buildingPK));
    		foreach ($unit_recordset as $rec) {
    			$unit_list[] = array('unitPK' => $rec['UnitPk'], 'unitNo' => $rec['NumberBk']);
    		}
    		
    		$meter_recordset = $Meter->getMeter(array(0, 0, $data->buildingPK, 0));
    		foreach ($meter_recordset as $rec) {
    			$meter_list[] = array('meterPK' => $rec['MeterPk'], 'meterNo' => $rec['NumberBk']);
    		}
    		
    		echo json_encode(array('unit_list' => $unit_list, 'meter_list' => $meter_list));
    		exit;
        case 'getMeters':
            $result = $Meter->getMeter(array(0, 0, $data->buildingPK, $data->unitPK));
			foreach ($result as $rec) {
                $meter_list[] = array('meterPK' => $rec['MeterPk'], 'meterNo' => $rec['NumberBk']);
            }
            echo json_encode($meter_list); 
            exit;
        case 'getReplacementMeters':
        	$result = $Meter->getMeter(array(0, 0, $data->buildingPK, $data->unitPK, $data->utility_typePK));
        	foreach ($result as $rec) {
        		if ($rec['MeterPk'] != $data->meterPK) {
        			$meter_list[] = array('meterPK' => $rec['MeterPk'], 'meterNo' => $rec['NumberBk']);
        		}
        	}
        	echo json_encode($meter_list);
        	exit;
    }
    
   
}
?>