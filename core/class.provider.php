<?php
class Provider extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param int $param1 as userPk
     * @param int $param2 as providerPk
     * @return record set
     */
    public function getProvider($params) {
        return $this->execute('[get].[Provider]', $params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @array val 2 int as @ProviderPk
     * @array val 3 int as @NumberBk
     * @array val 4 int as @Name
     * @array val 5 int as @ProviderContactPersonFk
     * @array val 6 int as @IsActive
     * @array val 7 int as @Comment
     * @return last inserted id
     */
    public function createProvider($params) {
        $this->setProvider($params);
        return $this->dbh->lastInsertId('Provider');
    }
    
    public function updateProvider($params) {
        return $this->setProvider($params);  
    }
    
    private function setProvider($params) {
        return $this->executeNoneQuery('[set].[Provider]', $params);
    }
}
?>