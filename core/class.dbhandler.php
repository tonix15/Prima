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
    
    public function getCutNotification($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_CUT_NOTIFICATION, $params, $isSingleRecord);
    }
    
    public function createCutNotification($params) {
    	$this->executeNonQueryStoredProcedure(SP::SET_CUT_NOTIFICATION, $params);
    	return $this->getLastInsertId(PrimaDB::CREDIT_MANAGEMENT_TABLE);
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
    
    /** Contact Person */
    public function getContactPerson($params, $isSingleRecord = false) {
    	return $this->executeQueryStoredProcedure(SP::GET_CONTACT_PERSON, $params, $isSingleRecord);
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
	
	/** Reason */
	public function movendusExportReason($params) {
		return $this->executeQueryStoredProcedure(SP::MOVENDUS_EXPORT_REASON, $params);
	}
	
	/** Reading */
	public function movendusImportReading($params) {
		$this->executeNonQueryStoredProcedure(SP::MOVENDUS_IMPORT_READING, $params);
		return $this->getLastInsertId(PrimaDB::READING_TABLE);
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
}
?>