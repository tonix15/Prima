<?php
class Building extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    public function getBuildingBillingPeriod($params, $isSingleData = false) {
    	if ($isSingleData === true) {
    		return $this->getSingleRecord($this->execute('[get].[BuildingBillingPeriod]', $params));
    	}
    
    	return $this->execute('[get].[BuildingBillingPeriod]', $params);
    }
    
    public function createBuildingBillingPeriod($params) {
    	$this->setBuildingBillingPeriod($params);
    	return $this->dbh->lastInsertId('BuildingBillingPeriod');
    }
	
    private function setBuildingBillingPeriod($params) {
    	return $this->executeNoneQuery('[set].[BuildingBillingPeriod]', $params);
    }
    
    public function updateBuildingBillingPeriod($params) {
    	return $this->setBuildingBillingPeriod($params);
    }
	
	public function getBuildingBilling($params, $isSingleData = false) {
        if ($isSingleData === true) {
        	return $this->getSingleRecord($this->execute('[get].[BuildingBilling]', $params));
        }
        
    	return $this->execute('[get].[BuildingBilling]', $params);  
		
    }
    
    public function getBuildingTerminationPeriod($params, $isSingleData = false) {
    	if ($isSingleData === true) {
    		return $this->getSingleRecord($this->execute('[get].[BuildingTerminationPeriod]', $params));
    	}
    
    	return $this->execute('[get].[BuildingTerminationPeriod]', $params);
    
    }
	
	public function setBuildingBilling($params){
		return $this->executeNoneQuery('[set].[BuildingBilling]', $params);
	}
   
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getBuilding($params, $isSingleData = false) {
        if ($isSingleData === true) {
        	return $this->getSingleRecord($this->execute('[get].[Building]', $params));
        }
        
    	return $this->execute('[get].[Building]', $params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getBuildingPortfolioManager($params, $isSingleData = false) {
    	if ($isSingleData === true) {
    		return $this->getSingleRecord($this->execute('[get].[BuildingPortfolioManager]', $params));
    	}
    
    	return $this->execute('[get].[BuildingPortfolioManager]', $params);
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createBuilding($params) {
        $this->setBuilding($params);
        return $this->dbh->lastInsertId('Building');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateBuilding($params) {
        return $this->setBuilding($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @BuildingFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setBuilding($params) {
        return $this->executeNoneQuery('[set].[Building]', $params);
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateBuildingPlanning($params) {
    	return $this->setBuildingPlanning($params);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @BuildingFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setBuildingPlanning($params) {
    	return $this->executeNoneQuery('[set].[BuildingPlanning]', $params);
    }
     
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getBuildingRateAccount($params) {
        return $this->execute('[get].[BuildingRateAccount]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createBuildingRateAccount($params) {
        $this->setBuildingRateAccount($params);
        return $this->dbh->lastInsertId('BuildingRateAccount');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateBuildingRateAccount($params) {
        return $this->setBuildingRateAccount($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMovendusBuilding($params) {
    	return $this->execute('[movendus].[ExportBuilding]', $params);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @BuildingRateAccountFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setBuildingRateAccount($params) {
        return $this->executeNoneQuery('[set].[BuildingRateAccount]', $params);
    }
}
?>