<?php
class Rate extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @array val 2 int as @RatePk
     * @return recordset of rates
     */
    public function getRate($params) {
        return $this->execute('[get].[Rate]', $params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @array val 2 int as @ScalePk
     * @return recordset of rate scales
     */
    public function getScale($params) {
        return $this->execute('[get].[Scale]', $params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @array val 2 int as @ScalePk
     * @return recordset of fixed rates
     */
    public function getFixedRate($params) {
        return $this->execute('[get].[FixedRate]', $params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @array val 2 int as @ScalePk
     * @return recordset of fixed rates
     */
    public function getFixedFee($params) {
        return $this->execute('[get].[FixedFee]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createRate($params) {
        $this->setRate($params);
        return $this->dbh->lastInsertId('Rate');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateRate($params) {
        return $this->setRate($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @UtilityTypeFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setRate($params) {
        return $this->executeNoneQuery('[set].[Rate]', $params);
    }
    
    /**
     * @param refer to setFixScale($params)
     * @return last inserted id
     */
    public function createScale($params) {
        $this->setFixScale($params);
        return $this->dbh->lastInsertId('Scale');
    }
    
    /**
     * @param refer to setFixScale($params)
     * @return boolean if the update is successful or not
     */
    public function updateScale($params) {
        return $this->setFixScale($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @UtilityTypeFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 bit as @IsRetail
     * @array val 8 varchar(100) as @BillingDescription
     * @array val 7 bigint as @ProviderFk
     * @array val 7 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setFixScale($params) {
        return $this->executeNoneQuery('[set].[Scale]', $params);
    }
    
    /**
     * @param refer to setFixedRate($params)
     * @return last inserted id
     */
    public function createFixedRate($params) {
        $this->setFixedRate($params);
        return $this->dbh->lastInsertId('FixedRate');
    }
    
    /**
     * @param refer to setFixedRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateFixedRate($params) {
        return $this->setFixedRate($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @UtilityTypeFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 bit as @IsRetail
     * @array val 8 varchar(100) as @BillingDescription
     * @array val 7 bigint as @ProviderFk
     * @array val 7 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setFixedRate($params) {
        return $this->executeNoneQuery('[set].[FixedRate]', $params);
    }
    
    /**
     * @param refer to createFixedFee($params)
     * @return last inserted id
     */
    public function createFixedFee($params) {
        $this->setFixedFee($params);
        return $this->dbh->lastInsertId('FixedFee');
    }
    
    /**
     * @param refer to createFixedFee($params)
     * @return boolean if the update is successful or not
     */
    public function updateFixedFee($params) {
        return $this->setFixedFee($params);  
    }
       
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @UtilityTypeFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 bit as @IsRetail
     * @array val 8 varchar(100) as @BillingDescription
     * @array val 7 bigint as @ProviderFk
     * @array val 7 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setFixedFee($params) {
        return $this->executeNoneQuery('[set].[FixedFee]', $params);
    } 
}
?>