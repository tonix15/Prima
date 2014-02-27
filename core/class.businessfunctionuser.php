<?php
class BusinessFunctionUser extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getBusinessFunctionUser($params) {
        return $this->execute('[get].[BusinessFunctionUser]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createBusinessFunctionUser($params) {
        $this->setBusinessFunctionUser($params);
        return $this->dbh->lastInsertId('BusinessFunctionUser');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateBusinessFunctionUser($params) {
        return $this->setBusinessFunctionUser($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @BusinessFunctionUserFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setBusinessFunctionUser($params) {
        return $this->executeNoneQuery('[set].[BusinessFunctionUser]', $params);
    }
}
?>