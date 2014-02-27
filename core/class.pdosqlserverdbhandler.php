<?php
class PDOSQLServerdbhandler {
	private $db;
	
    protected function __construct(PrimaDB $db) {
        $this->db = $db->getRewritableDatabase();
    }
    
    protected function __destruct() {
    	$this->closeDBConnection();	
    }
    
    public function closeDBConnection() {
    	$this->db = null;
    }
    
    protected function executeNonQueryStoredProcedure($procedure_name, $params) {
    	$result = $this->executeStoredProcedure($procedure_name, $params, true);
        return !is_null ($result) ? $result : false;
    }
    
    protected function getLastInsertId($table_name = null) {
    	return $this->db->lastInsertId($table_name);
    }
    
    protected function executeQueryStoredProcedure($procedure_name, $params, $isSingleRecord = false) {
    	$cmd = $this->executeStoredProcedure($procedure_name, $params);
        
        if (!is_null ($cmd)) {
        	return $isSingleRecord === true ? $cmd->fetch(PDO::FETCH_ASSOC) : $cmd->fetchAll(PDO::FETCH_ASSOC);
        } 
        
        return null;
    }
    
    private function executeStoredProcedure($procedure_name, $params, $isNonQuery = false) {    
    	if (!$this->isMultiDimensional($params)) {
	    	$temp = array();
	        $len = count($params);
	        $param_count = 0;
	        $param_args = '';
	        
	        for ($i = 0; $i < $len; $i++) {
	            $param_count = $i + 1;
	            $temp['param' . $param_count] = $params[$i];
	            $param_args .= ':param' . $param_count . ', ';
	        }
	        
	        $params = $temp;
	        $param_args = rtrim($param_args, ', ');
	
	        $sql = "{CALL " . $procedure_name . " (" . $param_args . ")}";
	        
        	if ($this->db instanceof PDO) {
        		$cmd = $this->db->prepare($sql);
        		foreach ($params as $key => $val) {
        			$cmd->bindValue($key, $val);
        		}
        		 
        		if ($isNonQuery) {
        			return $cmd->execute();
        		}
        		 
        		$cmd->execute();
        		return $cmd;
        	} else {
        		echo '<br />Database connection error.';
        	}
    	} else {
    		echo '<br />procedure does not accept multi-dimensional array parameters.';
    	}
    	
    	return null;	
    }
    
    private function isMultiDimensional($arrs) {
    	foreach ($arrs as $arr) {
    		if (is_array($arr)) {
    			return true;
    		}
    	}
    	return false;
    }
}
?>