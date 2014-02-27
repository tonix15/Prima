<?php
class Company extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getCompany($params) {
        return $this->execute('[get].[Company]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createCompany($params) {
        $this->setCompany($params);
        return $this->dbh->lastInsertId('Company');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateCompany($params) {
        return $this->setCompany($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @CompanyFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setCompany($params) {
        return $this->executeNoneQuery('[set].[Company]', $params);
    }
    
    public function createCompanyBuilding($params) {
    	$this->setCompanyBuilding($params);
    	return $this->dbh->lastInsertId('CompanyBuilding');
    }
    
    private function setCompanyBuilding($params) {
    	return $this->executeNoneQuery('[set].[CompanyBuilding]', $params);
    }
    
    public function getCompanyUser($params, $isSingleRecord = false) {
    	if ($isSingleRecord === true) {
    		return $this->getSingleRecord($this->execute('[get].[CompanyUser]', $params));
    	}
    	
    	return $this->execute('[get].[CompanyUser]', $params);
    }
    
    public function createCompanyUser($params) {
    	$this->setCompanyUser($params);
    	return $this->dbh->lastInsertId('CompanyUser');
    }

    public function updateCompanyUser($params) {
    	return $this->setCompanyUser($params);
    }
    
    private function setCompanyUser($params) {
    	return $this->executeNoneQuery('[set].[CompanyUser]', $params);
    }
}
?>