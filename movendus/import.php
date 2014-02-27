<?php
define('NUM_ROWS', 350);
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
			$building_list = array();	
			$building_recordset = $dbhandler->movendusExportBuilding(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($building_recordset);
			$from = $from + $num_rows;
			
			foreach ($building_recordset as $rec) {
				$building_list[] = array(
					'CompanyCode' => $rec['CompanyCode'],
					'BuildingCode' => $rec['BuildingCode'],
					'BuildingNumber' => $rec['BuildingNumber'],
					'Name' => $rec['Name'],
					'ReadingRoute' => $rec['ReadingRoute'],
					'Address1' => $rec['Address1'],
					'Address2' => $rec['Address2'],
					'Address3' => $rec['Address3'],
					'Address4' => $rec['Address4']
				);
			}
			
			unset($building_recordset);
			$isDone = $num_rows < NUM_ROWS ? true : false;
			$record_list = &$building_list;
			break;
		case 'importMeter': case 'importTerminationMeter': 
			$meter_list = array();
			
			if ($data->action == 'importMeter') {
				$meter_recordset = $dbhandler->movendusExportMeter(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			} else if ($data->action == 'importTerminationMeter') {
				$meter_recordset = $dbhandler->movendusExportTerminationMeter(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			} else {
				exit;
			}
			
			$num_rows = count($meter_recordset);
			$from = $from + $num_rows;
			
			foreach ($meter_recordset as $rec) {
				$meter_list[] = array(
					'MeterCode' => $rec['MeterCode'],
					'MeterNumber' => $rec['MeterNumber'],
					'MeterRouteCode' => $rec['MeterRouteCode'],
					'MeterTypeCode' => $rec['MeterTypeCode'],
					'BuildingCode' => $rec['BuildingFk'],
					'UnitCode' => $rec['UnitCode'],
					'UtilityTypeCode' => $rec['UtilityTypeCode'],
					'IsThreePhase' => $rec['IsThreePhase'],
					'PreviousReadingDate' => $rec['PreviousReadingDate'],
					'PreviousReadingAmount' => $rec['PreviousReadingAmount'],
					'AverageConsumption' => $rec['AverageConsumption']
				);
			} 
			
			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($meter_recordset);
			$record_list = &$meter_list;
			break;
		case 'importUnit':
			$unit_list = array();
			$unit_recordset = $dbhandler->movendusExportUnit(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($unit_recordset);
			$from = $from + $num_rows;
			
			foreach ($unit_recordset as $rec) {
				$unit_list[] = array(
					'UnitCode' => $rec['UnitCode'],
					'UnitNumber' => $rec['UnitNumber'],
					'BuildingCode' => $rec['BuildingCode']
				);
			} 
			
			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($unit_recordset);
			$record_list = &$unit_list;
			break;
		case 'importReason':
			$Reason = new Reason($dbh);
			$reason_list = array();
			
			$reason_recordset = $dbhandler->movendusExportReason(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($reason_recordset);
			$from = $from + $num_rows;
			
			foreach ($reason_recordset as $rec) {
				$reason_list[] = array(
					'ReasonCode' => $rec['ReasonCode'],
					'ReasonDescription' => $rec['ReasonDescription']
				);
			} 
			
			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($reason_recordset);
			$record_list = &$reason_list;
			break;
		case 'importUtilityType':
			$utility_list = array();
			$utility_recordset = $dbhandler->movendusExportUtilityType(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($utility_recordset);
			$from = $from + $num_rows;
			
			foreach ($utility_recordset as $rec) {
				$utility_list[] = array(
					'UtilityTypeCode' => $rec['UtilityTypeCode'],
					'UtilityTypeDescription' => $rec['UtilityTypeDescription']
				);
			} 
			
			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($utility_recordset);
			$record_list = &$utility_list;
			break;
		case 'importMeterType':
			$meter_type_list = array();	
			$meter_type_recordset = $dbhandler->movendusExportMeterType(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($meter_type_recordset);
			$from = $from + $num_rows;
			
			foreach ($meter_type_recordset as $rec) {
				$meter_type_list[] = array(
					'MeterTypeCode' => $rec['MeterTypeCode'],
					'MeterTypeDescription' => $rec['MeterTypeDescription']
				);
			} 
			
			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($meter_type_recordset);
			$record_list = &$meter_type_list;
			break;
		case 'importMaster':
			$master_list = array();
			$master_recordset = $dbhandler->movendusExportMaster(array($userPK, $companyPK, $from, NUM_ROWS, LAST_UPDATED_DATE));
			$num_rows = count($master_recordset);
			$from = $from + $num_rows;
			
			foreach ($master_recordset as $rec) {
				$master_list[] = array(
					'CompanyCode' => $rec['CompanyCode'],
					'MeterDeviation' => $rec['MeterDeviation'],
					'Input2Deviation' => $rec['Input2Deviation']
				);
			}

			$isDone = $num_rows < NUM_ROWS ? true : false;
			unset($master_recordset);
			$record_list = &$master_list;
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
						'BulkMeterDeviationValue' => $rec['BulkMeterDeviation']
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