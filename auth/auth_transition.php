<?php require_once '../init.php'; 	
	$_email = $_POST['user_email'];
	$_password = $_POST['user_password']; 
	
	//Get user credentials from http post request
	if(!empty($_email) && !empty($_password)){		
		//Sanitize email and password
		$_email = Sanitize::cleanEmail($_email);
		$_password = Sanitize::sanitizeStr($_password);
		
		//Check if valid email address
		if(Validate::validateEmail($_email)){
			/* 
			 * Get User Credentials from database
			 * if authenticate returns non-empty array then user is existing
			 * and authenticated.
			 *
			 */
			$ResultSet = $User->login(array($_email));
			$ResultSet = $User->getSingleRecord($ResultSet);
			if(!empty($ResultSet)){
				if(Bcrypt::check($_password, $ResultSet['Password'])){
					session_regenerate_id(TRUE);
					$Session->write('isLoggedIn', TRUE);
					$Session->write('UserPk', $ResultSet['UserPk']);
					$Session->write('DisplayName', $ResultSet['DisplayName']);
					
					//find out the business function that is associated with the authenticated user
					$BusinessFunctionUser = new BusinessFunctionUser($dbh);
					$business_function_user_data = $BusinessFunctionUser->getBusinessFunctionUser(array($ResultSet['UserPk'], 0));
					
					$associated_business_function = 0;
					
					foreach($business_function_user_data as $business_function_user){
						if($ResultSet['UserPk'] == $business_function_user['BusinessUserFk']){
							$associated_business_function = $business_function_user['BusinessFunctionFk'];
							break;
						}
					}
					
					$Session->write('BusinessFunctionUser', $associated_business_function);
					
					header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
					exit();
				}
				else{
					$Session->write('auth_error', 'Invalid email address or password.');
					$Session->write('email', $_email);
					header('Location: authenticate.php');
					exit();
				}
			}
		}
		else{
			$Session->write('auth_error', 'Invalid email address.');
			$Session->write('email', $_email);
			header('Location: authenticate.php');
			exit();
		}
	}	
?>
