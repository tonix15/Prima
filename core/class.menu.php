<?php
class Menu extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the paraMenus of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMenu($params) {
        return $this->execute('[get].[Menu]', $params);  
    }
    
    /**
     * @param refer to setRate($params)
     * @return last inserted id
     */
    public function createMenu($params) {
        $this->setMenu($params);
        return $this->dbh->lastInsertId('Menu');
    }
    
    /**
     * @param refer to setRate($params)
     * @return boolean if the update is successful or not
     */
    public function updateMenu($params) {
        return $this->setMenu($params);  
    }
    
    /**
     * @param array $params contains the paraMenus of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint as @RatePk
     * @array val 3 varchar(20) as @NumberBk
     * @array val 4 varchar(50) as @Name
     * @array val 5 bigint as @MenuFk
     * @array val 6 bit as @IsVATApplicable
     * @array val 7 varchar(100) as @BillingDescription
     * @array val 8 bigint as @ProviderFk
     * @array val 9 bit as @IsActive
     * @return PDOStatement $cmd
     */
    private function setMenu($params) {
        return $this->executeNoneQuery('[set].[Menu]', $params);
    }
}
?>