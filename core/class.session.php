<?php
	class Session{	
		/**
		 * Starts new or resumes existing session
		 * 
		 * @access  public
		 * @return  bool
		 */
		public function start(){
			session_name('Prima');
			session_set_cookie_params(43200);
			if(session_start()){ 
				//session_regenerate_id(TRUE);
				return TRUE; 
			}
			return FALSE;
		}
		
		/**
		 * End existing session, destroy, unset and delete session cookie
		 * 
		 * @access  public
		 * @return  void
		 */
		public function end(){
			if($this->status != true){ $this->start(); }
			session_unset();
			session_destroy();		
			setCookie('Prima', NULL, 0, '/');
		}
		
		/**
		 * Set new session item
		 * 
		 * @access  public
		 * @param   mixed
		 * @param   mixed
		 * @return  mixed
		 */
		public function write($key, $value){ 
			return $_SESSION[$key] = $value; 
		}
		
		/**
		 * Checks if session key is already set
		 * 
		 * @access  public
		 * @param   mixed  - session key
		 * @return  bool 
		 */
		public function check($key){
			if(isset($_SESSION[$key])){ return TRUE; }
			return FALSE;
		}
		
		/**
		 * Get session item
		 * 
		 * @access  public
		 * @param   mixed
		 * @return  mixed
		 */
		public function read($key){
			if(!isset($_SESSION[$key])){ return FALSE; }
			return $_SESSION[$key];
		}	
		
		public function sessionUnset($key){
			if(isset($_SESSION[$key])){ unset($_SESSION[$key]); }
		}
	}
?>