<?php 
	$menu_PK = array();
	$menu_VALUE = array();
	$counter = 0;
	
	$path_constants = array(
		//Maintenance menu
		'MAINTENANCE_PROVIDERS' => 	DOMAIN_NAME . '/maintenance/providers.php',
		'MAINTENANCE_RATES' => DOMAIN_NAME . '/maintenance/rates.php',
		'MAINTENANCE_BUILDINGS' => DOMAIN_NAME . '/maintenance/buildings.php',
		'MAINTENANCE_UNITS' => DOMAIN_NAME . '/maintenance/units.php',
		'MAINTENANCE_CUSTOMERS' => DOMAIN_NAME . '/maintenance/customers.php',
		'MAINTENANCE_METERS' => DOMAIN_NAME . '/maintenance/meters.php',
		'MAINTENANCE_PARAMETERS' => DOMAIN_NAME . '/maintenance/parameters.php',
		//Processing Menu
		'PROCESSING_PLANNING' => DOMAIN_NAME . '/processing/planning.php',
		'PROCESSING_REASONABILITY' => DOMAIN_NAME . '/processing/reasonability.php',
		'PROCESSING_INVOICING' => DOMAIN_NAME . '/processing/invoicing.php',
		'PROCESSING_IMPORT_PREPAID_TRANSACTIONS' => DOMAIN_NAME . '/processing/import/prepaid_transactions.php',
		//Credit Management		
		'CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_ACCOUNTS' => DOMAIN_NAME . '/credit_management/Cut_Notification/overdue_accounts.php',
		'CREDIT_MANAGEMENT_CUT_NOTIFICATION_OUTSTANDING_AGREEMENTS' => '#',
		'CREDIT_MANAGEMENT_CUT_INSTRUCTION' => DOMAIN_NAME . '/credit_management/cut_instruction.php',
		'CREDIT_MANAGEMENT_RECONNECTION' => DOMAIN_NAME . '/credit_management/reconnection.php',
		'CREDIT_MANAGEMENT_DEPOSIT_REVIEW' => '#',
		/** Reporting */
		//Providers
		'REPORTING_PROVIDERS' => '#',
		//Rate
		'REPORTING_RATE_RATE_REPORT' => '#',
		//Buildings
		'REPORTING_BUILDING_BUILDING_RATE_LIST' => DOMAIN_NAME . '/reporting/Buildings/building_rate_list.php',
		'REPORTING_BUILDING_SQM_METER_ALLOCATION_LIST' => DOMAIN_NAME . '/reporting/Buildings/sqm_meter_allocation_list.php',
		'REPORTING_BUILDING_PORTFOLIO_MANAGER_LIST' => DOMAIN_NAME . '/reporting/Buildings/portfolio_manager_list.php',
		//Units
		'REPORTING_UNITS' => '#',
		//Customers
		'REPORTING_CUSTOMERS_DEPOSIT_LIST' => DOMAIN_NAME . '/reporting/Customers/deposit_list.php',
		'REPORTING_CUSTOMERS_ACCOUNT_DETAIL' => DOMAIN_NAME . '/reporting/Customers/account_detail.php',
		'REPORTING_CUSTOMERS_INVOICE_DETAIL' => DOMAIN_NAME . '/reporting/Customers/invoice_detail.php',
		'REPORTING_CUSTOMERS_ACCOUNTS_WITHOUT_CONTACT_DETAILS' => DOMAIN_NAME . '/reporting/Customers/accounts_without_contact_details.php',
		'REPORTING_CUSTOMERS_OUTSTANDING_AGREEMENT_LIST' => DOMAIN_NAME . '/reporting/Customers/outstanding_agreement_list.php',
		//Meters
		'REPORTING_METERS_READING_IMPORTS_REVIEW_READINGS_IMPORTED' => DOMAIN_NAME . '/reporting/Meters/Reading_Imports_Review/readings_imported.php',
		'REPORTING_METERS_READING_IMPORTS_REVIEW_METER_TEST_REQUIRED' => DOMAIN_NAME . '/reporting/Meters/Reading_Imports_Review/meter_test_required.php',
		'REPORTING_METERS_READING_IMPORTS_REVIEW_ESTIMATED_READINGS' => DOMAIN_NAME . '/reporting/Meters/Reading_Imports_Review/estimated_readings.php',
		'REPORTING_METERS_READING_IMPORTS_REVIEW_EXCEPTIONAL_READINGS' => DOMAIN_NAME . '/reporting/Meters/Reading_Imports_Review/exceptional_readings.php',
		'REPORTING_METERS_READING_REVIEW' => DOMAIN_NAME . '/reporting/Meters/reading_review.php',
		'REPORTING_METERS_METERS_ESTIMATED_THREE_TIMES' => DOMAIN_NAME . '/reporting/Meters/reading_estimated_three_times.php',
		'REPORTING_METERS_READING_VARIANCE_PARAMETER_ANALYSIS' => DOMAIN_NAME . '/reporting/Meters/reading_variance_parameter_analysis.php',
		'REPORTING_METERS_INTERNAL_PREPAID_METER_LIST' => DOMAIN_NAME . '/reporting/Meters/internal_prepaid_meter_list.php',
		'REPORTING_METERS_INTERNAL_PREPAID_METER_REASONABILITY' => '#',
		// 'QUERY AND REPORTING.METER.IMPORT FILE ANALYSIS
		'REPORTING_METERS_RATE_SPECIFIC_METER_LIST' => DOMAIN_NAME . '/reporting/Meters/rate_specific_meter_list.php',
		'REPORTING_BILLING_OUTSTANDING_BILLING_LIST' => DOMAIN_NAME . '/reporting/Billing/outstanding_billing_list.php',
		'REPORTING_BILLING_OUTSTANDING_BILLING_DETAIL' => DOMAIN_NAME . '/reporting/Billing/outstanding_billing_detail.php',
		'REPORTING_BILLING_COMMON_PROPERTY_ALLOCATION' => DOMAIN_NAME . '/reporting/Billing/common_property_allocation.php',
		'REPORTING_BILLING_PREVIOUS_BILLING' => '#',
		//Credit Management
		'REPORTING_CREDIT_MANAGEMENT_PAYMENT_ARRANGEMENT_LIST' => DOMAIN_NAME . '/reporting/Credit_Management/payment_arrangement_list.php',
		'REPORTING_CREDIT_MANAGEMENT_OVERDUE_ACCOUNT_NOT_NOTIFIED' => DOMAIN_NAME . '/reporting/Credit_Management/overdue_account_not_notified.php',
		'REPORTING_CREDIT_MANAGEMENT_AGREEMENT_OUTSTANDING_NOT_NOTIFIED' => '#',
		'REPORTING_CREDIT_MANAGEMENT_NOTIFIED_ACCOUNTS_NOT_CUT' => DOMAIN_NAME . '/reporting/Credit_Management/notified_accounts_not_cut.php',
		'REPORTING_CREDIT_MANAGEMENT_SETTLED_ACCOUNTS_NOT_RECONNECTED' => '#',
		'REPORTING_CREDIT_MANAGEMENT_STANDBY_CUT_LIST' => DOMAIN_NAME . '/reporting/Credit_Management/standby_cut_list.php',
		/** System Validation */
		'SYSTEM_VALIDATION_PROVIDERS_PROVIDERS_WITH_NO_RATES' => DOMAIN_NAME . '/system_validation/Providers/providers_with_no_rates.php',
		'SYSTEM_VALIDATION_RATES_RATES_WITH_NO_RETAIL_OR_BULK_RATE' => DOMAIN_NAME . '/system_validation/Rates/rates_with_no_retail_or_bulk_rate.php',
		'SYSTEM_VALIDATION_RATES_RATES_WITH_SCALE_ERRORS' => DOMAIN_NAME . '/system_validation/Rates/rates_with_scale_error.php',
		'SYSTEM_VALIDATION_RATES_RATES_WITH_NO_PROVIDER' => DOMAIN_NAME . '/system_validation/Rates/rates_with_no_provider.php',
		'SYSTEM_VALIDATION_RATES_RATES_WITH_NO_BUILDINGS' => DOMAIN_NAME . '/system_validation/Rates/rates_with_no_buildings.php',
		'SYSTEM_VALIDATION_RATES_RATES_WITH_NO_METERS' => DOMAIN_NAME . '/system_validation/Rates/rates_with_no_meters.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_PORTFOLIO_MANAGER' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_no_portfolio_manager.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_RATES' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_no_rates.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_INACTIVE_RATES' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_inactive_rates.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_RATE_UTILITY_MISMATCH' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_rate_utility_mismatch.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_UNITS' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_no_units.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_METERS' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_no_meters.php',
		'SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_BULK_METERS' => DOMAIN_NAME . '/system_validation/Buildings/buildings_with_no_bulk_meters.php',
		'SYSTEM_VALIDATION_UNITS_BODY_CORPORATE_UNIT_WITH_SUB_METERS' => DOMAIN_NAME . '/system_validation/Units/body_corporate_unit_with_sub_meters.php',
		'SYSTEM_VALIDATION_UNITS_UNITS_WITH_BULK_AND_SERVICE_METERS' => DOMAIN_NAME . '/system_validation/Units/units_with_bulk_and_service_meters.php',
		'SYSTEM_VALIDATION_CUSTOMERS_CUSTOMERS_WITH_OVERLAPPING_OCCUPANCY' => DOMAIN_NAME . '/system_validation/Customers/customers_with_overlapping_occupancy.php',
		'SYSTEM_VALIDATION_CUSTOMERS_PREPAID_CUSTOMERS_WITH_OVERLAPPING_OCCUPANCY' => DOMAIN_NAME . '/system_validation/Customers/prepaid_customers_with_overlapping_occupancy.php',
		'SYSTEM_VALIDATION_METERS_METER_UTILITY_TYPE_UNKNOWN' => DOMAIN_NAME . '/system_validation/Meters/meter_utility_type_unknown.php',
		'SYSTEM_VALIDATION_METERS_DUPLICATE_METER_NUMBER' => DOMAIN_NAME . '/system_validation/Meters/duplicate_meter_number.php',
		'SYSTEM_VALIDATION_METERS_METERS_WITH_OVERLAPPING_PERIOD' => DOMAIN_NAME . '/system_validation/Meters/meters_with_overlapping_period.php',
		'SYSTEM_VALIDATION_METERS_DECOMMISSIONED_AND_IS_ACTIVE' => DOMAIN_NAME . '/system_validation/Meters/decommissioned_and_isactive.php',
		'SYSTEM_VALIDATION_METERS_DECOMMISSIONED_NOT_REPLACED' => DOMAIN_NAME . '/system_validation/Meters/decommissioned_not_replaced.php',
		'SYSTEM_VALIDATION_METERS_RATE_SPECIFIC_METER_WITH_INACTIVE_RATE' => DOMAIN_NAME . '/system_validation/Meters/rate_specific_meter_with_inactive_rate.php',
		'SYSTEM_VALIDATION_METERS_READINGS_AFTER_VACANCY_DATE' => DOMAIN_NAME . '/system_validation/Meters/reading_after_vacancy_date.php',
		'SYSTEM_VALIDATION_METERS_READING_CURRENT_NEGATIVE_CONSUMPTION' => DOMAIN_NAME . '/system_validation/Meters/reading_current_negative_consumption.php',
		/** System Administration */
		'SYSTEM_ADMINISTRATION_PARAMETERS' => DOMAIN_NAME . '/sysadmin/parameters.php',
		'SYSTEM_ADMINISTRATION_COMPANY'=> DOMAIN_NAME . '/sysadmin/company.php',
		'SYSTEM_ADMINISTRATION_BUSINESS_FUNCTION' => DOMAIN_NAME . '/sysadmin/business.php',
		'SYSTEM_ADMINISTRATION_USER' => DOMAIN_NAME . '/sysadmin/user.php',
		'SYSTEM_ADMINISTRATION_MANUAL_READING_ADJUSTMENT' => DOMAIN_NAME . '/sysadmin/manual_reading_adjustment.php'
	);
	
	//Business Function Menu
	$BusinessFunctionUserMenu = new BusinessFunctionUserMenu($dbh);
	$business_function_user_menu_data = $BusinessFunctionUserMenu->getBusinessFunctionUserMenu(array($userPK, $userPK));
	//StoredProcedures::displayParams($business_function_user_menu_data);
			
	//Menu
	$Menu = new Menu($dbh);
	$menu_data = $Menu->getMenu(array($userPK, 0));
	
	//Construct Parallel array,
	//$menu_PK will contain the Primary Keys for the Menu
	//$menu_VALUE will contain the String value of the Menu
	// foreach($menu_data as $menu){
		// $menu_PK[] = $menu['MenuPk'];
		// $menu_VALUE[] = $menu['Value'];
	// }
	
	//Function to get Menu
	function getMenuValue($menu_pk){
		foreach($menu_data as $menu){
			if ($menu_pk == $menu['MenuPk'] ){
				return $menu['Value'];
			}
		}
	}
	
	$BusinessFunctionFk = $Session->read('BusinessFunctionUser');
	//echo 'UserPK: ' . $userPK;
