<?php
class StoredProcedures {
    protected $dbh;
    
    protected function __construct(&$dbh) {
        $this->dbh = &$dbh;
    }
    protected function executeNoneQuery($procedure_name, $params) {
    	$result = $this->executeQuery($procedure_name, $params, true);
        return !is_null ($result) ? $result : false;
    }
    
    protected function execute($procedure_name, $params) {
    	$cmd = $this->executeQuery($procedure_name, $params);
        if (!is_null ($cmd)) {
    		return $cmd->fetchAll(PDO::FETCH_ASSOC);
        } 
        return null;
    }
    
    private function executeQuery($procedure_name, $params, $isNonQuery = false) {    
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
	        $cmd = $this->dbh->prepare($sql);
	        foreach ($params as $key => $val) {
	            $cmd->bindValue($key, $val);
	        }
	        
	        if ($isNonQuery) {
	        	return $cmd->execute();
	        }
	        
	        $cmd->execute();
	        return $cmd;
    	} else {
    		echo '<br />procedure does not accept multi-dimensional array parameters.';
    	}
    	
    	return null;	
    }
    
    public function getSingleRecord($records) {
        if (is_array($records) && !empty($records)) {
	        return !empty($records[0]) ? $records[0] : null;
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
    
    public static function displayParams($params) {
    	echo '<pre>';
    	print_r($params);
    	echo '</pre>';
    }
	
	public static function debug($params){
		echo '<pre>';
    	var_dump($params);
    	echo '</pre>';
	}
    
    public static function extractUsingIndexRange($records, $from, $to) {
    	$result = array();
    	$to = $to === 0 ? count($records) : $to;
    	
    	for ($i = $from - 1; $i < $to; $i++) {
    		if (!empty($records[$i])) {
    			$result[] = $records[$i];
    		} else {
    			break;
    		}
    	}
    	return $result;
    }
    
    public static function checkRecordsetSize(&$recordset) {
    	$json_recordset = json_encode($recordset);
    	while (strlen($json_recordset) > 8000) {
    		array_pop($recordset); // reduce the recordset size
    		$json_recordset = json_encode($recordset);
    	}
    }
    
    public static function compareLatestUpdatedDate($latest_date, $date) {
    	return $date > $latest_date ? $date : $latest_date;
    }
}
?>