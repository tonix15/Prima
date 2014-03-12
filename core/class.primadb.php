<?php
class PrimaDB {	
	/** Table */
	const BUILDING_RATE_ACCOUNT_TABLE = 'BuildingRateAccount';
	const BUILDING_TYPE_TABLE = 'BuildingType';
	const CREDIT_MANAGEMENT_TABLE = 'CreditManagement';
	const CUSTOMER_TABLE = 'Customer';
	const UTILITY_TYPE_TABLE = 'UtilityType';	
	const UNIT_TABLE = 'Unit';
	const RATE_TABLE = 'Rate';
	const SCALE_TABLE = 'Scale';
	const BUILDING_TABLE = 'Building';
	const FIXEDRATE_TABLE = 'FixedRate';
	const FIXEDFEE_TABLE = 'FixedFee';
	const LANGUAGE_TYPE_TABLE = 'LanguageType';
	const PREFERRED_CONTACT_TYPE_TABLE = 'PreferredContactType';
	const READING_TABLE = 'Reading';
	const REASON_TYPE_TABLE = 'ReasonType';
	const TEAM_TABLE = 'Team';
	const TITLE_TYPE_TABLE = 'TitleType';
	
	
	/** Constant DB values */
	const SYSTEM_TYPE_ESTIMATE_REASON_PK = 1;
	const SYSTEM_TYPE_TEST_METER_RESULT_PK = 2;
	
	private $db;
    public function __construct($driver, $server, $dbname, $dbuser, $dbpassword) {
    	/* Database configuration */
    	try {
    		$db_options = array();
    		require_once DOCROOT . '/config/dbconfig.php';
    		$this->db = new PDO($driver . ':Server=' . $server . ';Database=' . $dbname, $dbuser, $dbpassword );
    	} catch(PDOException $e) {
    		die(
    			"Error: Unable to connect to the database.<br />
		        Please edit the database configuration found in dbconfig.php <br />under config folder
		        using your own preference."
    		);
    	}
    }
    
    public function getRewritableDatabase() {
    	return $this->db;
    }
}
?>