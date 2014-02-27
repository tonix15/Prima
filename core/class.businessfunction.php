<?php
class BusinessFunction extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getBusinessFunction($params) {
        return $this->execute('[get].[BusinessFunction]', $params);  
    }
    
    public function createBusinessFunction($params) {
        $this->setBusinessFunction($params);
        return $this->dbh->lastInsertId('BusinessFunction');
    }
    
    public function updateBusinessFunction($params) {
        return $this->setBusinessFunction($params);  
    }

    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint $param1 as @UserPk
     * @array val 2 bigint $param2 as @BusinessFunctionPk
     * @array val 3 varchar(100) as @BusinessFunction1
     * @array val 4 varchar(100) as @BusinessFunction2
     * @array val 5 varchar(100) as @BusinessFunction3
     * @array val 6 varchar(100) as @BusinessFunction4
     * @array val 8 varchar(10) as @PostalCode
     * @return last inserted id or boolean if update was successful
     */
    private function setBusinessFunction($params) {
        return $this->executeNoneQuery('[set].[BusinessFunction]', $params);
    }
}
?>