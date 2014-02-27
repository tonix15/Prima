<?php
ini_set('memory_limit', '1024M');

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('DOCROOT', $_SERVER['DOCUMENT_ROOT']); // actual file path
define('DOMAIN_NAME', 'http://' . $_SERVER['HTTP_HOST']); // root of the server url
$dbh = null;

require_once DOCROOT . '/config/dbconnect.php';
require_once DOCROOT . '/core/autoloader.php';

spl_autoload_register('loadClasses');

$User = new User($dbh);

$dbhandler = new DBHandler(new PrimaDB(
	$db_options['driver'],
	$db_options['server'], 
	$db_options['database'], 
	$db_options['db_user'], 
	$db_options['db_password']
));

$Session = new Session();
$Session->start();
?>