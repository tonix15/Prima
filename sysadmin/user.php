<?php
$page_name = 'USER';

require_once '../init.php';

//User
if(!$User->isUserLogin()){
	header('Location:' . DOMAIN_NAME . '/index.php');
	exit();
}
else{
	if(!$Session->check('user_company_selection_key')){
		$Session->write('select_company', '<strong>Please Select a Company</strong>');	
		header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
		exit();
	}
	else if($Session->read('user_company_selection_key') <=0 ){
		$Session->write('select_company', '<h2>Please Select a Company</h2>');
		header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
		exit();
	}	
}

$userCredentials = $User->getUserCredentials();
$userPK = $userCredentials['UserPk'];
$user_CompanyFK = $Session->read('user_company_selection_key');

require DOCROOT . '/template/header.php';

$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level =  $BusinessFunctionUserMenu->getRestrictionLevel($userPK, $userPK, $page_name);

$systemUserPK = 0;
$user_data = '';
$user_login = '';
$user_name = '';
$user_password = '';
$user_isActive = '';
$user_isAbsent = '';
$user_isSystemGeneratedPassword = 0;
$TeamFK = 0;
$user_erp_code = NULL;


//Business Function
$BusinessFunction = new BusinessFunction($dbh);
$business_function_data = NULL;
$business_functionPK = 0;
$business_function_isMeterReader = 0;

//Business Function user
$BusinessFunctionUser = new BusinessFunctionUser($dbh);
$BusinessFunctionUserPK = 0;
$BusinessFunctionFK = '';
$BusinessUserFK = -1;
$BusinessUserIsActive = 0;

//Company
$Company = new Company($dbh);
$company_data = NULL;
$CompanyPK = 0;

//Company User
$CompanyUser = new CompanyUser($dbh);
$CompanyUserPK = 0;
$CompanyFK = '';
$CompanyUserFK = -1;
$CompanyIsActive = '';
$company_user_data = '';
$assignments_company_user = 0;

//Team
$Team = new Team($dbh);
$TeamPK = 0;
$Team_name = ' ';
$team_data = NULL;

// UI
$view_class = 'hidden';
$submit_button = '';
$add_submit_id = '';
$submit_button_class = '';
$team_tab_toggle = 'hidden';
$business_function_isActive = 'checked';
$company_isActive = 'checked';
$erpcode_tab_toggle = 'hidden';

if(isset($_GET['View'])){
	$user_userPK = Sanitize::cleanWholeNumber($_GET['choose_user']);
	
	//User data
	$user_data = $User->getUser(array($userPK, $user_userPK, $user_CompanyFK));
	$user_data = $User->getSingleRecord($user_data);	
	$user_login = $user_data['UserEmail'];
	$user_name = $user_data['DisplayName'];
	$user_password = $user_data['Password'];	
	$user_isActive = $user_data['IsActive'] == 1 ? 'checked':'';
	$user_isAbsent = $user_data['IsAbsent'] == 1 ? 'checked':'';
	$TeamFK = $user_data['TeamFk'];
	$user_erp_code = $user_data['ERPCode'];	
	
	//business function
	$business_function_data = $BusinessFunction->getBusinessFunction(array($userPK, 0));
	$business_function_user_data = $BusinessFunctionUser->getBusinessFunctionUser(array($userPK, 0));		
	
	//Company User
	$company_user_data = $CompanyUser->getCompanyUser(array($userPK, 0));		
	
	//UI
	$view_class = 'show';
	$submit_button = 'Update';
	$add_submit_id = '';
	$submit_button_class = '';	
	$team_tab_toggle = $TeamFK > 0 ? 'show':'hidden';
	$erpcode_tab_toggle = !empty($user_erp_code) ? 'show':'hidden'; 
}

else if(isset($_GET['Create'])){
	//UI
	$view_class = 'show';
	$submit_button = 'Create';
	$submit_button_class = 'user_submit';
	$add_submit_id = 'id="user-submit-buttons"';	
	
	//Make User Active by Default
	$user_isActive = 'checked';
}

