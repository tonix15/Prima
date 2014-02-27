		<div class="clear"></div>
       	</div><!--end of center content-->
     
        <div class="footer">
            <p>Copyright (c) 2013, Viis Novis</p>
       	</div>
</div> <!--end of main wrapper content-->
<!--scripts-->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
<script src="<?php echo DOMAIN_NAME; ?>/js/jquery/jquery-1.10.2.min.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/init.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/functions.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/ui-function.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/sys-ad_functions.js"></script>
<script src="<?php echo DOMAIN_NAME; ?>/js/sys-ad_ui-functions.js"></script>
<?php if (!empty($import_fancy_box)) { ?>
<script type="text/javascript" src="<?php echo DOMAIN_NAME; ?>/js/jquery.fancybox.js?v=2.1.5"></script>
<?php } ?>
</body> <!-- end of body -->
</html> <!-- end of document -->
<?php 
$dbh = null; // close the connection
?>