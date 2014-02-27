<?php
class PreferredContactType extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
  
   /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @array val 2 int as @PreferredContactTypePk
     * @return recordset of preferred contact types
     */
    public function getPreferredContactType($params) {
        return $this->execute('[get].[PreferredContactType]', $params);
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createPreferredContactType($params) {
        $this->setPreferredContactType($params);
        return $this->dbh->lastInsertId('PreferredContactType');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updatePreferredContactType($params) {
        return $this->setPreferredContactType($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @PreferredContactTypeFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setPreferredContactType($params) {
        return $this->executeNoneQuery('[set].[PreferredContactType]', $params);
    }
}
?>