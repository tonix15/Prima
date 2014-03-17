<?php
class SP {
	/** Address */
	const GET_ADDRESS = '[get].[Address]';
	const SET_ADDRESS = '[set].[Address]';
	
	/** Allocation Type */
	const GET_ALLOCATION_TYPE = '[get].[AllocationType]';
	const SET_ALLOCATION_TYPE = '[set].[AllocationType]';
	
	/** Arrangement List */
	const REP_ARRANGEMENT_LIST = '[rep].[ArrangementList]';
	
	/** Billing */
	const GET_BILLING_REASONABILITY = '[get].[BillingReasonability]';
	const SET_BILLING_REASONABILITY = '[set].[BillingReasonability]';
	const GET_BILLING = '[get].[Billing]';
	const SET_BILLING = '[set].[Billing]';
	const GET_BILLING_ACCOUNT = '[get].[BillingAccount]';
	const SET_BILLING_ACCOUNT = '[set].[BillingAccount]';
	
	const BODY_CORPORATE_UNIT_WITH_SUB_METER = '[val].[BCUnitWithSubMeter]';
	
	/** Building */
	const GET_BUILDING = '[get].[Building]';
	const SET_BUILDING = '[set].[Building]';
	const GET_BUILDING_BILLING = '[get].[BuildingBilling]';
	const SET_BUILDING_BILLING = '[set].[BuildingBilling]';
	const SET_BUILDING_PLANNING = '[set].[BuildingPlanning]';
	const GET_BUILDING_BILLING_PERIOD = '[get].[BuildingBillingPeriod]';
	const SET_BUILDING_BILLING_PERIOD = '[set].[BuildingBillingPeriod]';	
	const GET_BUILDING_PORTFOLIO_MANAGER = '[get].[BuildingPortfolioManager]';
	const GET_BUILDING_RATE_ACCOUNT = '[get].[BuildingRateAccount]';
	const SET_BUILDING_RATE_ACCOUNT = '[set].[BuildingRateAccount]';
	const GET_BUILDING_TERMINATION_PERIOD = '[get].[BuildingTerminationPeriod]';
	const SET_BUILDING_TERMINATION_PERIOD = '[set].[BuildingTerminationPeriod]';
	const MOVENDUS_EXPORT_BUILDING = '[movendus].[ExportBuilding]';
	const VAL_BUILDING_INACTIVE_RATE = '[val].[BuildingInActiveRate]';
	const VAL_BUILDING_ACTIVE_RATE = '[val].[BuildingActiveRate]';
	const VAL_BUILDING_SQUARE_METER_ALLOCATION = '[val].[BuildingSqrMeterAllocation]';
	
	/** Building Type */
	const GET_BUILDING_TYPE = '[get].[BuildingType]';
	const SET_BUILDING_TYPE = '[set].[BuildingType]';
	
	/** Business Function */
	const GET_BUSINESS_FUNCTION = '[get].[BusinessFunction]';
	const SET_BUSINESS_FUNCTION = '[set].[BusinessFunction]';
	
	/** Business Function Menu */
	const GET_BUSINESS_FUNCTION_MENU = '[get].[BusinessFunctionMenu]';
	const SET_BUSINESS_FUNCTION_MENU = '[set].[BusinessFunctionMenu]';
	
	/** Business Function User */
	const GET_BUSINESS_FUNCTION_USER = '[get].[BusinessFunctionUser]';
	const SET_BUSINESS_FUNCTION_USER = '[set].[BusinessFunctionUser]';
	
	/** Business Function User Menu */
	const GET_BUSINESS_FUNCTION_USER_MENU = '[get].[BusinessFunctionUserMenu]';
	const SET_BUSINESS_FUNCTION_USER_MENU = '[set].[BusinessFunctionUserMenu]';
	
	/** Company */
	const GET_COMPANY = '[get].[Company]';
	const SET_COMPANT = '[set].[Company]';
	const SET_COMPANY_BUILDING = '[set].[CompanyBuilding]';
	const GET_COMPANT_USER = '[get].[CompanyUser]';
	const SET_COMPANT_USER = '[set].[CompanyUser]';
	
	/** Contact Person */
	const GET_CONTACT_PERSON = '[get].[ContactPerson]';
	const SET_CONTACT_PERSON = '[set].[ContactPerson]';
	
	/** Credit Management */
	const SET_CREDIT_MANAGEMENT = '[set].[CreditManagement]';
	
	/** Customer */
	const GET_CUSTOMER = '[get].[Customer]';
	const SET_CUSTOMER = '[set].[Customer]';
	const VAL_CUSTOMER_WITH_OVERLAPPING_OCCUPANCY = '[val].[OccupancyOverlap]';
	const VAL_OCCUPANCY_OVERLAP_PREPAID = '[val].[OccupancyOverlapPrepaid]';
	
