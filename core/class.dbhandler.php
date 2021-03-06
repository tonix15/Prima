<?php
class DBHandler extends PDOSQLServerdbhandler {
    public function __construct(PrimaDB $db) {
        parent::__construct($db);
    }
    
    public function __destruct() {
    	parent::__destruct();
    }
    
    /** Address */
	public function getAddress($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_ADDRESS, $params, $isSingleRecord);
    }
	
	public function getArrangementList($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::REP_ARRANGEMENT_LIST, $params, $isSingleRecord);
    }
	
	/*** Billing */
	public function getBillingAccount($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::GET_BILLING_ACCOUNT, $params, $isSingleRecord);
	}
	
	public function getBodyCorporateUnitSubMeter($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::BODY_CORPORATE_UNIT_WITH_SUB_METER, $params, $isSingleRecord);
	}
    
    /** Building */
	public function getBuilding($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::GET_BUILDING, $params, $isSingleRecord);
	}
	
	public function createBuilding($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_BUILDING, $params);
		return $this->getLastInsertId(PrimaDB::BUILDING_TABLE);
	}
	
	public function updateBuilding($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_BUILDING, $params);
	}
	
	public function getBuildingBilling($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_BUILDING_BILLING, $params, $isSingleRecord);
    }
	
    public function getBuildingBillingPeriod($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_BUILDING_BILLING_PERIOD, $params, $isSingleRecord);
    }
    
    public function updateBuildingBillingPeriod($params) {
    	return $this->executeNonQueryStoredProcedure(SP::SET_BUILDING_BILLING_PERIOD, $params);
    }
       
    public function getBuildingTerminationPeriod($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_BUILDING_TERMINATION_PERIOD, $params, $isSingleRecord);
    }
    
    public function updateBuildingTerminationPeriod($params) {
    	return $this->executeNonQueryStoredProcedure(SP::SET_BUILDING_TERMINATION_PERIOD, $params);
    }
    
    public function movendusExportBuilding($params) {
    	return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_BUILDING, $params);
    }
    
    public function getBuildingPortfolioManager($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_BUILDING_PORTFOLIO_MANAGER, $params, $isSingleRecord);
    }
    
    public function updateBuildingPlanning($params) {
    	return $this->executeNonQueryStoredProcedure(SP::SET_BUILDING_PLANNING, $params);
    }
    
    public function getBuildingRateAccount($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_BUILDING_RATE_ACCOUNT, $params, $isSingleRecord);
    }
    
    public function createBuildingRateAccount($params) {
    	$this->executeNonQueryStoredProcedure(SP::SET_BUILDING_RATE_ACCOUNT, $params);
    	return $this->getLastInsertId(PrimaDB::BUILDING_RATE_ACCOUNT_TABLE);
    }
    
    public function updateBuildingRateAccount($params) {
    	return $this->executeNonQueryStoredProcedure(SP::SET_BUILDING_RATE_ACCOUNT, $params);
    }
	
	public function getBuildingInactiveRate($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDING_INACTIVE_RATE, $params, $isSingleRecord);
    }
    
    public function getBuildingActiveRate($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDING_ACTIVE_RATE, $params, $isSingleRecord);
    }
	
	public function getBuildingSquareMeterAllocation($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDING_SQUARE_METER_ALLOCATION, $params, $isSingleRecord);
    }
    
    /** Building Type */
    public function getBuildingType($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_BUILDING_TYPE, $params, $isSingleRecord);
    }
    
    public function createBuildingType($params) {
    	$this->executeNonQueryStoredProcedure(SP::SET_BUILDING_TYPE, $params);
    	return $this->getLastInsertId(PrimaDB::BUILDING_TYPE_TABLE);
    }
    
    public function updateBuildingType($params) {
    	return $this->executeNonQueryStoredProcedure(SP::SET_BUILDING_TYPE, $params);
    }
	
	/** Business Function */
    public function getBusinessFunctionUserMenu($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_BUSINESS_FUNCTION_USER_MENU, $params, $isSingleRecord);
    }
	
    /** Contact Person */
    public function getContactPerson($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_CONTACT_PERSON, $params, $isSingleRecord);
    }
	
	/** Credit Management */
	public function createCreditManagement($params) {
    	$this->executeNonQueryStoredProcedure(SP::SET_CREDIT_MANAGEMENT, $params);
    	return $this->getLastInsertId(PrimaDB::CREDIT_MANAGEMENT_TABLE);
    }
	
	/** Customer */
	public function createCustomer($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_CUSTOMER, $params);
		return $this->getLastInsertId(PrimaDB::CUSTOMER_TABLE);
	}
	
	public function updateCustomer($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_CUSTOMER, $params);
	}
	
	public function getCustomer($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_CUSTOMER, $params, $isSingleRecord);
	}
	
	public function getCustomerOverlappingOccupancy($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::VAL_CUSTOMER_WITH_OVERLAPPING_OCCUPANCY, $params, $isSingleRecord);
	}
	
	public function getCustomerOccupancyOverlapPrepaid($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::VAL_OCCUPANCY_OVERLAP_PREPAID, $params, $isSingleRecord);
	}
	
	public function getInvoice($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_INVOICE, $params, $isSingleRecord);
	}
	
	/**Cut Notification */
    public function getCutNotification($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_CUT_NOTIFICATION, $params, $isSingleRecord);
    }
    
    public function createCutNotification($params) {
    	$this->executeNonQueryStoredProcedure(SP::SET_CUT_NOTIFICATION, $params);
    	return $this->getLastInsertId(PrimaDB::CREDIT_MANAGEMENT_TABLE);
    }
	
	 /** Cut Instruction */
	public function getCutInstruction($params) { 
    	return $this->executeQueryStoredProcedure(SP::GET_CUT_INSTRUCTION, $params);
    }

	public function updateCutInstruction($params) { 
		return $this->executeNonQueryStoredProcedure(SP::SET_CUT_INSTRUCTION, $params);
	}
	
	/** Language Type */
	public function getLanguageType($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_LANGUAGE_TYPE, $params, $isSingleRecord);
	}
	
	public function createLanguageType($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_LANGUAGE_TYPE, $params);
		return $this->getLastInsertId(PrimaDB::LANGUAGE_TYPE_TABLE);
	}
	
	public function updateLanguageType($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_LANGUAGE_TYPE, $params);
	}
	
	/** Master */
	public function movendusExportMaster($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_MASTER, $params);
	}
	
	public function movendusExportBulkMeterDeviation($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_BULK_METER_DEVIATION, $params);
	}
	
	/** Meter */
	public function movendusExportMeter($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_METER, $params);
	}
	
	public function movendusExportTerminationMeter($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_TERMINATION_METER, $params);
	}
	
	public function getMeterInActiveRate($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::VAL_METER_IN_ACTIVE_RATE, $params, $isSingleRecord);
	}
	
	public function getMeter($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_METER, $params, $isSingleRecord);
	}
	
	public function getMeterActiveRate($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::VAL_METER_ACTIVE_RATE, $params, $isSingleRecord);
	}
	
	public function movendusImportMeter($params) {
		return $this->executeNonQueryStoredProcedure(SP::MOVENDUS_IMPORT_METER, $params);
	}
	
	/** Meter Type */
	public function movendusExportMeterType($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_METER_TYPE, $params);
	}
	
	/** Preferred Contact Type */
	public function getPreferredContactType($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_PREFERRED_CONTACT_TYPE, $params, $isSingleRecord);
	}
	
	public function createPreferredContactType($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_PREFERRED_CONTACT_TYPE, $params);
		return $this->getLastInsertId(PrimaDB::PREFERRED_CONTACT_TYPE_TABLE);
	}
	
	public function updatePreferredContactType($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_PREFERRED_CONTACT_TYPE, $params);
	}
	
	/** Prepaid Transactions */
	public function createPrepaidTransactions($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_PREPAID_TRANSACTIONS, $params);
		// $this->getLastInsertId(PrimaDB::PREPAID_TRANSACTIONS_TABLE);
	}
	
	/** Reason Type */
	public function getReasonType($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_REASON_TYPE, $params, $isSingleRecord);
	}
	
	public function createReasonType($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_REASON_TYPE, $params);
		return $this->getLastInsertId(PrimaDB::REASON_TYPE_TABLE);
	}
	
	public function updateReasonType($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_REASON_TYPE, $params);
	}
	
	/** Rate */
	public function getRate($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_RATE, $params, $isSingleRecord);
	}
	
	public function createRate($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_RATE, $params);
		return $this->getLastInsertId(PrimaDB::RATE_TABLE);
	}
	
	public function updateRate($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_RATE, $params);
	}
	
	public function getReading($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_READING, $params, $isSingleRecord);
	}
	
	public function getReadingAdjust($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_READING_ADJUST, $params, $isSingleRecord);
	}
	
	public function createScale($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_SCALE, $params);
		return $this->getLastInsertId(PrimaDB::SCALE_TABLE);
	}
	
	public function updateScale($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_SCALE, $params);
	}
	
	public function getScale($params) {
		return $this->executeQueryStoredProcedure(SP::GET_SCALE, $params);
	}
	
	public function getFixedRate($params) {
		return $this->executeQueryStoredProcedure(SP::GET_FIXED_RATE, $params);
	}
	
	public function createFixedRate($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_FIXED_RATE, $params);
		return $this->getLastInsertId(PrimaDB::FIXEDRATE_TABLE);
	}
	
	public function updateFixedRate($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_FIXED_RATE, $params);
	}
	
	public function getFixedFee($params) {
		return $this->executeQueryStoredProcedure(SP::GET_FIXED_FEE, $params);
	}
	
	public function createFixedFee($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_FIXED_FEE, $params);
		return $this->getLastInsertId(PrimaDB::FIXEDFEE_TABLE);
	}
	
	public function updateFixedFee($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_FIXED_FEE, $params);
	}
	
	public function getReadingAfterVacancyDate($params) {
		return $this->executeQueryStoredProcedure(SP::READING_AFTER_VACANCY_DATE, $params);
	}
	
	public function getReadingNegative($params) {
		return $this->executeQueryStoredProcedure(SP::READING_NEGATIVE, $params);
	}
	
	/** Reporting */
	public function repCommonPropertyNoPreviousReading($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::COMMON_PROPERTY_NO_PREVIOUS_READING, $params, $isSingleRecord);
	}
	
	public function repCommonPropertyNegativeConsumption($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::COMMON_PROPERTY_NEGATIVE_CONSUMPTION, $params, $isSingleRecord);
	}
	
	public function repCommonPropertyUnderRecoveryDetail($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::COMMON_PROPERTY_UNDER_RECOVERY_DETAIL, $params, $isSingleRecord);
	}
	
	public function repCommonPropertyAllocation($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::COMMON_PROPERTY_ALLOCATION, $params, $isSingleRecord);
	}
	
	public function repReadingImport($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::READING_IMPORT, $params, $isSingleRecord);
	}
	
	public function repReadingTestMeter($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::READING_TEST_METER, $params, $isSingleRecord);
	}
	
	public function repReadingEstimated($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::READING_ESTIMATED, $params, $isSingleRecord);
	}
	
	public function repReadingEstimatedThreeTimes($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::READING_ESTIMATED_THREE_TIMES, $params, $isSingleRecord);
	}
    
	public function repReadingExceptional($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::READING_EXCEPTIONAL, $params, $isSingleRecord);
	}
	
	public function repReadingFactor($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::READING_FACTOR, $params, $isSingleRecord);
	}
	
	public function repOutstandingAgreement($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::OUTSTANDING_AGREEMENT, $params, $isSingleRecord);
	}
	
	public function repCustomerNoContactDetail($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::CUSTOMER_NO_CONTACT_DETAIL, $params, $isSingleRecord);
	}
	
	public function repOutstandingBillingList($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::OUTSTANDING_BILLING_LIST, $params, $isSingleRecord);
	}
	
	public function repOutstandingBillingDetail($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::OUTSTANDING_BILLING_DETAIL, $params, $isSingleRecord);
	}
	
	public function repCutInstructionNotNotified($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::CUT_INSTRUCTION_NOT_NOTIFIED, $params, $isSingleRecord);
	}	

	public function repCutInstructionNotCut($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::CUT_INSTRUCTION_NOT_CUT, $params, $isSingleRecord);
	}
	
	public function repBuildingPortfolioManagerList($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::BUILDING_PORTFOLIO_MANAGER_LIST, $params, $isSingleRecord);
	}
	
	public function repInternalPrepaidMeterList($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::INTERNAL_PREPAID_METER_LIST, $params, $isSingleRecord);
	}
	
	public function repStandbyCutList($params, $isSingleRecord = false){
		return $this->executeQueryStoredProcedure(SP::STANDBY_CUT_LIST, $params, $isSingleRecord);
	}
	
	/** Reason */
	public function movendusExportReason($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_REASON, $params);
	}
	
	/** Reading */
	public function movendusImportReading($params) {
		$this->executeNonQueryStoredProcedure(SP::MOVENDUS_IMPORT_READING, $params);
		return $this->getLastInsertId(PrimaDB::READING_TABLE);
	}
	
	/** Reconnection */
	public function getReconnectionInstruction($params) {
    	return $this->executeQueryStoredProcedure(SP::GET_RECONNECTION_INSTRUCTION, $params);
    }

	public function updateInstantReconnectionInstruction($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_INSTANT_RECONNECTION_INSTRUCTION, $params);
	}
	
	public function getInstantReconnectionInstruction($params) {
    	return $this->executeQueryStoredProcedure(SP::GET_INSTANT_RECONNECTION_INSTRUCTION, $params);
    }

	public function updateReconnectionInstruction($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_RECONNECTION_INSTRUCTION, $params);
	}
	
	/** Team */
	public function getTeam($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_TEAM, $params, $isSingleRecord);
	}
	
	public function createTeam($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_TEAM, $params);
		return $this->getLastInsertId(PrimaDB::TEAM_TABLE);
	}
	
	public function updateTeam($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_TEAM, $params);
	}
	
	/** Title Type */
	public function getTitleType($params, $isSingleRecord = false) {
		return $this->executeQueryStoredProcedure(SP::GET_TITLE_TYPE, $params, $isSingleRecord);
	}
	
	public function createTitleType($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_TITLE_TYPE, $params);
		return $this->getLastInsertId(PrimaDB::TITLE_TYPE_TABLE);
	}
	
	public function updateTitleType($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_TITLE_TYPE, $params);
	}
	
	/** Unit */
	public function getUnit($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_UNIT, $params, $isSingleRecord);
    }
    
    public function movendusExportUnit($params) {
    	return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_UNIT, $params);
    }
	
    /** Utility Type */
    public function getUtilityType($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_UTILITY_TYPE, $params, $isSingleRecord);
    }
    
	public function createUtilityType($params) {
		$this->executeNonQueryStoredProcedure(SP::SET_UTILITY_TYPE, $params);
        return $this->getLastInsertId(PrimaDB::UTILITY_TYPE_TABLE);
	}
	
	public function updateUtilityType($params) {
		return $this->executeNonQueryStoredProcedure(SP::SET_UTILITY_TYPE, $params);
	}
	
	public function movendusExportUtilityType($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_UTILITY_TYPE, $params);
	}
    
	/** Sage Import Account Detail */
	public function sageImportAccountDetail($params){
		return $this->executeQueryStoredProcedure(SP::SAGE_IMPORT_ACCOUNT_DETAIL, $params);
	}
	
	/** Sage Import Invoice Detail */
	public function sageImportInvoiceDetail($params){
		return $this->executeQueryStoredProcedure(SP::SAGE_IMPORT_INVOICE_DETAIL, $params);
	}
	
	public function getUnitsBulkServiceMeters($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::UNITS_WITH_BULK_AND_SERVICE_METERS, $params, $isSingleRecord);
    }
	
	/** User */
	public function getUser($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_USER, $params, $isSingleRecord);
    }
	
	/** Validation */
	public function valProvidersNoRates($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_PROVIDERS_NO_RATES, $params, $isSingleRecord);
    }
	
	public function valRatesNoBulkorRetail($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_RATES_NO_BULK_OR_RETAIL, $params, $isSingleRecord);
    }
	
	public function valRatesScaleError($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_SCALE_ERROR, $params, $isSingleRecord);
    }
	
	public function valRatesNoProvider($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_RATES_NO_PROVIDER, $params, $isSingleRecord);
    }
	
	public function valRatesNoBuildings($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_RATES_NO_BUILDINGS, $params, $isSingleRecord);
    }
	
	public function valRatesNoMeters($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_RATES_NO_METERS, $params, $isSingleRecord);
    }
	
	public function valBuildingsNoPortfolioManagers($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDINGS_NO_PORTFOLIO_MANAGERS, $params, $isSingleRecord);
    }
	
	public function valBuildingsNoRates($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDINGS_NO_RATES, $params, $isSingleRecord);
    }
	
	public function valBuildingsRateAccountUtilityNotMatching($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDINGS_RATE_ACCOUNT_UTILITY_NOT_MATCHING, $params, $isSingleRecord);
    }
	
	public function valBuildingsNoUnits($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDINGS_NO_UNITS, $params, $isSingleRecord);
    }
	
	public function valBuildingsNoMeters($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDINGS_NO_METERS, $params, $isSingleRecord);
    }
	
	public function valBuildingsNoBulkMeters($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_BUILDINGS_NO_BULK_METERS, $params, $isSingleRecord);
    }
	
	public function valMeterUtilityTypeUnknown($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_METER_UTILITY_TYPE_UNKNOWN, $params, $isSingleRecord);
    }
	
	public function valMetersDuplicate($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_METERS_DUPLICATE, $params, $isSingleRecord);
    }
	
	public function valMeterOverlapPeriod($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_METER_OVERLAP_PERIOD, $params, $isSingleRecord);
    }
	
	public function valMeterDecommissionedIsActive($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_METER_DECOMMISSIONED_ISACTIVE, $params, $isSingleRecord);
    }
	
	public function valMeterDecommissionedWithoutReplacement($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::VAL_METER_DECOMMISSIONED_WITHOUT_REPLACEMENT, $params, $isSingleRecord);
    }

	public function getRestrictionLevel($page_name){
		$MenuList = $_SESSION['BusinessFunctionUser'];
		$IsWritable = $_SESSION['IsWritable'];
		$pagename = array();
		
		$permission = 0;
		foreach($MenuList as $page){
			//Check if string constains a '.' character
			$temp = strrpos($page, '.');
			$pagename[] = $temp === FALSE ? substr($page, $temp):substr($page, $temp+1);
		}
		
		$index = 0;
		for($i = 0; $i < count($pagename); $i++){
			//if Maintenance is found in MenuList
			if(strcasecmp($pagename[$i], 'MAINTENANCE') == 0){
				//get current index
				$index = $i;
				
				if(strcasecmp($page_name, 'PROVIDERS') == 0 ||
				   strcasecmp($page_name, 'RATES') == 0 ||
				   strcasecmp($page_name, 'BUILDINGS') == 0 ||
				   strcasecmp($page_name, 'UNITS') == 0 ||
				   strcasecmp($page_name, 'CUSTOMERS') == 0 ||
				   strcasecmp($page_name, 'METERS') == 0 ||
				   strcasecmp($page_name, 'PARAMETERS') == 0){
					$permission = $IsWritable[$index];
					break;
				}
			}
			else if(strcasecmp($pagename[$i], 'PROCESSING') == 0){
				//get current index
				$index = $i;
				
				if(strcasecmp($page_name, 'PLANNING') == 0 ||
				   strcasecmp($page_name, 'REASONABILITY') == 0 ||
				   strcasecmp($page_name, 'INVOICING') == 0 ||
				   strcasecmp($page_name, 'PREPAID TRANSACTIONS') == 0){
					$permission = $IsWritable[$index];
					break;
				}
			}
			else if(strcasecmp($pagename[$i], 'CREDIT MANAGEMENT') == 0){
				//get current index
				$index = $i;
				
				if(strcasecmp($page_name, 'PLANNING') == 0 ||
				   strcasecmp($page_name, 'REASONABILITY') == 0 ||
				   strcasecmp($page_name, 'INVOICING') == 0 ||
				   strcasecmp($page_name, 'PREPAID TRANSACTIONS') == 0){
					$permission = $IsWritable[$index];
					break;
				}
			}
			else if(strcasecmp($pagename[$i], 'REPORTING') == 0){
				//get current index
				$index = $i;
				
				if(strcasecmp($page_name, 'PROVIDERS') == 0 ||
				   strcasecmp($page_name, 'RATE REPORT') == 0 ||
				   strcasecmp($page_name, 'BUILDING RATE LIST') == 0 ||
				   strcasecmp($page_name, 'PORTFOLIO MANAGER LIST') == 0 ||
				   strcasecmp($page_name, 'SQM METER ALLOCATION LIST') == 0 ||
				   strcasecmp($page_name, 'UNITS') == 0 ||
				   strcasecmp($page_name, 'DEPOSIT LIST') == 0 ||
				   strcasecmp($page_name, 'ACCOUNT DETAIL') == 0 ||
				   strcasecmp($page_name, 'ACCOUNTS WITHOUT CONTACT DETAILS') == 0 ||
				   strcasecmp($page_name, 'OUTSTANDING AGREEMENT LIST') == 0 ||
				   strcasecmp($page_name, 'READINGS IMPORTED') == 0 ||
				   strcasecmp($page_name, 'Meter Test Required') == 0 ||
				   strcasecmp($page_name, 'Estimated Readings') == 0 ||
				   strcasecmp($page_name, 'Reading Review') == 0 ||
				   strcasecmp($page_name, 'Meters Estimated Three Times') == 0 ||
				   strcasecmp($page_name, 'Reading Variance Parameter Analysis') == 0 ||
				   strcasecmp($page_name, 'Internal Prepaid Meter List') == 0 ||
				   strcasecmp($page_name, 'Internal Prepaid Meter Reasonability') == 0 ||
				   strcasecmp($page_name, 'Rate Specific Meter List') == 0 ||
				   strcasecmp($page_name, 'Outstanding Billing List') == 0 ||
				   strcasecmp($page_name, 'Common Property Allocation') == 0 ||
				   strcasecmp($page_name, 'Previous Billing') == 0 ||
				   strcasecmp($page_name, 'Payment Arrangement List') == 0 ||
				   strcasecmp($page_name, 'Overdue Accounts not Notified') == 0 ||
				   strcasecmp($page_name, 'Agreement Outstanding not Notified') == 0 ||
				   strcasecmp($page_name, 'Notified Accounts not Cut') == 0 ||
				   strcasecmp($page_name, 'Settled Accounts not Reconnected') == 0 ||
				   strcasecmp($page_name, 'Standby Cut List') == 0){
					$permission = $IsWritable[$index];
					break;
				}
			}
			else if(strcasecmp($pagename[$i], 'SYSTEM VALIDATION') == 0){
				//get current index
				$index = $i;
				
				if(strcasecmp($page_name, 'Providers with no Rates') == 0 ||
				   strcasecmp($page_name, 'Rates with no Retail or Bulk Rate') == 0 ||
				   strcasecmp($page_name, 'Rates with Scale Error') == 0 ||
				   strcasecmp($page_name, 'Rates with no Provider') == 0 ||
				   strcasecmp($page_name, 'Rates with no Buildings') == 0 ||
				   strcasecmp($page_name, 'Rates with no Meters') == 0 ||
				   strcasecmp($page_name, 'Buildings with no Portfolio Manager') == 0 ||
				   strcasecmp($page_name, 'Buildings with no Rates') == 0 ||
				   strcasecmp($page_name, 'Buildings with Inactive Rates') == 0 ||
				   strcasecmp($page_name, 'Buildings with Rate Utility Mismatch') == 0 ||
				   strcasecmp($page_name, 'Buildings with no Units') == 0 ||
				   strcasecmp($page_name, 'Buildings with no Meters') == 0 ||
				   strcasecmp($page_name, 'Buildings with no Bulk Meters') == 0 ||
				   strcasecmp($page_name, 'Body Corporate Unit with Sub Meters') == 0 ||
				   strcasecmp($page_name, 'Units with Bulk and Service Meters') == 0 ||
				   strcasecmp($page_name, 'Customers with Overlapping Occupancy') == 0 ||
				   strcasecmp($page_name, 'Prepaid Customers with Overlapping Occupancy') == 0 ||
				   strcasecmp($page_name, 'Meter Utility Type Unknown') == 0 ||
				   strcasecmp($page_name, 'Duplicate Meter Number') == 0 ||
				   strcasecmp($page_name, 'Meters with Overlapping Period') == 0 ||
				   strcasecmp($page_name, 'Decommissioned and Is Active') == 0 ||
				   strcasecmp($page_name, 'Decommissioned not Replaced') == 0 ||
				   strcasecmp($page_name, 'RATE SPECIFIC METER WITH INACTIVE RATE') == 0 ||
				   strcasecmp($page_name, 'Reading After Vacancy Date') == 0 ||
				   strcasecmp($page_name, 'Reading Current Negative Consumption') == 0){
					$permission = $IsWritable[$index];
					break;
				}
			}
			else if(strcasecmp($pagename[$i], 'SYSTEM ADMINISTRATION') == 0){
				//get current index
				$index = $i;
				
				if(strcasecmp($page_name, 'PARAMETERS') == 0 ||
				   strcasecmp($page_name, 'COMPANY') == 0 ||
				   strcasecmp($page_name, 'BUSINESS FUNCTION') == 0 ||
				   strcasecmp($page_name, 'USER') == 0 ||
				   strcasecmp($page_name, 'Manual Reading Adjustment') == 0){
					$permission = $IsWritable[$index];
					break;
				}
			}
			else if(strcasecmp($pagename[$i], $page_name) == 0){
				//get current index
				$index = $i;
				$permission = $IsWritable[$index];
				break;
			}
		}
		return $permission;
	}
}
?>