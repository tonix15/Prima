<?php
	require_once '../init.php'; 
	//Destroy Current Session
	$Session->end();
	header('Location:' . DOMAIN_NAME . '/index.php');
	exit(); 
?>