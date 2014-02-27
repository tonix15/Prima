<?php
class Master extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMovendusMaster($params) {
    	return $this->execute('[movendus].[ExportMaster]', $params);
    }
    
    public function getBulkMeterDeviation($params) {
    	return $this->execute('[movendus].[ExportBulkMeterDeviation]', $params);
    }
   
}
?>