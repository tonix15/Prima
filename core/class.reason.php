<?php
class Reason extends StoredProcedures {
    public function __construct(&$dbh) {
        parent::__construct($dbh);
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getMovendusReason($params) {
    	return $this->execute('[movendus].[ExportReason]', $params);
    }
   
}
?>