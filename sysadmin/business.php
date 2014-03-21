<?php
$page_name = 'BUSINESS FUNCTION';

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

//Restriction Level = 1; Read, Write and Update
//Restriction Level = 0; Read Only
$restriction_level = $dbhandler->getRestrictionLevel($page_name);

//Business Function
$BusinessFunction = new BusinessFunction($dbh);
$business_function_data = NULL;
$business_functionPK = 0;
$business_function_value = '';
$business_function_isActive_checkbox = 1;//Set to 1 to make isActive Checkbox Checked by default
$business_function_isActive = '';
$business_function_isPortfolioManager = '';
$business_function_isMeterReader = '';

//BusinessFunctionMenu
$Business_function_menu = new BusinessFunctionMenu($dbh);

$business_function_menu_data = $Business_function_menu->getBusinessFunctionMenu(array($userPK, 0));	

$business_function_menuPK = 0;
$business_functionFK = '';
$business_function_menu_value = '';
$business_function_isWritable = '';
$business_function_isActive = '';

//Business Function User Menu
$Business_function_user_menu = new BusinessFunctionUserMenu($dbh);
$Business_function_user_menu_list = '';
$Business_function_user_menu_list_count = 0;

//Menu
$Menu = new Menu($dbh);
$menuPK = 0;
$menu_isActive = '';
$menu_isReadWrite = '';

$test = $Business_function_menu->getBusinessFunctionMenu(array($userPK, 0));
$menu_list = $Menu->getMenu(array($userPK, 0));

// UI
$view_class = 'hidden';
$submit_button = '';
$add_submit_id = '';

//GET VIEW
if(isset($_GET['View'])){ 
	//UI
	$view_class = 'show';
	$submit_button = 'Update';
	$add_submit_id = '';
	
	$business_functionPK = Sanitize::cleanWholeNumber($_GET['choose_business_function']);
		
	//Business Function
	$business_function_data = $BusinessFunction->getBusinessFunction(array($userPK, $business_functionPK));
	$business_function_data = $BusinessFunction->getSingleRecord($business_function_data);
		
	$business_function_value = $business_function_data['Value'];
	$business_function_isActive = $business_function_data['IsActive'] == 1 ? 'checked':'';
	$business_function_isPortfolioManager = $business_function_data['IsPortfolioManager'] == 1 ? 'checked':'';	
	$business_function_isMeterReader = $business_function_data['IsMeterReader'] == 1 ? 'checked':'';
	
	//Business Function Menu	
	$business_function_menu_data = $Business_function_menu->getBusinessFunctionMenu(array($userPK, 0));	
}

//GET CREATE
else if(isset($_GET['Create'])){
	//UI
	$view_class = 'show';
	$submit_button = 'Create';
	$add_submit_id = 'id="business-function-submit-buttons"';
	$business_function_menu_data = '';
	
	$business_function_isActive = 'checked';
	$menu_isActive = 'checked';
	$menu_isReadWrite = '';
	$business_function_isMeterReader = '';
}

