<?php
	class Validate{
		public static function validateEmail($email){
			if(preg_match( '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/', $email)){
				return TRUE;
			}
			return FALSE;
		}
		
		public static function validateWholeNumber($number){
			if(filter_var($number, FILTER_VALIDATE_INT)){
				return TRUE;
			}
			return FALSE;
		}
		
		public static function validateDecimalNumber($number){
			if(filter_var($number, FILTER_VALIDATE_FLOAT)){
				return TRUE;
			}
			return FALSE;
		}
	}