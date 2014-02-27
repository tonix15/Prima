<?php
	if(isset($_POST['CompanyRegistrationNumber'])){
		$requested_registration_number = json_decode($_POST['CompanyRegistrationNumber']);
		require_once '../init.php';
		$Company = new Company($dbh);
		
		$queried_registration_number = $Company->getCompany(array($Session->read('UserPk'), 0));
		
		$isDuplicate = FALSE;
		foreach($queried_registration_number as $ResultSet){
			if(strtoupper($requested_registration_number) == strtoupper($ResultSet['RegistrationNumber'])){ 
				$isDuplicate = TRUE; 
				break;
			}
			else{ $isDuplicate = FALSE; }
		}
		if($isDuplicate){ echo 'duplicate'; }
		else{ echo 'entry not found'; }
		exit();
	}
?>