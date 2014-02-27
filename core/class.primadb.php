<?php
class PrimaDB {	
	const CREDIT_MANAGEMENT_TABLE = 'CreditManagement';
	const CUSTOMER_TABLE = 'Customer';
	const UTILITY_TYPE_TABLE = 'UtilityType';	
	const UNIT_TABLE = 'Unit';
	const RATE_TABLE = 'Rate';
	const SCALE_TABLE = 'Scale';
	const BUILDING_TABLE = 'Building';
	const FIXEDRATE_TABLE = 'FixedRate';
	const FIXEDFEE_TABLE = 'FixedFee';
	const BUILDING_RATE_ACCOUNT_TABLE = 'BuildingRateAccount';
	const READING_TABLE = 'Reading';
	
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