?>
<ul>
	<li><a href="#">Maintenance</a>
		<ul class="sub-menu">    
<?php			
	foreach($business_function_user_menu_data as $user_menu){	
		if($user_menu['Menu'] == 'MAINTENANCE'){
			echo '<li><a href="' . $path_constants['MAINTENANCE_PROVIDERS'] . '">Providers</a></li>'; 
			echo '<li><a href="' . $path_constants['MAINTENANCE_RATES'] . '">Rates</a></li>'; 
			echo '<li><a href="' . $path_constants['MAINTENANCE_BUILDINGS'] . '">Buildings</a></li>'; 
			echo '<li><a href="' . $path_constants['MAINTENANCE_UNITS'] . '">Units</a></li>'; 
			echo '<li><a href="' . $path_constants['MAINTENANCE_CUSTOMERS'] . '">Customers</a></li>'; 
			echo '<li><a href="' . $path_constants['MAINTENANCE_METERS'] . '">Meters</a></li>'; 
			echo '<li><a href="' . $path_constants['MAINTENANCE_PARAMETERS'] . '">Parameters</a></li>';
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.PROVIDERS'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_PROVIDERS'] . '">Providers</a></li>';  
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.RATES'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_RATES'] . '">Rates</a></li>';   
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.BUILDINGS'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_BUILDINGS'] . '">Buildings</a></li>';   
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.UNITS'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_UNITS'] . '">Units</a></li>';   
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.CUSTOMERS'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_CUSTOMERS'] . '">Customers</a></li>';    
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.METERS'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_METERS'] . '">Meters</a></li>';    
			break;
		}
		else if($user_menu['Menu'] == 'MAINTENANCE.PARAMETERS'){ 
			echo '<li><a href="' . $path_constants['MAINTENANCE_PARAMETERS'] . '">Parameters</a></li>';   
			break;
		}
	}//end of foreach condition
