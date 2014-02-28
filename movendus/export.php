<?php
define('NUM_ROWS', 5);
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

if (isset($_POST['data'])) {
	require_once '../init.php';
	$data = json_decode($_POST['data']);
	$meter_count  = count($data->meter_list);
	$reading_date = null;
	$result = array();
	
	if (!empty($data->meter_list)) {
		
		$user = $dbhandler->getUser(array(0, $data->userCode, 0), true);
		$teamCode = $user['TeamFk'];
		
		foreach ($data->meter_list as $meter) {
			$transactionNumber = $meter->BuildingCode . '/' . $teamCode . '/' .
				date(DATETIME_FORMAT, strtotime($meter->ReadingDate));
			
			$dbhandler->movendusImportReading(array(
				$data->userCode,
				$meter->BuildingCode,
				$transactionNumber,
				$meter->MeterCode,
				$meter->ReadingDate,
				$meter->ReadingAmount,
				$meter->IsEstimated,
				$meter->ReasonCode,
				$meter->IsRemoved,
				$meter->IsExceptionalReading,
				$meter->IsTestReading,
				$meter->Geolocation
			));
			
			$dbhandler->movendusImportMeter(array(
				$data->userCode,
				$meter->MeterCode,
				$meter->MeterNumber,
				$meter->UtilityTypeCode,
				$meter->MeterTypeCode
			));
			
			if (!empty($meter->Photo)) {
				$image = Prima::convertStringToImage($meter->Photo);
				$dir = DOCROOT . '/uploads/meters/' . $meter->MeterCode . '/';
				Prima::saveImageToDisk($image, $dir, Prima::formatToSaveDate($meter->ReadingDate));
			} 
			
			$reading_date = $meter->ReadingDate;
		}
	
	} 
	
	if ($meter_count < NUM_ROWS) {
		$result['isDone'] = true;
		$result['from'] = 0;
		// 1 is for tripple M company. Take not that this must be dynamic to avoid confict in the future.
		
		$result['result'] = $dbhandler->repReadingImport(array($data->userCode, 1, Prima::formatToSaveDate($reading_date)));
		// $result['result'] = $dbhandler->repReadingImport(array($data->userCode, 1, Prima::getSaveDate()));
	} else {
		$result['isDone'] = false;
		$result['from'] = (int) $data->from + NUM_ROWS;
	}
	
	echo json_encode($result);
}
?>