//POST CREATE
if(isset($_POST['Create'])){	
	$business_function_value = Sanitize::sanitizeStr($_POST['business_function']);		
	
	if(!isset($_POST['business-function_isActive'])){ $business_function_isActive = 0; }
	else{ $business_function_isActive = 1; }
	
	if(!isset($_POST['business-function_isPortfolio_manager'])){ $business_function_isPortfolioManager = 0; }
	else{ $business_function_isPortfolioManager = 1; }	
	
	if(!isset($_POST['business-function_isMeter_Reader'])){ $business_function_isMeterReader = 0; }	
	else{ $business_function_isMeterReader = 1; }
	
	//Business Function Params Array
	$business_functions_params = array(
		$userPK,
		0,
		$business_function_value,
		$business_function_isActive,
		$business_function_isPortfolioManager,
		$business_function_isMeterReader
	);	
	$lastInsertedId = $BusinessFunction->createBusinessFunction($business_functions_params);//Pass last inserted id to $lastInsertedId variable
	
	//Business Function Menu
	$menuFK = $_POST['business_function_menu'];
	$business_function_isWritable = $_POST['isReadWrite_values'];	
	$business_function_isActive1 = $_POST['assign_menu_isActive_values'];
	
	//get last inserted id of Business Function and pass as foreign key id to Business Function Menu
	$business_functionFK = $lastInsertedId;	
	$assign_menus_len = count($menuFK);	//count number of selected assigned menus	
	$business_function_menu_last_inserted_id = 0;
		
	for($count = 0; $count < $assign_menus_len; $count++){
		$business_function_menu_params = array(		
			$userPK,
			0,
			$business_functionFK,
			$menuFK[$count],
			$business_function_isWritable[$count],
			$business_function_isActive1[$count]			
		);	
		//get last inserted id for Business Function Menu
		$business_function_menu_last_inserted_id = $Business_function_menu->createBusinessFunctionMenu($business_function_menu_params);
	}
	
	if((!empty($lastInsertedId) || $lastInsertedId > 0) && (!empty($business_function_menu_last_inserted_id) || $business_function_menu_last_inserted_id > 0)){
		$Session->write('Success', '<strong>' . $business_function_value . '</strong> created successfully.');
		header('Location:' . DOMAIN_NAME . $_SERVER['PHP_SELF']);
		exit();
	}	
}

//POST UPDATE
else if(isset($_POST['Update'])){
	$business_functionPK = Sanitize::cleanWholeNumber($_GET['choose_business_function']);
		
	//Business Function
	$business_function_data = $BusinessFunction->getBusinessFunction(array($userPK, $business_functionPK));
	$business_function_data = $BusinessFunction->getSingleRecord($business_function_data);
	
	$business_function_value = $_POST['business_function'];
	if(!isset($_POST['business-function_isActive'])){ $business_function_isActive = 0; }
	else{ $business_function_isActive = 1; }
	
	if(!isset($_POST['business-function_isPortfolio_manager'])){ $business_function_isPortfolioManager = 0; }
	else{ $business_function_isPortfolioManager = 1; }
	
	if(!isset($_POST['business-function_isMeter_Reader'])){ $business_function_isMeterReader = 0; }	
	else{ $business_function_isMeterReader = 1; }
	
	$business_functions_params = array(
		$userPK,
		$business_functionPK,
		$business_function_value,
		$business_function_isActive,
		$business_function_isPortfolioManager,
		$business_function_isMeterReader
	);
	
	$updateStatus = $BusinessFunction->updateBusinessFunction($business_functions_params);
	
	//Business Function Menu
	$business_function_menuPK_array = $_POST['business_function_menu'];
	$business_functionFK = $business_functionPK;
	$menuFK = '';
	$business_function_isWritable = $_POST['isReadWrite_values'];	
	$business_function_isActive1 = $_POST['assign_menu_isActive_values'];
	
	$business_function_user_num_menu_before_update = Sanitize::cleanWholeNumber($_POST['business_function_user_count']);
	$assign_menu_count = count($business_function_menuPK_array);
	$business_function_menu_updateStatus = '';
	
	for($i = 0; $i < $assign_menu_count; $i++){
		$business_function_menuPK_menuFK_explode = explode('|',$business_function_menuPK_array[$i]);				
		
		if(($i+1) > $business_function_user_num_menu_before_update){ $business_function_menuPK = 0; }
		else{ $business_function_menuPK = $business_function_menuPK_menuFK_explode[1]; }		
		
		$menu_list = $Menu->getMenu(array($userPK, $business_function_menuPK_menuFK_explode[1]));
		$menu_list = $Menu->getSingleRecord($menu_list);
		$menuFK = $menu_list['MenuPk'];
		
		$business_function_menu_params = array(
			$userPK,
			$business_function_menuPK,
			$business_functionFK,
			$menuFK = $business_function_menuPK_menuFK_explode[0],
			$business_function_isWritable[$i],
			$business_function_isActive1[$i]
		);		
		$business_function_menu_updateStatus = $Business_function_menu->updateBusinessFunctionMenu($business_function_menu_params);
	}
	
	if(!empty($updateStatus) || !empty($business_function_menu_updateStatus)){
		$Session->write('Success', '<strong>' . $business_function_value . '</strong> updated successfully.');
		header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));
		exit();
	}
}