if(isset($_POST['Create'])) { 
	$user_login = Sanitize::cleanEmail($_POST['user_login']);
	$user_name = Sanitize::sanitizeStr($_POST['user_name']);
	$user_password_plaintext = Sanitize::sanitizeStr($_POST['password']);	
	$user_password = Bcrypt::hash($user_password_plaintext, 32);
	$user_isActive = $_POST['user_isActive_value'];
	$user_isAbsent = $_POST['user_isAbsent_value'];	
	$TeamFK = !empty($_POST['user_team']) ? $_POST['user_team'] : -1;
	$user_erp_code = !empty($_POST['user_ERP_code']) ? Sanitize::sanitizeStr($_POST['user_ERP_code']) : NULL;
		
	$user_params = array(
		$userPK,
		$systemUserPK,
		$user_name,
		$user_password,
		$user_login,
		$user_erp_code,
		$user_isActive,
		$user_isAbsent,
		$user_isSystemGeneratedPassword,
		$TeamFK
	);
	//Get id of newly created user
	$UserLastInsertedId = $User->createUser($user_params);
	
	//Business Function
	$BusinessFunctionFK = $_POST['choose_business_function'];
	$BusinessUserIsActive = Sanitize::cleanWholeNumber($_POST['business_function_isActive_value']);
	
	$BusinessUserFK = $BusinessFunctionUserPK = $UserLastInsertedId;
	
	$business_function_user_params = array(
		$userPK,
		$BusinessFunctionUserPK,
		$BusinessFunctionFK,
		$BusinessUserFK,
		$BusinessUserIsActive
	);
	
	$BusinessFunctionUserLastInsertedId = $BusinessFunctionUser->createBusinessFunctionUser($business_function_user_params);
	
	//Company
	$CompanyFK = $_POST['choose_company'];
	$CompanyIsActive = $_POST['company_isActive_value'];
	
	$company_entry_count = count($CompanyFK);
	
	$CompanyUserPK = 0;
	$CompanyUserFK = $UserLastInsertedId;
	
	$CompanyUserLastInsertedId = 0;
	
	for($i = 0; $i < $company_entry_count; $i++){
		$company_user_params = array(
			$userPK,
			$CompanyUserPK,
			Sanitize::cleanWholeNumber($CompanyFK[$i]),
			$CompanyUserFK,
			Sanitize::cleanWholeNumber($CompanyIsActive[$i])
		);
		$CompanyUserLastInsertedId = $CompanyUser->createCompanyUser($company_user_params);
	}		
	
	if((!empty($UserLastInsertedId) || $UserLastInsertedId > 0)){
		$Session->write('Success', '<strong>' . $user_login . '</strong> created successfully.');
		header('Location:' . DOMAIN_NAME .  Sanitize::sanitizeStr($_SERVER['PHP_SELF']));
		exit();
	}
}

if(isset($_POST['Update'])) { 
	$user_userPK = Sanitize::cleanWholeNumber($_GET['choose_user']);	
	
	$user_data = $User->getUser(array($userPK, $user_userPK, $user_CompanyFK));
	$user_data = $User->getSingleRecord($user_data);	
		
	$systemUserPK = $user_data['UserPk'];
	
	$user_login = Sanitize::cleanEmail($_POST['user_login']);	
	
	$user_password_plaintext = NULL;
	$user_password = NULL;
	if(!empty($_POST['password'])){
		$user_password_plaintext = Sanitize::sanitizeStr($_POST['password']);
		$user_password = Bcrypt::hash($user_password_plaintext, 32);
	}
	else{ $user_password = $user_data['Password']; }
	
	$user_name = Sanitize::sanitizeStr($_POST['user_name']);
	
	$user_isActive = $_POST['user_isActive_value'];
	$user_isAbsent = $_POST['user_isAbsent_value'];	
	$user_isSystemGeneratedPassword = 0;		
	
	$TeamFK = $_POST['user_team'];
	
	$user_erp_code = Sanitize::sanitizeStr($_POST['user_ERP_code']);
	
	//Update User Details Block
	$user_params = array(
		$userPK,
		$systemUserPK,
		$user_name,
		$user_password,
		$user_login,
		$user_erp_code,
		$user_isActive,
		$user_isAbsent,
		$user_isSystemGeneratedPassword,
		$TeamFK
	);	
	$updateStatus = $User->updateUser($user_params);
		
	//Update Assignments Block
	
	//Business Function	
	$business_function_menu_array = explode('|',$_POST['choose_business_function']);
	
	$BusinessFunctionUserPK = $business_function_menu_array[1];
	$BusinessFunctionFK = $business_function_menu_array[0];
	$BusinessUserFK = $user_userPK;
	
	$BusinessUserIsActive = $_POST['business_function_isActive_value'];
	
	$business_function_user_params = array(
		$userPK,
		$BusinessFunctionUserPK,
		$BusinessFunctionFK,
		$BusinessUserFK,
		$BusinessUserIsActive
	);
	$business_function_user_status = $BusinessFunctionUser->updateBusinessFunctionUser($business_function_user_params);
	
	//Company 
	$CompanyUser_array = $_POST['choose_company'];
	$CompanyUserFK = $user_userPK;
	$CompanyIsActive = $_POST['company_isActive_value'];
	
	$company_user_count = count($CompanyUser_array);
	$company_assigned_count = $_POST['assignments_company_user'];
	
	$company_user_update_status = '';
	
	for($i = 0; $i < $company_user_count; $i++){
		$company_user_array_explode = explode('|', $CompanyUser_array[$i]);		
		
		if(($i+1) > $company_assigned_count){ $CompanyUserPK = 0; }
		else{ $CompanyUserPK = $company_user_array_explode[1]; }
		
		$company_user_params = array(
			$userPK,
			$CompanyUserPK,
			$CompanyFK = $company_user_array_explode[0],
			$CompanyUserFK,
			$CompanyIsActive[$i]
		);
		$company_user_update_status = $CompanyUser->updateCompanyUser($company_user_params);
	}
	
	if(!empty($updateStatus) || !empty($business_function_user_status) || !empty($company_user_update_status)){
		$Session->write('Success', '<strong>' . $user_login . '</strong> updated successfully.');
		header('Location:' . DOMAIN_NAME .  Sanitize::sanitizeStr($_SERVER['PHP_SELF']));		
		exit();		
	}
}

