<?php
class User extends StoredProcedures {
	protected $Session;
	
    public function __construct(&$dbh) {
        parent::__construct($dbh);
		$this->Session = new Session();
    }
    
    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 int as @UserPk
     * @return recordset of rates
     */
    public function getUser($params, $isSingleRecord = false) {
    	if ($isSingleRecord === true) {
    		return $this->getSingleRecord($this->execute('[get].[User]', $params));	
    	}
    	
        return $this->execute('[get].[User]', $params);  
    }
    
    public function createUser($params) {
        $this->setUser($params);
        return $this->dbh->lastInsertId('User');
    }
    
    public function updateUser($params) {
        return $this->setUser($params);
    }
    
	public function getUserLogin($params, $isSingleRecord = false) {
		if ($isSingleRecord === true) {
			return $this->getSingleRecord($this->execute('[get].[UserLogin]', $params));
		}
        return $this->execute('[get].[UserLogin]', $params);  
    }
    
    public function isUserLogin() {
		if($this->Session->check('isLoggedIn')){
			return $this->Session->read('isLoggedIn');
		}
		return FALSE;
    }
    
    public function getUserCredentials() {
    	$user = array(
			'UserPk' => $this->Session->read('UserPk'),
			'DisplayName' => $this->Session->read('DisplayName')
		);
    	return $user;
    }
  

    /**
     * @param array $params contains the parameters of the stored procedure
     * @array val 1 bigint $param1 as @UserPk
     * @array val 2 bigint $param2 as @UserPk
     * @array val 3 varchar(100) as @User1
     * @array val 4 varchar(100) as @User2
     * @array val 5 varchar(100) as @User3
     * @array val 6 varchar(100) as @User4
     * @array val 8 varchar(10) as @PostalCode
     * @return last inserted id or boolean if update was successful
     */
    private function setUser($params) {
        return $this->executeNoneQuery('[set].[User]', $params);
    }
	
	public function login($data = array()){
		return $this->getUserLogin($data);
	}
	
	public function logout(){ 
		$this->Session->end();
	}
	
	public function checkUserTable() {
		return $user_count = count($this->getUser(array(0, 0, 0)));				
	}
	
	public function setCompanyId($company_id) {
		$this->Session->write('user_company_id', $company_id);
	}
	
	public function getCompanyId() {			
		return !empty($_SESSION['user_company_id']) ? (int)$_SESSION['user_company_id'] : -1;
	}
}
?>