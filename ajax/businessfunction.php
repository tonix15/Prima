<?php
	if(isset($_POST['key'])){
		$businessFunctionQuery = (int)$_POST['key'];
		
		$Result = array('isMeterReader' => '', 'isPortfolioManager' => '');
		if($businessFunctionQuery == 0){ 
			$Result['isMeterReader'] = 0;
			$Result['isPortfolioManager'] = 0;
		}
		else{
			require_once '../init.php';
			$BusinessFunction = new BusinessFunction($dbh);
			$business_function_query = $BusinessFunction->getBusinessFunction(array($Session->read('UserPk'), $businessFunctionQuery));			
			
			if(!empty($business_function_query)){
				foreach($business_function_query as $ResultSet){
					$Result['isMeterReader'] = $ResultSet['IsMeterReader'];
					$Result['isPortfolioManager'] = $ResultSet['IsPortfolioManager'];
				}				
			}	
			else{
				$Result['isMeterReader'] = 0;
				$Result['isPortfolioManager'] = 0;
			}
		}
		echo json_encode($Result);
		exit();
	}
?>