else if (isset($_POST['Cancel'])) {
	header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));
	exit();
}
?>

<form method="get" class="hover-cursor-pointer" id="company-selection-form">
	<?php
		if($Session->check('Success')){ 
			echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
			$Session->sessionUnset('Success');
		}
	?>
	<div class="sub-menu-title">
		<h1>User</h1>
	</div><!-- end of .sub-menu-title -->
	<div id="user-selection" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>User Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>User login:</label></li>
				<li>  
					<select id="user-selection-user" name="choose_user" class="selection-required-input">
						<option value="0">Please choose a user</option>
					<?php 
						$user_list = $User->getUser(array($userPK, 0, $user_CompanyFK));
						foreach ($user_list as $user) { 
						$selected = $user['UserPk'] === $userPK ? 'selected="' . $userPK . '"':'';
						
					?>
						<option <?php echo $selected; ?> value="<?php echo $user['UserPk']; ?>"><?php echo $user['UserEmail']; ?></option>
					<?php } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="user-view-submit" type="submit" value="View" name="View"/>
				<?php if($restriction_level > 0){ ?>				
				<input type="submit" value="Create" name="Create"/>
				<?php } ?>
			</div>
			<div id="user-selection-error-box" class="selection-error-box error-box float-left hidden"></div>			
		</fieldset>
	</div><!-- end of #user-selection -->	
</form> <!-- end of post form -->