//POST CANCEL
else if (isset($_POST['Cancel'])) { 
	header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));
	exit();
}
require DOCROOT . '/template/header.php';
?>

<form method="get" class="hover-cursor-pointer" id="company-selection-form">
	<?php
		if($Session->check('Success')){ 
			echo '<div class="warning insert-success">' . $Session->read('Success') . '</div>';
			$Session->sessionUnset('Success');
		}
	?>
	<div class="sub-menu-title">
		<h1>Business Function</h1>
	</div><!-- end of .sub-menu-title -->
	<div id="business-function-selection" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>Business Function Selection</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Business function:</label></li>
				<li>  
					<select id="business-function-selection-business-function" name="choose_business_function" class="selection-required-input">
						<option value="0">Please choose a business function</option>
						<?php 
							$business_function_list = $BusinessFunction->getBusinessFunction(array($userPK, 0));
							foreach ($business_function_list as $business_function) { 
							$selected = $business_function['BusinessFunctionPk'] === $business_functionPK ? 'selected="' . $business_functionPK . '"':'';
						?>
							<option <?php echo $selected; ?> value="<?php echo $business_function['BusinessFunctionPk']; ?>"><?php echo $business_function['Value']; ?></option>
						<?php } ?>
					</select>
				</li>
			</ul>
			<div class="selection-form-submit float-left">
				<input id="business-function-view-submit" type="submit" value="View" name="View"/>
				<?php if($restriction_level > 0){ ?>
				<input type="submit" value="Create" name="Create"/>
				<?php } ?>
			</div>
			<div id="business-function-selection-error-box" class="selection-error-box error-box float-left hidden"></div>
			
		</fieldset>
	</div><!-- end of #business-function-selection -->	
</form> <!-- end of post form -->