	/** Cut Notification */
	const GET_CUT_NOTIFICATION = '[get].[CutNotification]';
	const SET_CUT_NOTIFICATION = '[set].[CutNotification]';
	
	/** Cut Instruction */
	const GET_CUT_INSTRUCTION = '[get].[CutInstruction]';
	const SET_CUT_INSTRUCTION = '[set].[CutInstruction]';
	
	/** Reconnection Instruction */ 
	const GET_RECONNECTION_INSTRUCTION = '[get].[ReconnectionInstruction]';
	const SET_RECONNECTION_INSTRUCTION = '[set].[ReconnectionInstruction]';
	const GET_INSTANT_RECONNECTION_INSTRUCTION = '[get].[InstantReconnectionInstruction]';
	const SET_INSTANT_RECONNECTION_INSTRUCTION = '[set].[InstantReconnectionInstruction]';
	
	/** Invoice */
	const GET_INVOICE = '[get].[Invoice]';
	
	/** Language Type */
	const GET_LANGUAGE_TYPE = '[get].[LanguageType]';
	const SET_LANGUAGE_TYPE = '[set].[LanguageType]';
	
	/** Master */
	const MOVENDUS_EXPORT_MASTER = '[movendus].[ExportMaster]';
	const MOVENDUS_EXPORT_BULK_METER_DEVIATION = '[movendus].[ExportBulkMeterDeviation]';
	
	/** Menu */
	const GET_MENU = '[get].[Menu]';
	const SET_MENU = '[set].[Menu]';
	
	/** Meter */
	const MOVENDUS_IMPORT_READING = '[movendus].[ImportReading]';
	const MOVENDUS_IMPORT_METER = '[movendus].[ImportMeter]';
	const MOVENDUS_EXPORT_METER = '[movendus].[ExportMeter]';
	const MOVENDUS_EXPORT_TERMINATION_METER = '[movendus].[ExportTerminationMeter]';
	const GET_READING = '[get].[Reading]';
	const SET_READING = '[set].[Reading]';
	const GET_METER = '[get].[Meter]';
	const SET_METER = '[set].[Meter]';
	const VAL_METER_IN_ACTIVE_RATE = '[val].[MeterInActiveRate]';
	const VAL_METER_ACTIVE_RATE = '[val].[MeterActiveRate]';
	
	/** Meter Type */
	const MOVENDUS_EXPORT_METER_TYPE = '[movendus].[ExportMeterType]';
	const GET_METER_TYPE = '[get].[MeterType]';
	const SET_METER_TYPE = '[set].[MeterType]';
	
	/** Preferred Contact Type */
	const GET_PREFERRED_CONTACT_TYPE = '[get].[PreferredContactType]';
	const SET_PREFERRED_CONTACT_TYPE = '[set].[PreferredContactType]';
	
	/** Prepaid Transactions */
	const SET_PREPAID_TRANSACTIONS = '[set].[PrepaidTransactions]';
	
	/** Provider */
	const GET_PROVIDER = '[get].[Provider]';
	const SET_PROVIDER = '[set].[Provider]';
	
	/** Rate */
	const GET_FIXED_FEE = '[get].[FixedFee]';
	const SET_FIXED_FEE = '[set].[FixedFee]';
	const GET_FIXED_RATE = '[get].[FixedRate]';
	const SET_FIXED_RATE = '[set].[FixedRate]';
	const GET_RATE = '[get].[Rate]';
	const SET_RATE = '[set].[Rate]';
	const GET_SCALE = '[get].[Scale]';
	const SET_SCALE = '[set].[Scale]';
	
	const READING_AFTER_VACANCY_DATE = '[val].[ReadingAfterVacancyDate]';
	const READING_NEGATIVE = '[val].[ReadingNegative]';
	
	/** Reason */
	const MOVENDUS_EXPORT_REASON = '[movendus].[ExportReason]';
	
	/** Reason Type */
	const GET_REASON_TYPE = '[get].[ReasonType]';
	const SET_REASON_TYPE = '[set].[ReasonType]';
	