<form id="user-data-form" method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
	<div id="user-detail" class="wrapper-fieldset-forms">
		<fieldset id="user-fieldset-user" class="fieldset-forms">
			<legend>User Detail</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>User login/E-mail:</label></li>
				<li><input type="text" maxlength="100" class="user_login" name="user_login" value="<?php echo $user_login; ?>" /></li>
				<li><label>User Name:</label></li>
				<li><input type="text" maxlength="100" class="user_name" name="user_name" value="<?php echo $user_name; ?>" /></li>
				<li><label>Password:</label></li>
				<li><input type="password" maxlength="100" class="user_password" name="password" placeholder="Password" /></li>
				<li><label>Active:</label></li>
				<li>
					<input type="checkbox" name="isActive" <?php echo $user_isActive; ?> class="isActive" />
					<input type="hidden" name="user_isActive_value" value="1" />
				</li>
				<li><label>Absent:</label></li>
				<li>
					<input type="checkbox" name="isAbsent" <?php echo $user_isAbsent; ?> class="isAbsent" />
					<input type="hidden" name="user_isAbsent_value" value="<?php echo strcasecmp($user_isAbsent, 'checked') == 0 ? 1:0; ?>" />
				</li>
			</ul>
		</fieldset>
	</div><!-- end of #user-data-form -->
	
	<div id="user-assign-business-function-company" class="wrapper-fieldset-forms hover-cursor-pointer">
		<fieldset class="fieldset-forms">
			<legend>Assignments</legend>
			<div class="tab-container"> 
				<ul>
					<li class="tabs"><a href="#business-functions" class="current-tab">Business functions</a></li>
					<li class="tabs"><a href="#assign-company">Company</a></li>
					<li class="tabs <?php echo $team_tab_toggle; ?>"><a href="#assign-teams">Teams</a></li>
					<li class="tabs <?php echo $erpcode_tab_toggle; ?>"><a href="#assign-ERP-Code">ERP Code</a></li>
				</ul>
			</div><!-- end of .tab-container -->
			<div class="tab-contents-container">
				<!-- Business Functions -->
				<div class="tab-contents" id="business-functions">
					<ul class="fieldset-forms-li-2-cols">
						<li><label>Business functions:</label></li>
						<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Active:</label></li>
					</ul>
					<?php
						if(!empty($business_function_user_data)){
							foreach($business_function_user_data as $business_function_user){
								if($business_function_user['BusinessUserFk'] === $user_userPK){
					?>
						<ul class="fieldset-forms-li-2-cols">
							<li>
								<select name="choose_business_function" class="assignment_business_function_selection">
									<option value="0">Please choose a business function</option>
									<?php 
										$business_function_list = $BusinessFunction->getBusinessFunction(array($userPK, 0));										
										foreach ($business_function_list as $business_function) { 
											$selected = $business_function['BusinessFunctionPk'] === $business_function_user['BusinessFunctionFk'] ? 'selected':'';
									?>
									<option <?php echo $selected; ?> value="<?php echo $business_function['BusinessFunctionPk']; ?>|<?php echo $business_function_user['BusinessFunctionUserPk']; ?>"><?php echo $business_function['Value']; ?></option>
									<?php } ?>
								</select>
							</li>
							<li>
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="business_function_isActive_temp" value="0" <?php echo $checked = $business_function_user['IsActive'] == 1 ? 'checked':''; ?> />
								<input type="hidden" name="business_function_isActive_value" value="<?php echo $business_function_user['IsActive']; ?>" />
							</li>
						</ul>
					<?php
									break;
								}//end of if condition
							}//end of foreach loop
						}//end of if condition
						else{
					?>
							<ul class="fieldset-forms-li-2-cols">
								<li>
									<select name="choose_business_function" class="assignment_business_function_selection">
										<option value="0">Please choose a business function</option>
										<?php 
											$business_function_list = $BusinessFunction->getBusinessFunction(array($userPK, 0));
											foreach ($business_function_list as $business_function) {
										?>
										<option value="<?php echo $business_function['BusinessFunctionPk']; ?>"><?php echo $business_function['Value']; ?></option>
										<?php } ?>
									</select>
								</li>
								<li>
									&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="business_function_isActive_temp" <?php echo $business_function_isActive; ?> />
									<input type="hidden" name="business_function_isActive_value" value="0" />
								</li>
							</ul>							
					<?php }//end of else ?>
					<input type="hidden" class="business_function_isMeterReader" name="business_function_isMeterReader" value="0" />
				</div><!-- end of #business-functions -->
				
				<!-- assign-company -->
				<div class="tab-contents" id="assign-company">
					<ul class="fieldset-forms-li-2-cols">
						<li><label>Company</label></li>
						<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Active:</label></li>
					</ul>
					<?php
						if(!empty($company_user_data)){
							foreach($company_user_data as $company_user){
								if($company_user['CompanyUserFk'] === $user_userPK){
									$assignments_company_user++;
					?>
						<ul class="fieldset-forms-li-2-cols">
							<li>
								<select name="choose_company[]" class="assignment_company_selection">
									<option value="0">Please choose a company</option>
									<?php 
										$company_list = $Company->getCompany(array($userPK, 0));
										foreach ($company_list as $company) { 
											$selected = $company['CompanyPk'] === $company_user['CompanyFk'] ? 'selected':'';
									?>
									<option <?php echo $selected; ?> value="<?php echo $company['CompanyPk']; ?>|<?php echo $company_user['CompanyUserPk']; ?>"><?php echo $company['CompanyName']; ?></option>
									<?php } ?>
								</select>
							</li>
							<li>
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="company_isActive_temp[]" <?php echo $CompanyIsActive = $company_user['IsActive'] == 1 ? 'checked':''; ?> />
								<input type="hidden" name="company_isActive_value[]" value="<?php echo $company_user['IsActive']; ?>" />
							</li>
						</ul>
					<?php
								}//end of if condition										
							}//end of foreach loop
						}//end of if condition
						else{
					?>
						<ul class="fieldset-forms-li-2-cols">
							<li>
								<select name="choose_company[]" class="assignment_company_selection">
									<option value="0">Please choose a company</option>
									<?php 
										$company_list = $Company->getCompany(array($userPK, 0));
										foreach ($company_list as $company) { 
									?>
									<option value="<?php echo $company['CompanyPk']; ?>"><?php echo $company['CompanyName']; ?></option>
									<?php } ?>
								</select>
							</li>
							<li>
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="company_isActive_temp[]" <?php echo $company_isActive; ?> />
								<input type="hidden" name="company_isActive_value[]" value="0" />
							</li>
						</ul>						
					<?php }//end of else ?>
					<div class="clear">
						<button class="user-assign-company-assign-menu-add-button">Add</button> 				
					</div>
					<input type="hidden" value="<?php echo $assignments_company_user; ?>" name="assignments_company_user" />
				</div><!-- end of #assign-company -->
				
				<!-- assign team -->
				<?php if($TeamFK > 0){ ?>
						<div class="tab-contents" id="assign-teams">
							<ul class="fieldset-forms-li-2-cols">
								<li><label>Teams:</label></li>														
								<li>
									<select name="choose_team" class="assignment_team_selection">
										<option value="0">Please choose a team</option>
										<?php
											$team_data = $Team->getTeam(array($userPK, 0));
											foreach($team_data as $team){
												$selected = $TeamFK == $team['TeamPk'] ? 'selected':'';
										?>
												<option <?php echo $selected; ?> value="<?php echo $team['TeamPk']; ?>"><?php echo $team['Value']; ?></option>
										<?php
											}//end of foreach loop
										?>								
									</select>
								</li>
							</ul>							
						</div><!-- end of #assign-teams-->				
				<?php
					}//end of if condition
					else{
				?>	
						<div class="tab-contents" id="assign-teams">
							<ul class="fieldset-forms-li-2-cols">
								<li><label>Teams:</label></li>	
								<li>
									<select name="choose_team" class="assignment_team_selection">
										<option value="-1">Please choose a team</option>
										<?php
											$team_data = $Team->getTeam(array($userPK, 0));
											foreach($team_data as $team){												
										?>
												<option value="<?php echo $team['TeamPk']; ?>"><?php echo $team['Value']; ?></option>
										<?php
											}//end of foreach loop
										?>								
									</select>
								</li>
							</ul>							
						</div><!-- end of #assign-teams-->
				<?php
					}//end of else statement
				?>	
				
				<!-- assign ERP Code -->
				<div class="tab-contents" id="assign-ERP-Code">
					<ul class="fieldset-forms-li-2-cols">
						<li><label>ERP Code:</label></li>						
						<li>
							<input type="text" name="user_ERP_code" value="<?php echo $user_erp_code; ?>" />
							<input type="hidden" name="user_erp_code" value="<?php echo $user_erp_code; ?>" />
						</li>
					</ul>					
				</div><!-- end of #assign-ERP-Code -->
			</div><!-- end of .tab-contents-container -->
		</fieldset>
		<input type="hidden" name="user_team" class="user_team" value="<?php echo $TeamFK; ?>" />
	</div><!-- end of #user-assign-business-function-company -->
	
	<div <?php echo $add_submit_id; ?> class="wrapper-fieldset-forms hover-cursor-pointer">
		<div id="user-submit-error-box" class="warning-box warning hidden"></div>
		<?php if($restriction_level > 0){ ?>
		<div class="form-submit">
			<input type="submit" value="<?php echo $submit_button; ?>" class="submit-positive <?php echo $submit_button_class; ?>" name="<?php echo $submit_button; ?>" />
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel" />
		</div>
		<?php } ?>
	</div>
</form><!-- end of form -->

<?php
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.placeholder.sniffer.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/tab-script.js"></script>