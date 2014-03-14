<?php
define('NUM_ROWS', 100);
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('LAST_UPDATED_DATE', '1970-01-01');

if (isset($_POST['data'])) {
	require_once '../init.php';
	$data = json_decode($_POST['data']);
	$userPK = $data->userCode;
	$companyPK = $data->companyCode;
	$record_list = null;
	$from = (int) $data->from;
	$num_rows = 0;

	switch($data->action) {
		case 'importBuilding':
			$building_recordset = $dbhandler->movendusExportBuilding(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($building_recordset);
			$from = $from + $num_rows;
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$building_recordset;
			break;
		case 'importMeter': case 'importTerminationMeter': 
			$meter_recordset = null;
			
			if ($data->action == 'importMeter') {
				$meter_recordset = $dbhandler->movendusExportMeter(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
				$num_rows = count($meter_recordset);
				$from = $from + $num_rows;
			} else if ($data->action == 'importTerminationMeter') {
				$meter_recordset = $dbhandler->movendusExportTerminationMeter(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
				$num_rows = count($meter_recordset);
				$from = $from + $num_rows;
			} else {
				exit;
			}
				
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$meter_recordset;
			break;
		case 'importUnit':
			$unit_recordset = $dbhandler->movendusExportUnit(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($unit_recordset);
			$from = $from + $num_rows;

			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$unit_recordset;
			break;
		case 'importReason':
			$reason_recordset = $dbhandler->movendusExportReason(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($reason_recordset);
			$from = $from + $num_rows;
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$reason_recordset;
			break;
		case 'importUtilityType':
			$utility_recordset = $dbhandler->movendusExportUtilityType(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($utility_recordset);
			$from = $from + $num_rows;
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$utility_recordset;
			break;
		case 'importMeterType':
			$meter_type_recordset = $dbhandler->movendusExportMeterType(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($meter_type_recordset);
			$from = $from + $num_rows;	
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$meter_type_recordset;
			break;
		case 'importMaster':
			$master_recordset = $dbhandler->movendusExportMaster(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($master_recordset);
			$from = $from + $num_rows;
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$master_recordset;
			break;
		case 'importBulkMeterDeviation':
			$bulk_meter_deviation_list = array();
			$bulk_meter_deviation_recordset = $dbhandler->movendusExportBulkMeterDeviation(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($bulk_meter_deviation_recordset);
			$from = $from + $num_rows;
				
			foreach ($bulk_meter_deviation_recordset as $rec) {
				$bulk_meter_deviation_list[] = array(
					'BuildingCode' => $rec['BuildingFk'],
					'UtilityTypeCode' => $rec['UtilityTypeFk'],
					'Value' => $rec['BulkMeterDeviation']
				);
			}
		
			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($bulk_meter_deviation_recordset);
			$record_list = &$bulk_meter_deviation_list;
			break;
	}

	echo json_encode(array(
			'record_list' => $record_list,
			'from' => $from,
			'isDone' => $isDone
	));
}
?>