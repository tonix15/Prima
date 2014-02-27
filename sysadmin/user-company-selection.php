<?php
$page_name = 'USER COMPANY';

require_once '../init.php';

//User
$user = new User($dbh);
if(!$user->isUserLogin()){
	header('Location:' . DOMAIN_NAME . '/index.php');
	exit();
}
$userCredentials = $user->getUserCredentials();
$userPK = $userCredentials['UserPk'];

//Company
$Company = new Company($dbh);
$company_data = NULL;
$company_list = $Company->getCompany(array($userPK, 0));

//Company User
$CompanyUser = new CompanyUser($dbh);
$company_user_data = $CompanyUser->getCompanyUser(array($userPK, 0));

if(isset($_POST['choose_company'])){ 	
	$user_company_temp_value = explode('|', $_POST['user-company-selection-value']);
	
	$user_company_selection_key = $user_company_temp_value[0];
	$user_company_selection_text = $user_company_temp_value[1];
	
	if($user_company_selection_key > 0){		
		$Session->write('user_company_selection_key', $user_company_selection_key); 
		$Session->write('user_company_selection_text', $user_company_selection_text);
		$company_id = (int) $_POST['user_company_selection'];
		$user->setCompanyId($company_id); 
		
		if($Session->check('select_company')){ $Session->sessionUnset('select_company'); }
		header('Location:' . DOMAIN_NAME . '/index.php');
		exit;
	}	
}

require DOCROOT . '/template/header.php';
?>

<form method="post" class="hover-cursor-pointer" id="user-company-selection-form">	
	<?php
		if($Session->check('select_company')){ echo '<div class="warning warning-box">' . $Session->read('select_company') . '</div>'; }
	?>
	
	<div id="user-company-selection" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>Company Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>User Company:</label></li>
				<li>  
					<select id="user-selection-company" name="user_company_selection" class="selection-required-input">
						<option value="0">Please choose a company</option>
					<?php
						if(!empty($company_user_data)){							
							foreach($company_user_data as $company_user){
								$company_list = $Company->getCompany(array($userPK, $company_user['CompanyFk']));
								foreach($company_list as $company){
									if($company_user['CompanyUserFk'] === $userPK){
										$selected = '';
										if($Session->check('user_company_selection_key')){
											$selected = $Session->read('user_company_selection_key') == $company_user['CompanyFk'] ? 'selected':'';
										}
					?>
										<option <?php echo $selected; ?> value="<?php echo $company_user['CompanyFk']; ?>"><?php echo $company['CompanyName']; ?></option>
					<?php										
									}//end of if condition
								}//end of inner foreach loop
							}//end of outer foreach loop
						}//end of if condition
					?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="user-company-selection-button" type="submit" value="Choose" name="choose_company"/>
			</div>
			<div class="selection-error-box error-box float-left hidden" id="user-company-selection-error-box"></div>
		</fieldset>
		<input type="hidden" class="user-company-selection-value" name="user-company-selection-value" value="" />
	</div><!-- end of #user-selection -->	
</form> <!-- end of post form -->
<?php require DOCROOT . '/template/footer.php'; ?>
<script src="<?php echo DOMAIN_NAME; ?>/js/user_company_selection_specific.js"></script>