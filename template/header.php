<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Viis Novis Prima</title>
<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/style.css" media="all, print" />
<?php if (!empty($import_fancy_box)) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/jquery.fancybox.css?v=2.1.5" media="screen" />
<?php } ?>

</head>
<body>
<div id="wrap">
       <div class="header">			
           <div class="logo"><a href="<?php echo DOMAIN_NAME; ?>/index.php"><img src="<?php echo DOMAIN_NAME; ?>/images/logo.png" alt="" title="" border="0" /></a></div>           
		   <div id="menu">
                <?php require_once DOCROOT . '/widgets/menu.php'; ?>
           </div>
           <div class="clear"></div>
       </div> 
       <div class="center_content">