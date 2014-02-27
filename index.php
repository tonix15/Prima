<?php

require_once 'init.php';

//User
if(!$User->isUserLogin()){
	header('Location:' . DOMAIN_NAME . '/auth/authenticate.php');
	exit();
}
$userCredentials = $User->getUserCredentials();
$userPK = $userCredentials['UserPk'];

require DOCROOT . '/template/header.php';
echo '<h2>Welcome to Viis Novis Prima</h2>';
require DOCROOT . '/template/footer.php';

?>