<form id="business-function-data-form" method="post" class="hover-cursor-pointer <?php echo $view_class; ?>">
	<div id="business-function" class="wrapper-fieldset-forms">
		<fieldset id="business-function-fieldset-business-function" class="fieldset-forms">
			<legend>Business Function</legend>
			<ul class="fieldset-forms-li-2-cols">
				<li><label>Business function:</label></li>
				<li><input type="text" maxlength="100" id="business_function_value" name="business_function" value="<?php echo $business_function_value; ?>" /></li>
				<li><label>Active:</label></li>
				<li><input type="checkbox" name="business-function_isActive" <?php echo $business_function_isActive; ?> /></li>
				<li><label>Portfolio manager:</label></li>
				<li><input type="checkbox" name="business-function_isPortfolio_manager" <?php echo $business_function_isPortfolioManager; ?> /></li>
				<li><label>Meter Reader:</label></li>
				<li><input type="checkbox" name="business-function_isMeter_Reader" <?php echo $business_function_isMeterReader; ?> /></li>
			</ul>
		</fieldset>
	</div><!-- end of #business-function -->
	
	<div id="business-function-assign-menus" class="wrapper-fieldset-forms">
		<fieldset class="fieldset-forms">
			<legend>Assign menus</legend>
			<div class="show">
				<ul class="fieldset-forms-li-4-cols">
					<li><label>Menu:</label></li>
					<li class="center-li-contents"><label>&nbsp;</label></li>
					<li class="center-li-contents"><label>Read/Write:</label></li>
					<li class="center-li-contents"><label>Active:</label></li>
				</ul>
				<div id="business-function-assign-menus-form-container">
					<?php 
						if(!empty($business_function_menu_data)) {
							foreach($business_function_menu_data as $Business_function_menu){ 
								if($business_function_data['BusinessFunctionPk'] == $Business_function_menu['BusinessFunctionFk']){
									$Business_function_user_menu_list_count++;
					?>					
									<ul class="fieldset-forms-li-4-cols">
										<li class="extended-width">
											<select name="business_function_menu[]" class="business_function_menu">
												<option value="0">Please choose a menu</option>
												<?php 
													$menu_list = $Menu->getMenu(array($userPK, 0));
													foreach ($menu_list as $menu) { 
														$selected = $menu['MenuPk'] === $Business_function_menu['MenuFk'] ? 'selected':'';
												?>
												<option <?php echo $selected; ?> value="<?php echo $menu['MenuPk']; ?>|<?php echo $Business_function_menu['BusinessFunctionMenuPk']; ?>"><?php echo $menu['Value']; ?></option>
												<?php } ?>
											</select>
										</li>
										<li>
											<input type="checkbox" name="isReadWrite_temp[]" class="menu_isReadWrite checkbox-float-right" <?php echo $menu_isReadWrite = $Business_function_menu['IsWritable'] == 1 ? 'checked':''; ?> />
											<input type="hidden" class="isReadWrite_values" name="isReadWrite_values[]" value="<?php echo $Business_function_menu['IsWritable']; ?>" />
										</li>
										<li>
											&nbsp;&nbsp;&nbsp;<input type="checkbox" name="assign_menu_isActive_temp[]" class="menu_isActive checkbox-float-right" <?php echo $menu_isActive = $Business_function_menu['IsActive'] == 1 ? 'checked':''; ?> />
											<input type="hidden" name="assign_menu_isActive_values[]" value="<?php echo $Business_function_menu['IsActive']; ?>" />
										</li>
									</ul>
					<?php
								}//end of if condition
							}//end of foreach loop					
					    }//end of if statement 
					    else{ 
					?>
							<ul class="fieldset-forms-li-4-cols">
								<li class="extended-width">
									<select name="business_function_menu[]" class="business_function_menu">
										<option value="0">Please choose a menu</option>
										<?php 
											$menu_list = $Menu->getMenu(array($userPK, 0));
											foreach ($menu_list as $menu) { 
										?>
										<option value="<?php echo $menu['MenuPk']; ?>"><?php echo $menu['Value']; ?></option>
										<?php } ?>
									</select>
								</li>
								<li>
									<input type="checkbox" name="isReadWrite_temp[]" class="menu_isReadWrite checkbox-float-right" <?php echo $menu_isReadWrite; ?> />
									<input type="hidden" class="isReadWrite_values" name="isReadWrite_values[]" value="0" />
								</li>
								<li>
									&nbsp;&nbsp;&nbsp;<input type="checkbox" name="assign_menu_isActive_temp[]" class="menu_isActive checkbox-float-right" <?php echo $menu_isActive; ?> />
									<input type="hidden" name="assign_menu_isActive_values[]" value="1" />
								</li>
							</ul>
					<?php 
					    }//end of else
					?>
				</div>
				<div class="clear">
					<button class="business-function-assign-menu-add-button" id="business-function-assign-menu-add-button">Add</button> 
				</div>
				<input type="hidden" value="<?php echo $Business_function_user_menu_list_count; ?>" name="business_function_user_count" />
			</div>	
		</fieldset>
	</div><!-- end of #business-function-assign-menus -->
	
	<div <?php echo $add_submit_id; ?> class="wrapper-fieldset-forms hover-cursor-pointer <?php echo $view_class; ?>">
		<div class="warning warning-box hidden"></div>
		<?php if($restriction_level > 0){ ?>
		<div class="form-submit">
			<input type="submit" value="<?php echo $submit_button; ?>" class="submit-positive" name="<?php echo $submit_button; ?>" />
			<input type="submit" value="Cancel" class="submit-netagive" name="Cancel" />
		</div>
		<?php } ?>
	</div>
</form><!-- end of #company-data-form -->
<?php
require DOCROOT . '/template/footer.php';
?>