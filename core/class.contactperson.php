<?php
class ContactPerson extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    /**
     * @param int $param1 as userPk
     * @param int $param2 as contactPersonPK
     * @return record set
     */
    public function getContactPerson($params) {
        return $this->execute('[get].[ContactPerson]', $params);
    }

    public function createContactPerson($params) {
        $this->setContactPerson($params);
        return $this->dbh->lastInsertId('ContactPerson');
    }
    
    public function updateContactPerson($params) {
        return $this->setContactPerson($params);  
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint as @UserPk
     * @array val 2 bigint $param2 as @ContactPersonPk
     * @array val 3 varchar(100) $param3 as @Name
     * @array val 4 varchar(100) $param4 as @Email
     * @array val 5 varchar(100) $param5 as @Cellphone
     * @array val 6 varchar(100) $param6 as @AlternatePhone
     * @array val 7 varchar(100) $param7 as @Fax
     * @array val 8 bigint $param8 as @PreferredContactTypeFk
     * @array val 9 bigint $param9 as @AddressFk
     * @return last inserted id or boolean if update was successful
     */
    private function setContactPerson($params) {
        return $this->executeNoneQuery('[set].[ContactPerson]', $params);
    }
}
?>