<?php 
	require_once '../init.php';
	
	//Check if Users Table has users
	//If Users table has no users then create an administrator account
    if($User->checkUserTable() <= 0){
		$password_plaintext = 'admin';
		$password_hashed = Bcrypt::hash($password_plaintext, 32);
		
		$admin_email = 'admin@viisnovis.co.za';
		$user_params = array(
			0,//UserPk
			0,//SystemUserPk
			'Admin',//DisplayName
			$password_hashed,//Password			
			$admin_email,//UserEmail
			1,//IsActive
			0,//IsAbsent
			0,//IsSystemGeneratedPassword
			-1 //TeamFk
		);
		$lastInsertedId = $User->createUser($user_params);
		if($lastInsertedId > 0){ 
			header('Location:' . DOMAIN_NAME . Sanitize::sanitizeStr($_SERVER['PHP_SELF']));
			exit(); 
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Viis Novis Prima</title>
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/style.css" />
	<script src="<?php echo DOMAIN_NAME; ?>/js/jquery-1.10.2.min.js"></script>
</head>
<body class="user-auth">
	<div id="wrap">
       <div class="header">    
           <div class="logo"><a href="<?php echo DOMAIN_NAME; ?>/index.php"><img src="<?php echo DOMAIN_NAME; ?>/images/logo.png" alt="" title="" border="0" /></a></div>		   
           <div class="clear"></div>
       </div> 
       <div class="center_content">
			<?php
				$email = NULL;
				if($Session->read('auth_error')){
					echo '<div class="warning-box warning">'. $Session->read('auth_error') .'</div>';
					$Session->sessionUnset('auth_error');
				}
				
				if($Session->read('email')){ $email = $Session->read('email'); }
			?>
			<div class="auth_container">
            <div class="login">				
				<form class="form-auth" action="<?php echo DOMAIN_NAME . '/auth/auth_transition.php'; ?>" method="post">
					<h2 class="form-auth-heading" align="center">Please sign in</h2>
					<input class="form-control" type="text" autofocus="autofocus" tabindex="1" name="user_email" required placeholder="Email address" value="<?php echo $email; ?>"  />
					<input class="form-control" type="password" tabindex="2" name="user_password" required placeholder="Password" />
					<button class="btn auth-submit" type="submit">Sign in</button> 
				</form>
                </div>
			</div>
<?php
$Session->sessionUnset('email');
require DOCROOT . '/template/footer.php';
?>
<script src="<?php echo DOMAIN_NAME; ?>/js/modernizr.custom.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/input.placeholder.sniffer.js"></script>