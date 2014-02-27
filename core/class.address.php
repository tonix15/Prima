<?php
class Address extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getAddress($params, $isSingleRecord = false) {
        if ($isSingleRecord) {
        	return $this->getSingleRecord($this->execute('[get].[Address]', $params));
        }
        
        return $this->execute('[get].[Address]', $params);
    }
    
    public function createAddress($params) {
        $this->setAddress($params);
        return $this->dbh->lastInsertId('Address');
    }
    
    public function updateAddress($params) {
        return $this->setAddress($params);  
    }

    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint $param1 as @UserPk
     * @array val 2 bigint $param2 as @AddressPk
     * @array val 3 varchar(100) as @Address1
     * @array val 4 varchar(100) as @Address2
     * @array val 5 varchar(100) as @Address3
     * @array val 6 varchar(100) as @Address4
     * @array val 8 varchar(10) as @PostalCode
     * @return last inserted id or boolean if update was successful
     */
    private function setAddress($params) {
        return $this->executeNoneQuery('[set].[Address]', $params);
    }
}
?>