<?php
if (isset($_POST['data'])) {
	require_once '../init.php';
	$data = json_decode($_POST['data']);
	
	$recordset = $User->login(array(strtoupper($data->username)));
	$recordset = $User->getSingleRecord($recordset);
	
	if(!empty($recordset)){
		if(Bcrypt::check($data->password, $recordset['Password'])){
			$Company = new Company($dbh);
			$user_recordset = $User->getUserLogin(array(strtoupper($data->username)), true);
			$company_recordset = $Company->getCompanyUser(array(0 , $user_recordset['UserPk']), true);
			
			echo json_encode(array(
				'UserCode' => $user_recordset['UserPk'], 
				'CompanyCode' => $company_recordset['CompanyFk']
			));
		}
	}
}

?>