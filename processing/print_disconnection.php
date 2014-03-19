<?php header('Content-type: text/html; charset=utf-8'); 
	require_once '../init.php';
	require_once '../res/mpdf/mpdf.php';
?>
<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
	<title>Viis Novis Prima</title>
	<script src="<?php echo DOMAIN_NAME; ?>/js/jquery/jquery-1.10.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/style.css" media="all, print" />
	<?php if (!empty($import_fancy_box)) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/jquery.fancybox.css?v=2.1.5" media="screen" />
	<?php } ?>
	<style type="text/css" >
		.a4_bond {
		height: 9in;
		width: 7.067in;
		padding: .85in .6in .85in .6in;
		background-color: white;
		border: solid 1px;   
		}
		.pdf-logo {
		height:0.76in;
		width:3.72in;
		}
		.pdf-bulb {
		height:1.67in;
		width:2.02in;
		}
	</style>
	</head>
	<body style="padding:0;background-color: #6C6C50;">
		<?php 
			foreach ($_SESSION['cut_instruction_pdf'] as $cut_instruction) {
		?>

		<div class="a4_bond" style="">
			<img class="pdf-logo" src="<?php echo DOMAIN_NAME; ?>/images/triple-m-logo.JPG">
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">Reg. No. 2004/085107/23</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">VAT No. 4730214733</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0">
				<span" style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;color:navy">
					416 Theuns van Niekerk Street, Wierda Park, Centurion
				</span>
			</p>
			<p class="MsoNormal" style="text-align:justify; margin:0">
				<b>
					<span" style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;color:navy">
						Telephone Numbers:&nbsp; 
					</span>
				</b>
				<span" style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;color:navy">
					012-653 0600
				</span>
			</p>
			<p class="MsoNormal" style="text-align:justify; margin:0">
				<b>
					<span" style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;color:navy">
						Fax Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; 
					</span>
				</b>
				<span" style="font-size:10.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;color:navy">
					012-653 0650
				</span>
			</p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy"><?php echo $cut_instruction['CuttingDay']; ?></span></p>
			<p class="MsoNormal" style="text-align:center; ">
				<b>
					<span" style="font-size:16.0pt;font-family:&quot;Arial Narrow&quot;,&quot;sans-serif&quot;;color:black;">
						Electricity Disconnection Notice
					</span>
				</b>
			</p>
			<p class="MsoNormal" style="text-align:center; ">
				<img class="pdf-bulb" src="<?php echo DOMAIN_NAME; ?>/images/bulb.png">
			</p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><?php echo $cut_instruction['ClientName']; ?></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><?php echo $cut_instruction['BuildingName']; ?></p>
			<p class="MsoNormal" style="text-align:justify; margin:0">Unit <?php echo $cut_instruction['UnitNumberBk']; ?></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:left; margin:0">Good day, this email is a friendly reminder that your account with Triple M Metering is in arrears by <u>R<?php echo Prima::formatDecimal($cut_instruction['OutstandingAmount']); ?></u>. and your power supply has been suspended.</p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:left; margin:0">To be reconnected, urgently make payment and send proof of payment to <a href="#">reception@triple-m.co.za</a> or phone our offices at 012 653 0600.  </p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0">Please note the following:</p>
			<ul>
				<li style="text-align:left; ">No Electricity will be reconnected after hours.</li>
				<br/>
				<li style="text-align:left; ">A re-connection fee of R350.00 will be charged to your account and will reflect in your next bill from Triple M.</li>
				<br/>
				<li style="text-align:left; ">Legal steps will be taken against any person that attempts to re-connect their power supply illegally</li>
			</ul>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0">Kind Regards</p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span lang="EN-US" style="font-size:14.0pt;color:navy">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0">Triple M Metering Team</p>
		</div>
		<?php //break;
			}
		?>
		<script type="text/javascript">
			$('img').load(function() {
				window.print();
				window.history.back();
			});
		</script>
	</body>
</html>
