<?php
class Sage extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    public function importDeposit($params) {
        return $this->execute('[sage].[ImportDeposit]', $params);  
    }
    
	public function ExportCustomer($params) {
        return $this->execute('[sage].[ExportCustomer]', $params);  
    }
}
?>