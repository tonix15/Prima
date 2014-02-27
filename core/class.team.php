<?php
class Team extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getTeam($params, $isSingleData = false) {
        if ($isSingleData === true) {
        	return $this->getSingleRecord($this->execute('[get].[Team]', $params));
        }        
    	return $this->execute('[get].[Team]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createTeam($params) {
        $this->setTeam($params);
        return $this->dbh->lastInsertId('Team');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateTeam($params) {
        return $this->setTeam($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @TeamFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setTeam($params) {
        return $this->executeNoneQuery('[set].[Team]', $params);
    }
}
?>