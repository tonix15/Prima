<?php
	class Utility{
		public static function vd_debug($params){
			echo '<pre>';
			var_dump($params);
			echo '</pre>';
		}
		
		public static function pr_debug($params){
			echo '<pre>';
			print_r($params);
			echo '</pre>';
		}
	}
?>