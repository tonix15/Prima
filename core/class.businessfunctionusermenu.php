<?php
class BusinessFunctionUserMenu extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the paraBusinessFunctionUserMenus of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getBusinessFunctionUserMenu($params) {
        return $this->execute('[get].[BusinessFunctionUserMenu]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createBusinessFunctionUserMenu($params) {
        $this->setBusinessFunctionUserMenu($params);
        return $this->dbh->lastInsertId('BusinessFunctionUserMenu');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateBusinessFunctionUserMenu($params) {
        return $this->setBusinessFunctionUserMenu($params);  
    }
    
    /**
     * @param array $params contains the paraBusinessFunctionUserMenus of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @BusinessFunctionUserMenuFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setBusinessFunctionUserMenu($params) {
        return $this->executeNoneQuery('[set].[BusinessFunctionUserMenu]', $params);
    }
    
    public function getRestrictionLevel($userPK, $var, $page_name) {
    	$business_function_user_menu = $this->getBusinessFunctionUserMenu(array($userPK, $var));
    	$page_name = strtoupper($page_name);	
    	
    	foreach ($business_function_user_menu as $menu) {
			if(substr_count($menu['Menu'], 'MAINTENANCE') > 0){
				switch($page_name){
					case 'BUILDINGS':
					case 'CUSTOMERS':
					case 'METERS':
					case 'PARAMETERS':
					case 'PROVIDERS':
					case 'RATES':
					case 'UNITS':
					return $menu['IsWritable'];
					break;
				}
			}			
			else if(substr_count($menu['Menu'], 'PROCESSING') > 0){
				switch($page_name){
					case 'BILLING':
					case 'INVOICE':
					case 'PLANNING':
					return $menu['IsWritable'];
					break;
				}
			}	
			else if(substr_count($menu['Menu'], 'SYSTEM ADMINISTRATION') > 0){
				switch($page_name){
					case 'BUSINESS FUNCTION':
					case 'COMPANY':
					case 'SYSAD_PARAMETERS1':
					case 'USER':
					return $menu['IsWritable'];
					break;
				}
			}else if(substr_count($menu['Menu'], 'QUERY AND REPORTING') > 0){
				switch($page_name){
					case 'READING':
					case 'DEPOSIT LIST':
					return $menu['IsWritable'];
					break;
				}
				}
    		else if (substr_count($menu['Menu'], $page_name) > 0) {
    			return $menu['IsWritable'];
    		} 
    	}
    
    	return 0;
    }
}
?>