?>		
		</ul>
	</li><!-- end of Maintenance -->	
	
    <li><a href="#">Processing</a>	
        <ul class="sub-menu">
		<?php foreach($business_function_user_menu_data as $user_menu){
				if($user_menu['Menu'] == 'PROCESSING'){					
					echo '<li><a href="' . $path_constants['PROCESSING_PLANNING'] . '">Planning</a></li>'; 
					echo '<li><a href="' . $path_constants['PROCESSING_REASONABILITY'] . '">Reasonability</a></li>';
					echo '<li><a href="' . $path_constants['PROCESSING_INVOICING'] . '">Invoicing</a></li>';
					echo '<li>'; 
						echo '<a href="#">Import</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_IMPORT_PREPAID_TRANSACTIONS'] . '">Prepaid Transactions</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.PLANNING'){
					echo '<li><a href="' . $path_constants['PROCESSING_PLANNING'] . '">Planning</a></li>'; 
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.REASONABILITY'){
					echo '<li><a href="' . $path_constants['PROCESSING_REASONABILITY'] . '">Reasonability</a></li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.INVOICING'){
					echo '<li><a href="' . $path_constants['PROCESSING_INVOICING'] . '">Invoicing</a></li>';
					break;
				}				
			  }//end of foreach
		?>
        </ul>
    </li><!-- end of Processing -->
    <li><a href="#">Credit Management</a>
        <ul class="sub-menu">
		<?php 
			foreach($business_function_user_menu_data as $user_menu){
				if($user_menu['Menu'] == 'CREDIT MANAGEMENT'){
					echo '<li>'; 
						echo '<a href="#">Cut Notification</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_ACCOUNTS'] . '">Overdue Accounts</a></li>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OUTSTANDING_AGREEMENTS'] . '">Outstanding Agreements</a></li>';
						echo '</ul>';
					echo '</li>';
					echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_INSTRUCTION'] . '">Cut Instruction</a></li>';
					echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_RECONNECTION'] . '">Reconnection</a></li>'; 
					echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_DEPOSIT_REVIEW'] . '">Deposit Review</a></li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.CUT NOTIFICATION'){
					echo '<li>'; 
						echo '<a href="#">Cut Notification</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_ACCOUNTS'] . '">Overdue Accounts</a></li>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OUTSTANDING_AGREEMENTS'] . '">Outstanding Agreements</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.CUT NOTIFICATION.OVERDUE ACCOUNTS'){
					echo '<li>'; 
						echo '<a href="#">Cut Notification</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_ACCOUNTS'] . '">Overdue Accounts</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.CUT NOTIFICATION.OUTSTANDING AGREEMENTS'){
					echo '<li>'; 
						echo '<a href="#">Cut Notification</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OUTSTANDING_AGREEMENTS'] . '">Outstanding Agreements</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.CUT INSTRUCTION'){
					echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_INSTRUCTION'] . '">Cut Instruction</a></li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.RECONNECTION'){
					echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_RECONNECTION'] . '">Reconnection</a></li>'; 
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.DEPOSIT REVIEW'){
					echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_DEPOSIT_REVIEW'] . '">Deposit Review</a></li>';
					break;
				}
			}//end of foreach
		?>
        </ul>
    </li><!-- end of Credit Management -->
    <li><a href="#">Reporting</a>
        <ul class="sub-menu">
		<?php 
			foreach($business_function_user_menu_data as $user_menu){		
				if($user_menu['Menu'] == 'QUERY AND REPORTING'){
					echo '<li><a href="' . $path_constants['REPORTING_PROVIDERS'] . '">Providers</a></li>';
					
					echo '<li>'; 
						echo '<a href="#">Rates</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['REPORTING_RATE_RATE_REPORT'] . '">Rate Report</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li>'; 
						echo '<a href="#">Buildings</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['REPORTING_BUILDING_BUILDING_RATE_LIST'] . '">Building Rate List</a></li>';
								echo '<li><a href="' . $path_constants['REPORTING_BUILDING_SQM_METER_ALLOCATION_LIST'] . '">Sqm Meter Allocation List</a></li>';
								echo '<li><a href="' . $path_constants['REPORTING_BUILDING_PORTFOLIO_MANAGER_LIST'] . '">Portfolio Manager List</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li><a href="' . $path_constants['REPORTING_UNITS'] . '">Units</a></li>';
					
					echo '<li class="Customer_list">'; 
						echo '<a href="#">Customers</a>';
						echo '<ul class="customers_sub_list">';
								echo '<li><a href="' . $path_constants['REPORTING_CUSTOMERS_DEPOSIT_LIST'] . '">Deposit List</a></li>';
								echo '<li><a href="' . $path_constants['REPORTING_CUSTOMERS_ACCOUNT_DETAIL'] . '">Account Detail</a></li>';
								echo '<li><a href="' . $path_constants['REPORTING_CUSTOMERS_INVOICE_DETAIL'] . '">Invoice Detail</a></li>';
								echo '<li><a href="' . $path_constants['REPORTING_CUSTOMERS_ACCOUNTS_WITHOUT_CONTACT_DETAILS'] . '">Accounts without Contact Details</a></li>';
								echo '<li><a href="' . $path_constants['REPORTING_CUSTOMERS_OUTSTANDING_AGREEMENT_LIST'] . '">Outstanding Agreement List</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li>'; 
						echo '<a href="#">Meters</a>';
						echo '<ul class="meters_sub_list">';
								echo '<li><a href="#">Readings Import View</a>';
									echo '<ul>';
										echo '<li><a href="' . $path_constants['REPORTING_METERS_READING_IMPORTS_REVIEW_READINGS_IMPORTED'] . '">Readings Imported</a><li>';
										echo '<li><a href="' . $path_constants['REPORTING_METERS_READING_IMPORTS_REVIEW_METER_TEST_REQUIRED'] . '">Meter Test Required</a><li>';
										echo '<li><a href="' . $path_constants['REPORTING_METERS_READING_IMPORTS_REVIEW_ESTIMATED_READINGS'] . '">Estimated Readings</a><li>';
										echo '<li><a href="' . $path_constants['REPORTING_METERS_READING_IMPORTS_REVIEW_EXCEPTIONAL_READINGS'] . '">Exceptional Readings</a><li>';
									echo '</ul>';
								echo '</li>';								
								echo '<li><a href="' . $path_constants['REPORTING_METERS_READING_REVIEW'] . '">Reading Review</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_METERS_METERS_ESTIMATED_THREE_TIMES'] . '">Meters Estimated Three Times</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_METERS_READING_VARIANCE_PARAMETER_ANALYSIS'] . '">Reading Variance Parameter Analysis</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_METERS_INTERNAL_PREPAID_METER_LIST'] . '">Internal Prepaid Meter List</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_METERS_INTERNAL_PREPAID_METER_REASONABILITY'] . '">Internal Prepaid Meter Reasonability</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_METERS_RATE_SPECIFIC_METER_LIST'] . '">Rate Specific Meter List</a><li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li>'; 
						echo '<a href="#">Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['REPORTING_BILLING_OUTSTANDING_BILLING_LIST'] . '">Outstanding Billing List</a></li>';								
								echo '<li><a href="' . $path_constants['REPORTING_BILLING_OUTSTANDING_BILLING_DETAIL'] . '">Outstanding Billing Detail</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_BILLING_COMMON_PROPERTY_ALLOCATION'] . '">Common Property Allocation</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_BILLING_PREVIOUS_BILLING'] . '">Previous Billing</a><li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul style="width:250px;">';
								echo '<li><a href="' . $path_constants['REPORTING_CREDIT_MANAGEMENT_PAYMENT_ARRANGEMENT_LIST'] . '">Payment Arrangement List</a></li>';								
								echo '<li><a href="' . $path_constants['REPORTING_CREDIT_MANAGEMENT_OVERDUE_ACCOUNT_NOT_NOTIFIED'] . '">Overdue Account not Notified</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_CREDIT_MANAGEMENT_AGREEMENT_OUTSTANDING_NOT_NOTIFIED'] . '">Agreement Outstanding not Notified</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_CREDIT_MANAGEMENT_NOTIFIED_ACCOUNTS_NOT_CUT'] . '">Notified Accounts not Cut</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_CREDIT_MANAGEMENT_SETTLED_ACCOUNTS_NOT_RECONNECTED'] . '">Settled Accounts not Reconnected</a><li>';
								echo '<li><a href="' . $path_constants['REPORTING_CREDIT_MANAGEMENT_STANDBY_CUT_LIST'] . '">Standby Cut List</a><li>';
						echo '</ul>';
					echo '</li>';
					break;
				}				
			}//end of foreach
		?>
     
        </ul>
    </li><!-- end of Query and Reporting -->
	
	<li><a href="#">System Validation</a>
		<ul class="sub-menu">      
		<?php 
			foreach($business_function_user_menu_data as $user_menu){	
				if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION' ||  $userPK == 1){
					echo '<li><a href="#">Providers</a>'; 					
						echo '<ul>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_PROVIDERS_PROVIDERS_WITH_NO_RATES'] . '">Providers with no Rates</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li><a href="#">Rates</a>'; 					
						echo '<ul class="rates_sub_list">';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_RATES_RATES_WITH_NO_RETAIL_OR_BULK_RATE'] . '">Rates with no Retail or Bulk Rate</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_RATES_RATES_WITH_SCALE_ERRORS'] . '">Rates with Scale Error</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_RATES_RATES_WITH_NO_PROVIDER'] . '">Rates with no Provider</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_RATES_RATES_WITH_NO_BUILDINGS'] . '">Rates with no Buildings</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_RATES_RATES_WITH_NO_METERS'] . '">Rates with no Meters</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li><a href="#">Buildings</a>'; 					
						echo '<ul class="building_sub_list">';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_PORTFOLIO_MANAGER'] . '">Buildings with no Portfolio Manager</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_RATES'] . '">Buildings with no Rates</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_INACTIVE_RATES'] . '">Buildings with Inactive Rates</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_RATE_UTILITY_MISMATCH'] . '">Buildings with Rate Utility Mismatch</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_UNITS'] . '">Buildings with no Units</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_METERS'] . '">Buildings with no Meters</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_BUILDINGS_BUILDINGS_WITH_NO_BULK_METERS'] . '">Buildings with no Bulk Meters</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li><a href="#">Units</a>'; 					
						echo '<ul style="width:255px;">';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_UNITS_BODY_CORPORATE_UNIT_WITH_SUB_METERS'] . '">Body Corporate Unit with Sub Meters</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_UNITS_UNITS_WITH_BULK_AND_SERVICE_METERS'] . '">Units with Bulk and Service Meters</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li><a href="#">Customers</a>'; 					
						echo '<ul style="width: 325px;">';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_CUSTOMERS_CUSTOMERS_WITH_OVERLAPPING_OCCUPANCY'] . '">Customers with Overlapping Occupancy</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_CUSTOMERS_PREPAID_CUSTOMERS_WITH_OVERLAPPING_OCCUPANCY'] . '">Prepaid Customers with Overlapping Occupancy</a></li>';
						echo '</ul>';
					echo '</li>';
					
					echo '<li><a href="#">Meters</a>'; 					
						echo '<ul style="width:270px;">';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_METER_UTILITY_TYPE_UNKNOWN'] . '">Meter Utility Type Unknown</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_DUPLICATE_METER_NUMBER'] . '">Duplicate Meter Number</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_METERS_WITH_OVERLAPPING_PERIOD'] . '">Meters with Overlapping Period</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_DECOMMISSIONED_AND_IS_ACTIVE'] . '">Decommissioned and is Active</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_DECOMMISSIONED_NOT_REPLACED'] . '">Decommissioned not Replaced</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_RATE_SPECIFIC_METER_WITH_INACTIVE_RATE'] . '">Rate Specific Meter with Inactive Rate</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_READINGS_AFTER_VACANCY_DATE'] . '">Readings After Vacancy Date</a></li>';
							echo '<li><a href="' . $path_constants['SYSTEM_VALIDATION_METERS_READING_CURRENT_NEGATIVE_CONSUMPTION'] . '">Reading Current Negative Consumption</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}				
			}	
		?>
		</ul>
    </li><!-- end of System Validation -->
	
    <li><a href="#">System Administration</a>
		<ul class="sub-menu" style="width: 197px;">      
		<?php 
			foreach($business_function_user_menu_data as $user_menu){	
				if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION' || $userPK == 1){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_PARAMETERS'] . '">Parameters</a></li>'; 
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_COMPANY'] . '">Company</a></li>'; 
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_BUSINESS_FUNCTION'] . '">Business Function</a></li>'; 
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_USER'] . '">User</a></li>';
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_MANUAL_READING_ADJUSTMENT'] . '">Manual Reading Adjustment</a></li>';
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.PARAMETERS'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_PARAMETERS'] . '">Parameters</a></li>'; 
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.COMPANY'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_COMPANY'] . '">Company</a></li>'; 
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.BUSINESS FUNCTION'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_BUSINESS_FUNCTION'] . '">Business Function</a></li>'; 
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.USER'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_USER'] . '">User</a></li>';
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.MANUAL READING ADJUSTMENT'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_MANUAL_READING_ADJUSTMENT'] . '">Manual Reading Adjustment</a></li>';
					break;
				}
			}	
		?>
		</ul>
    </li><!-- end of System Administration -->
    <li>
		<a href="#"><?php echo ucwords(strtolower($Session->read('DisplayName'))); ?></a>		
		<ul class="sub-menu">
            <li>
				<a href="<?php echo DOMAIN_NAME; ?>/sysadmin/user-company-selection.php">
					<?php
						if($Session->check('user_company_selection_text')){ echo ucwords(strtolower($Session->read('user_company_selection_text'))); }
						else{ echo 'Company'; }
					?>
				</a>
			</li>
			<li><a href="<?php echo DOMAIN_NAME; ?>/auth/logout.php">Log out</a></li>
        </ul>
	</li>
</ul>