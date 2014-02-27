<?php
class Billing extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    public function getBillingReasonability($params) {
    	return $this->execute('[get].[BillingReasonability]', $params);
    }
    
    public function updateBillingReasonability($params) {
    	return $this->setBillingReasonability($params);
    }
    
    private function setBillingReasonability($params) {
    	return $this->executeNoneQuery('[set].[BillingReasonability]', $params);
    }
    
    public function getBilling($params) {
        return $this->execute('[get].[Billing]', $params);  
    }
    
    public function createBilling($params) {
        $this->setBilling($params);
        return $this->dbh->lastInsertId('Billing');
    }

    public function updateBilling($params) {
        return $this->setBilling($params);  
    }
    
    private function setBilling($params) {
        return $this->executeNoneQuery('[set].[Billing]', $params);
    }
    
    public function getBillingAccount($params) {
        return $this->execute('[get].[BillingAccount]', $params);  
    }

    public function createBillingAccount($params) {
        $result = $this->setBillingAccount($params);
       return $result ? $this->dbh->lastInsertId('BillingAccount') : 0;
		
    }
    
    public function updateBillingAccount($params) {
        return $this->setBillingAccount($params);  
    }
    
    private function setBillingAccount($params) {
        return $this->executeNoneQuery('[set].[BillingAccount]', $params);
    }  
}
?>