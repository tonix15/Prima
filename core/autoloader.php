<?php
/* autoload pre-defined function */
function loadClasses($class_name) {
	require_once DOCROOT . '/core/class.' . strtolower($class_name) . '.php';
}

/* autoload anonymous function 
spl_autoload_register(function ($class_name) {
	require_once DOCROOT . '/core/class.' . strtolower($class_name) . '.php'; 
}); */
?>