	/*** Reporting */
	const COMMON_PROPERTY_NO_PREVIOUS_READING = '[rep].[CommonPropertyNoPreviousReading]';
	const COMMON_PROPERTY_NEGATIVE_CONSUMPTION = '[rep].[CommonPropertyNegativeConsumption]';
	const COMMON_PROPERTY_UNDER_RECOVERY_DETAIL = '[rep].[CommonPropertyUnderRecoveryDetail]';
	const COMMON_PROPERTY_ALLOCATION = '[rep].[CommonPropertyAllocation]';
	const READING_IMPORT = '[rep].[ReadingImport]';
	const READING_TEST_METER = '[rep].[ReadingTestMeter]';
	const READING_ESTIMATED = '[rep].[ReadingEstimated]';
	const READING_ESTIMATED_THREE_TIMES = '[rep].[ReadingEstimated3Times]';
	const READING_EXCEPTIONAL = '[rep].[ReadingExceptional]';
	const READING_FACTOR = '[rep].[ReadingFactor]';
	const OUTSTANDING_AGREEMENT = '[rep].[CrManAgreementOutstanding]';
	const CUSTOMER_NO_CONTACT_DETAIL = '[rep].[CrManCutInstructionNoContactDetail]';
	const OUTSTANDING_BILLING_LIST = '[rep].[OutstandingBillingList]';
	const OUTSTANDING_BILLING_DETAIL = '[rep].[OutstandingBillingDetail]';
	const CUT_INSTRUCTION_NOT_NOTIFIED = '[rep].[CrManCutInstructionNotNotified]';
	const CUT_INSTRUCTION_NOT_CUT = '[rep].[CrManCutInstructionNotCut]';
	const BUILDING_PORTFOLIO_MANAGER_LIST = '[rep].[BuildingPortfolioManagerList]';
	const INTERNAL_PREPAID_METER_LIST = '[rep].[InternalPrepaidMeterList]';
	
	/** Sage **/
	const SAGE_IMPORT_DEPOSIT = '[sage].[ImportDeposit]';
	const SAGE_EXPORT_CUSTOMER = '[sage].[ExportCustomer]';
	const SAGE_IMPORT_ACCOUNT_DETAIL = '[sage].[ImportAccountDetail]';
	const SAGE_IMPORT_INVOICE_DETAIL = '[sage].[ImportInvoiceDetail]';
	
	/** Team */
	const GET_TEAM = '[get].[Team]';
	const SET_TEAM = '[set].[Team]';
	
	/** Title Type */
	const GET_TITLE_TYPE = '[get].[TitleType]';
	const SET_TITLE_TYPE = '[set].[TitleType]';
	
	/** Unit */
	const MOVENDUS_EXPORT_UNIT = '[movendus].[ExportUnit]';
	const GET_UNIT = '[get].[Unit]';
	const SET_UNIT = '[set].[Unit]';
	const UNITS_WITH_BULK_AND_SERVICE_METERS = '[val].[UnitWithBulkOrServiceMeter]';
	
	/** User */
	const GET_USER = '[get].[User]';
	const SET_USER = '[set].[User]';
	const GET_USER_LOGIN = '[get].[UserLogin]';
	
	/** Utility Type */
	const MOVENDUS_EXPORT_UTILITY_TYPE = '[movendus].[ExportUtilityType]';
	const GET_UTILITY_TYPE = '[get].[UtilityType]';
	const SET_UTILITY_TYPE = '[set].[UtilityType]';	
	
	/** Validation */
	const VAL_PROVIDERS_NO_RATES = '[val].[ProvidersNoRates]';
	const VAL_RATES_NO_BULK_OR_RETAIL = '[val].[RatesNoBulkOrRetail]';
	const VAL_SCALE_ERROR = '[val].[RatesScaleError]';
	const VAL_RATES_NO_PROVIDER = '[val].[RatesNoProvider]';
	const VAL_RATES_NO_BUILDINGS = '[val].[RatesNoBuildings]';
	const VAL_RATES_NO_METERS = '[val].[RatesNoMeters]';
	const VAL_BUILDINGS_NO_PORTFOLIO_MANAGERS = '[val].[BuildingsNoPortfolioManagers]';
	const VAL_BUILDINGS_NO_RATES = '[val].[BuildingsNoRates]';
	const VAL_BUILDINGS_RATE_ACCOUNT_UTILITY_NOT_MATCHING = '[val].[BuildingRateAccountUtilityNotMatching]';
	const VAL_BUILDINGS_NO_UNITS = '[val].[BuildingsNoUnits]';
	const VAL_BUILDINGS_NO_METERS = '[val].[BuildingsNoMeters]';
	const VAL_BUILDINGS_NO_BULK_METERS = '[val].[BuildingsNoBulkMeters]';
	const VAL_METER_UTILITY_TYPE_UNKNOWN = '[val].[MeterUtilityTypeUnknown]';
	const VAL_METERS_DUPLICATE = '[val].[MetersDuplicate]';
	const VAL_METER_OVERLAP_PERIOD = '[val].[MeterOverlapPeriod]';
	const VAL_METER_DECOMMISSIONED_ISACTIVE = '[val].[MeterDecommisionedIsActive]';
	const VAL_METER_DECOMMISSIONED_WITHOUT_REPLACEMENT = '[val].[MeterDecommisionedWithoutReplacement]';
}
?>