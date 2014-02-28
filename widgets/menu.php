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
		'PROCESSING_MONTH_END_BILLING_REASONABILITY' => DOMAIN_NAME . '/processing/Month_end_Billing/reasonability.php',
		'PROCESSING_MONTH_END_BILLING_BILLING' => DOMAIN_NAME . '/processing/Month_end_Billing/billing.php',
		'PROCESSING_SINGLE_BILLING_RESET_BILLING' => '#',
		'PROCESSING_SINGLE_BILLING_INVOICING' => '#',
		//Credit Management		
		'CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_CUT_NOTIFICATION' => DOMAIN_NAME . '/credit_management/Cut_Notification/overdue_cut_notification.php',
		'CREDIT_MANAGEMENT_CUT_NOTIFICATION_AGREEMENT' => '#',
		'CREDIT_MANAGEMENT_CUT_INSTRUCTION' => '#',
		'CREDIT_MANAGEMENT_RECONNECTION' => '#',
		'CREDIT_MANAGEMENT_DEPOSIT_REVIEW' => '#',
		//Query and Reporting
		'QUERY_AND_REPORTING_CUSTOMER_DEPOSIT_LIST' => DOMAIN_NAME . '/query_and_reporting/Customer/deposit_list.php',
		'QUERY_AND_REPORTING_CUSTOMER_ACCOUNT_DETAIL' => DOMAIN_NAME . '/query_and_reporting/Customer/account_detail.php',
		'QUERY_AND_REPORTING_CUSTOMER_INVOICE_DETAIL' => DOMAIN_NAME . '/query_and_reporting/Customer/invoice_detail.php',
		'QUERY_AND_REPORTING_METER_READING_REVIEW' => DOMAIN_NAME . '/query_and_reporting/Meter/reading.php',
		'QUERY_AND_REPORTING_METER_IMPORT_FILE_ANALYSIS' => '#',
		'QUERY_AND_REPORTING_METER_INTERNAL_PREPAID_REASONABILITY' => '#',
		'QUERY_AND_REPORTING_BILLING_TERMINATIONS_NOT_INVOICE' => '#',
		'QUERY_AND_REPORTING_BILLING_PREVIOUS_BILLING_REPORT' => '#',
		'QUERY_AND_REPORTING_BILLING_COMMON_PROPERTY_CALCULATION' => '#',
		'QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAYMENT_ARRANGEMENT_LIST' => DOMAIN_NAME . '/query_and_reporting/Credit_Management/payment_arrangement_list.php',
		'QUERY_AND_REPORTING_CREDIT_MANAGEMENT_OVERDUE_NOT_NOTIFIED' => '#',
		'QUERY_AND_REPORTING_CREDIT_MANAGEMENT_AGREEMENT_NOT_NOTIFIED' => '#',
		'QUERY_AND_REPORTING_CREDIT_MANAGEMENT_NOTIFIED_NOT_CUT' => '#',
		'QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAID_NOT_RECONNECTED' => '#',
		'QUERY_AND_REPORTING_READING_IMPORTS_BUILDING_IMPORTS' => DOMAIN_NAME . '/query_and_reporting/Reading_Imports/buildings_import.php',
		'QUERY_AND_REPORTING_READING_IMPORTS_TEST_METER_REPORT' => DOMAIN_NAME . '/query_and_reporting/Reading_Imports/reading_test_meter.php',
		'QUERY_AND_REPORTING_READING_IMPORTS_ESTIMATED_READINGS_REPORT' => DOMAIN_NAME . '/query_and_reporting/Reading_Imports/estimated_readings_report.php',
		'QUERY_AND_REPORTING_READING_IMPORTS_METERS_ESTIMATE_THREE_TIMES' => DOMAIN_NAME . '/query_and_reporting/Reading_Imports/reading_estimated_3_times.php',
		'QUERY_AND_REPORTING_READING_IMPORTS_EXCEPTIONAL_READING_REPORT' => DOMAIN_NAME . '/query_and_reporting/Reading_Imports/exceptional_reading_report.php',
		'QUERY_AND_REPORTING_READING_IMPORTS_VARIANCE_FACTOR_ANALYSIS' => DOMAIN_NAME . '/query_and_reporting/Reading_Imports/variance_factor_analysis.php',
		'QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_INACTIVE_RATES' => DOMAIN_NAME . '/query_and_reporting/Validation/buildings_with_inactive_rates.php',
		'QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_ACTIVE_RATES' => DOMAIN_NAME . '/query_and_reporting/Validation/buildings_with_active_rates.php',
		'QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_INACTIVE_RATE' => DOMAIN_NAME . '/query_and_reporting/Validation/rate_specific_meter_with_inactive_rate.php',
		'QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_ACTIVE_RATE' => DOMAIN_NAME . '/query_and_reporting/Validation/rate_specific_meter_with_active_rate.php',
		'QUERY_AND_REPORTING_VALIDATION_TOTAL_SQUARE_METER_BUILDING_SQM_ALLOCATION' => DOMAIN_NAME . '/query_and_reporting/Validation/total_square_meters_building_SQM_allocation.php',
		'QUERY_AND_REPORTING_VALIDATION_BODY_CORPORATE_UNIT_WITH_SUB_METERS' => DOMAIN_NAME . '/query_and_reporting/Validation/body_corporate_unit_with_sub_meters.php',
		'QUERY_AND_REPORTING_VALIDATION_UNITS_WITH_BULK_AND_SERVICE_METERS' => DOMAIN_NAME . '/query_and_reporting/Validation/units_with_bulk_and_service_meters.php',
		'QUERY_AND_REPORTING_VALIDATION_READING_AFTER_VACANCY_DATE' => DOMAIN_NAME . '/query_and_reporting/Validation/reading_after_vacancy_date.php',
		//System Administration
		'SYSTEM_ADMINISTRATION_PARAMETERS' => DOMAIN_NAME . '/sysadmin/parameters.php',
		'SYSTEM_ADMINISTRATION_COMPANY'=> DOMAIN_NAME . '/sysadmin/company.php',
		'SYSTEM_ADMINISTRATION_BUSINESS_FUNCTION' => DOMAIN_NAME . '/sysadmin/business.php',
		'SYSTEM_ADMINISTRATION_USER' => DOMAIN_NAME . '/sysadmin/user.php'
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
					echo '<li>'; 
						echo '<a href="#">Month-end Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_MONTH_END_BILLING_REASONABILITY'] . '">Reasonability</a></li>';
								echo '<li><a href="' . $path_constants['PROCESSING_MONTH_END_BILLING_BILLING'] . '">Invoicing</a></li>';
						echo '</ul>';
					echo '</li>';					
					echo '<li>'; 
						echo '<a href="#">Single Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_SINGLE_BILLING_RESET_BILLING'] . '">Reset Billing</a></li>';
								echo '<li><a href="' . $path_constants['PROCESSING_SINGLE_BILLING_INVOICING'] . '">Invoicing</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.MONTH END BILLING'){
					echo '<li><a href="' . $path_constants['PROCESSING_PLANNING'] . '">Planning</a></li>'; 
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.MONTH END BILLING.REASONABILITY'){
					echo '<li>'; 
						echo '<a href="#">Month-end Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_MONTH_END_BILLING_REASONABILITY'] . '">Reasonability</a></li>';
								echo '<li><a href="' . $path_constants['PROCESSING_MONTH_END_BILLING_BILLING'] . '">Invoicing</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.MONTH END BILLING.INVOICING'){
					echo '<li>'; 
						echo '<a href="#">Month-end Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_MONTH_END_BILLING_REASONABILITY'] . '">Reasonability</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.MONTH END BILLING.INVOICING'){
					echo '<li>'; 
						echo '<a href="#">Month-end Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_MONTH_END_BILLING_BILLING'] . '">Invoicing</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.SINGLE BILLING.RESET BILLING'){
					echo '<li>'; 
						echo '<a href="#">Single Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_SINGLE_BILLING_RESET_BILLING'] . '">Reset Billing</a></li>';
								echo '<li><a href="' . $path_constants['PROCESSING_SINGLE_BILLING_INVOICING'] . '">Invoicing</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.SINGLE BILLING.RESET BILLING'){
					echo '<li>'; 
						echo '<a href="#">Single Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_SINGLE_BILLING_RESET_BILLING'] . '">Reset Billing</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'PROCESSING.SINGLE BILLING.INVOICING'){
					echo '<li>'; 
						echo '<a href="#">Single Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['PROCESSING_SINGLE_BILLING_INVOICING'] . '">Invoicing</a></li>';
						echo '</ul>';
					echo '</li>';
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
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_CUT_NOTIFICATION'] . '">Overdue Cut Notification</a></li>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_AGREEMENT'] . '">Agreement</a></li>';
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
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_CUT_NOTIFICATION'] . '">Overdue Cut Notification</a></li>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_AGREEMENT'] . '">Agreement</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.CUT NOTIFICATION.OVERDUE'){
					echo '<li>'; 
						echo '<a href="#">Cut Notification</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_OVERDUE_CUT_NOTIFICATION'] . '">Overdue Cut Notification</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'CREDIT MANAGEMENT.CUT NOTIFICATION.AGREEMENT'){
					echo '<li>'; 
						echo '<a href="#">Cut Notification</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['CREDIT_MANAGEMENT_CUT_NOTIFICATION_AGREEMENT'] . '">Agreement</a></li>';
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
    <li><a href="#">Query and Reporting</a>
        <ul class="sub-menu">
		<?php 
			foreach($business_function_user_menu_data as $user_menu){		
				if($user_menu['Menu'] == 'QUERY AND REPORTING'){
					echo '<li class="Customer_list">'; 
						echo '<a href="#">Customer</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_DEPOSIT_LIST'] . '">Deposit List</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_ACCOUNT_DETAIL'] . '">Account Detail</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_INVOICE_DETAIL'] . '">Invoice Detail</a></li>';
						echo '</ul>';
					echo '</li>';
					echo '<li>'; 
						echo '<a href="#">Meter</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_READING_REVIEW'] . '">Reading Review</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_IMPORT_FILE_ANALYSIS'] . '">Import File Analysis</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_INTERNAL_PREPAID_REASONABILITY'] . '">Internal Prepaid Reasonability</a></li>';
						echo '</ul>';
					echo '</li>';
					echo '<li>'; 
						echo '<a href="#">Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_TERMINATIONS_NOT_INVOICE'] . '">Terminations not Invoice</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_PREVIOUS_BILLING_REPORT'] . '">Previous Billing Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_COMMON_PROPERTY_CALCULATION'] . '">Common Property Calculation</a></li>';
						echo '</ul>';
					echo '</li>';
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAYMENT_ARRANGEMENT_LIST'] . '">Payment Arrangement List</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_OVERDUE_NOT_NOTIFIED'] . '">Overdue not Notified</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_AGREEMENT_NOT_NOTIFIED'] . '">Agreement not Notified</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_NOTIFIED_NOT_CUT'] . '">Notified not Cut</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAID_NOT_RECONNECTED'] . '">Paid not Reconnected</a></li>';
						echo '</ul>';
					echo '</li>';
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_BUILDING_IMPORTS'] . '">Buildings Import</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_TEST_METER_REPORT'] . '">Test Meter Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_ESTIMATED_READINGS_REPORT'] . '">Estimated Readings Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_METERS_ESTIMATE_THREE_TIMES'] . '">Meters Estimate Three Times</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_EXCEPTIONAL_READING_REPORT'] . '">Exceptional Reading Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_VARIANCE_FACTOR_ANALYSIS'] . '">Variance Factor Analysis</a></li>';
						echo '</ul>';
					echo '</li>';
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_INACTIVE_RATES'] . '">Buildings with Inactive Rates</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_ACTIVE_RATES'] . '">Buildings with Active Rates</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_INACTIVE_RATE'] . '">Rate Specific Meter with Inactive Rate</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_ACTIVE_RATE'] . '">Rate Specific Meter with Active Rate</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_TOTAL_SQUARE_METER_BUILDING_SQM_ALLOCATION'] . '">Total Square Meters for Buildings with SQM Allocation</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BODY_CORPORATE_UNIT_WITH_SUB_METERS'] . '">Body Corporate Unit with Sub Meters</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_UNITS_WITH_BULK_AND_SERVICE_METERS'] . '">Units with Bulk and Service Meters</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_READING_AFTER_VACANCY_DATE'] . '">Reading After Vacancy Date</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CUSTOMER' ){
					echo '<li class="Customer_list">'; 
						echo '<a href="#">Customer</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_DEPOSIT_LIST'] . '">Deposit List</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_ACCOUNT_DETAIL'] . '">Account Detail</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_INVOICE_DETAIL'] . '">Invoice Detail</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CUSTOMER.DEPOSIT LIST' ){
					echo '<li class="Customer_list">'; 
						echo '<a href="#">Customer</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_DEPOSIT_LIST'] . '">Deposit List</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CUSTOMER.ACCOUNT DETAIL'){
					echo '<li class="Customer_list">'; 
						echo '<a href="#">Customer</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_ACCOUNT_DETAIL'] . '">Account Detail</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CUSTOMER.INVOICE DETAIL'){
					echo '<li class="Customer_list">'; 
						echo '<a href="#">Customer</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CUSTOMER_INVOICE_DETAIL'] . '">Invoice Detail</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}				
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.METER'){
					echo '<li>'; 
						echo '<a href="#">Meter</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_READING_REVIEW'] . '">Reading Review</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_IMPORT_FILE_ANALYSIS'] . '">Import File Analysis</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_INTERNAL_PREPAID_REASONABILITY'] . '">Internal Prepaid Responsibility</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.METER.READING REVIEW'){
					echo '<li>'; 
						echo '<a href="#">Meter</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_READING_REVIEW'] . '">Reading Review</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.METER.IMPORT FILE ANALYSIS'){
					echo '<li>'; 
						echo '<a href="#">Meter</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_IMPORT_FILE_ANALYSIS'] . '">Import File Analysis</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.METER.INTERNAL PREPAID REASONA'){
					echo '<li>'; 
						echo '<a href="#">Meter</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_METER_INTERNAL_PREPAID_REASONABILITY'] . '">Internal Prepaid Reasonability</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.BILLING'){
					echo '<li>'; 
						echo '<a href="#">Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_TERMINATIONS_NOT_INVOICE'] . '">Terminations not Invoice</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_PREVIOUS_BILLING_REPORT'] . '">Previous Billing Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_COMMON_PROPERTY_CALCULATION'] . '">Common Property Calculation</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.BILLING.TERMINATIONS NOT INVOICE'){
					echo '<li>'; 
						echo '<a href="#">Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_TERMINATIONS_NOT_INVOICE'] . '">Terminations not Invoice</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.BILLING.PREVIOUS BILLING REPORT'){
					echo '<li>'; 
						echo '<a href="#">Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_PREVIOUS_BILLING_REPORT'] . '">Previous Billing Report</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.BILLING.COMMON PROPERTY CALCULATION'){
					echo '<li>'; 
						echo '<a href="#">Billing</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_BILLING_COMMON_PROPERTY_CALCULATION'] . '">Common Property Calculation</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CREDIT MANAGEMENT'){
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAYMENT_ARRANGEMENT_LIST'] . '">Payment Arrangement List</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_OVERDUE_NOT_NOTIFIED'] . '">Overdue not Notified</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_AGREEMENT_NOT_NOTIFIED'] . '">Agreement not Notified</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_NOTIFIED_NOT_CUT'] . '">Notified not Cut</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAID_NOT_RECONNECTED'] . '">Paid not Reconnected</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CREDIT MANAGEMENT.PAYMENT ARRANGEMENT LIST'){
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAYMENT_ARRANGEMENT_LIST'] . '">Payment Arrangement List</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CREDIT MANAGEMENT.OVERDUE NOT NOTIFIED'){
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_OVERDUE_NOT_NOTIFIED'] . '">Overdue not Notified</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CREDIT MANAGEMENT.AGREEMENT NOT NOTIFIED'){
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_AGREEMENT_NOT_NOTIFIED'] . '">Agreement not Notified</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CREDIT MANAGEMENT.NOTIFIED NOT CUT'){
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_NOTIFIED_NOT_CUT'] . '">Notified not Cut</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.CREDIT MANAGEMENT.PAID NOT RECONNECTED'){
					echo '<li>'; 
						echo '<a href="#">Credit Management</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_CREDIT_MANAGEMENT_PAID_NOT_RECONNECTED'] . '">Paid not Reconnected</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_BUILDING_IMPORTS'] . '">Buildings Import</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_TEST_METER_REPORT'] . '">Test Meter Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_ESTIMATED_READINGS_REPORT'] . '">Estimated Readings Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_METERS_ESTIMATE_THREE_TIMES'] . '">Meters Estimate Three Times</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_EXCEPTIONAL_READING_REPORT'] . '">Exceptional Reading Report</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_VARIANCE_FACTOR_ANALYSIS'] . '">Variance Factor Analysis</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS.BUILDINGS IMPORT'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_BUILDING_IMPORTS'] . '">Buildings Import</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS.TEST METER'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_TEST_METER_REPORT'] . '">Test Meter Report</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS.ESTIMATED READING'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_ESTIMATED_READINGS_REPORT'] . '">Estimated Readings Report</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS.METER ESTIMATE 3 TIMES'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_METERS_ESTIMATE_THREE_TIMES'] . '">Meters Estimate Three Times</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS.EXCEPTIONAL READING'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_EXCEPTIONAL_READING_REPORT'] . '">Exceptional Reading Report</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 'QUERY AND REPORTING.READING IMPORTS.VARIANCE FACTOR ANALYSIS'){
					echo '<li>'; 
						echo '<a href="#">Reading Imports</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_READING_IMPORTS_VARIANCE_FACTOR_ANALYSIS'] . '">Variance Factor Analysis</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 1032){
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_INACTIVE_RATES'] . '">Buildings with Inactive Rates</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_ACTIVE_RATES'] . '">Buildings with Active Rates</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_INACTIVE_RATE'] . '">Rate Specific Meter with Inactive Rate</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_ACTIVE_RATE'] . '">Rate Specific Meter with Active Rate</a></li>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_TOTAL_SQUARE_METER_BUILDING_SQM_ALLOCATION'] . '">Total Square Meters for Buildings with SQM Allocation</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 1033){
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_INACTIVE_RATES'] . '">Buildings with Inactive Rates</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 1034){
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_BUILDINGS_WITH_ACTIVE_RATES'] . '">Buildings with Active Rates</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 1035){
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_INACTIVE_RATE'] . '">Rate Specific Meter with Inactive Rate</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 1036){
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_RATE_SPECIFIC_METER_WITH_ACTIVE_RATE'] . '">Rate Specific Meter with Active Rate</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
				else if($user_menu['Menu'] == 1039){
					echo '<li class="Validation_list">'; 
						echo '<a href="#">Validation</a>';
						echo '<ul>';
								echo '<li><a href="' . $path_constants['QUERY_AND_REPORTING_VALIDATION_TOTAL_SQUARE_METER_BUILDING_SQM_ALLOCATION'] . '">Total Square Meters for Buildings with SQM Allocation</a></li>';
						echo '</ul>';
					echo '</li>';
					break;
				}
			}//end of foreach
		?>
     
        </ul>
    </li><!-- end of Query and Reporting -->
    <li><a href="#">System Administration</a>
		<ul class="sub-menu">      
		<?php 
			foreach($business_function_user_menu_data as $user_menu){	
				if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION' || $userPK == 1){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_PARAMETERS'] . '">Parameters</a>'; 
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_COMPANY'] . '">Company</a>'; 
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_BUSINESS_FUNCTION'] . '">Business Function</a>'; 
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_USER'] . '">User</a>';
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.PARAMETERS'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_PARAMETERS'] . '">Parameters</a>'; 
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.COMPANY'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_COMPANY'] . '">Company</a>'; 
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.BUSINESS FUNCTION'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_BUSINESS_FUNCTION'] . '">Business Function</a>'; 
					break;
				}
				else if($user_menu['Menu'] == 'SYSTEM ADMINISTRATION.USER'){
					echo '<li><a href="' . $path_constants['SYSTEM_ADMINISTRATION_USER'] . '">User</a>';
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