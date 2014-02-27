<?php
class Meter extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function importReading($params) {
    	return $this->executeNoneQuery('[movendus].[ImportReading]', $params);
    }
    
    public function importMeter($params) {
    	return $this->executeNoneQuery('[movendus].[ImportMeter]', $params);
    }
    
	public function getReading($params, $isSingleRecord = false) {
    	if ($isSingleRecord === true) {
        	return $this->getSingleRecord($this->execute('[get].[Reading]', $params));
        }
        
    	return $this->execute('[get].[Reading]', $params);
    }
	public function createReading($params) {
        $this->setReading($params);
        return $this->dbh->lastInsertId('Reading');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateReading($params) {
        return $this->setReading($params);  
    }
	
	private function setReading($params) {
        return $this->executeNoneQuery('[set].[Reading]', $params);
    }
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMovendusMeter($params) {
    	return $this->execute('[movendus].[ExportMeter]', $params);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMeter($params, $isSingleRecord = false) {
        if ($isSingleRecord === true) {
        	return $this->getSingleRecord($this->execute('[get].[Meter]', $params));
        }
        
    	return $this->execute('[get].[Meter]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createMeter($params) {
        $this->setMeter($params);
        return $this->dbh->lastInsertId('Meter');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateMeter($params) {
        return $this->setMeter($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @MeterFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setMeter($params) {
        return $this->executeNoneQuery('[set].[Meter]', $params);
    }
}
?>