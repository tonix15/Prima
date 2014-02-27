<?php
class Unit extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMovendusUnit($params) {
    	return $this->execute('[movendus].[ExportUnit]', $params);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getUnit($params, $isSingleData = false) {
        if ($isSingleData === true) {
        	return $this->getSingleRecord($this->execute('[get].[Unit]', $params));
        }
    	return $this->execute('[get].[Unit]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createUnit($params) {
        $this->setUnit($params);
        return $this->dbh->lastInsertId('Unit');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateUnit($params) {
        return $this->setUnit($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @UnitFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setUnit($params) {
        return $this->executeNoneQuery('[set].[Unit]', $params);
    }
}
?>