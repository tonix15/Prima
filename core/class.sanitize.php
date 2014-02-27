<?php
class Sanitize{
	//Remove illegal or unwanted characters
	//Allowed - all letters and Digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=.
	public static function cleanEmail($email){
		if(!empty($email)){
			return filter_var($email, FILTER_SANITIZE_EMAIL);
		}
	}
	
	//Strips out all characters except for digits and +-
	public static function cleanWholeNumber($number = 0){
		if(!empty($number)){
			return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
		}		
	}
	/*
	 * Possible values for options
	 * 1 = allow fractions
	 * 2 = allow thousand
	 * 3 = allow scientific
	 */
	public static function cleanDecimalNumber($number = 0.0, $option = NULL){
		if(!is_null($option) && !empty($number)){
			switch($option){
				case 1:
					return filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					break;
				case 2:
					return filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
					break;
				case 3:
					return filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_SCIENTIFIC);
					break;
				default: 
					die('Invalid Option.');
					break;
			}
		}
		else{ return filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT); }
	}
	
	public static function sanitizeStr($str){
		if(!empty($str)){
			$str = trim($str);
			$str = strip_tags($str);
			$str = htmlspecialchars($str);
			return $str;
		}
	}
}