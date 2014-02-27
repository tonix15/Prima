<?php
class ReasonType extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getReasonType($params) {
        return $this->execute('[get].[ReasonType]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createReasonType($params) {
        $this->setReasonType($params);
        return $this->dbh->lastInsertId('ReasonType');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateReasonType($params) {
        return $this->setReasonType($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @ReasonTypeFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setReasonType($params) {
        return $this->executeNoneQuery('[set].[ReasonType]', $params);
    }
}
?>