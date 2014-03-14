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
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/style.css" media="all, print" />
	<?php if (!empty($import_fancy_box)) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_NAME; ?>/css/jquery.fancybox.css?v=2.1.5" media="screen" />
	<?php } ?>

	</head>
	<body style="padding:0;background-color: #6C6C50;">
		<?php 

		//ini_set( 'memory_limit' , '-1' );
		//ini_set('pcre.backtrack_limit','200000');
				//Buffer the html table with PHP to be stored in variable
				ob_start(); 
			foreach ($_SESSION['cut_instruction_pdf'] as $cut_instruction) {
		?>
		<div class="short_bond" >
			<img class="pdf-logo" src="<?php echo DOMAIN_NAME; ?>/images/triple-m-logo.JPG">
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">Reg. No. 2004/085107/23</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">VAT No. 4730214733</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">Reg. No. 2004/085107/23</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">Reg. No. 2004/085107/23</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">VAT No. 4730214733</span></p>
			<p class="MsoNormal" style="text-align:justify; margin:0"><span style="color:navy">Reg. No. 2004/085107/23</span></p>
		</div>
		<?php break;
			}
			 
			$cut_instruction_contents = ob_get_contents();
			ob_end_flush();
			
			//$mpdf = new mPDF();		
			$mpdf = new mPDF('',    // mode - default ''
										 'Letter',    // format - A4, for example, default ''
										 0,     // font size - default 0
										 '',    // default font family
										 0,    // margin_left
										 0,    // margin right
										 0,     // margin top
										 0,    // margin bottom
										 0,     // margin header
										 0,     // margin footer
										 'P');  // L - landscape, P - portrait
			$mpdf->debug = true;
			$mpdf->allow_charset_conversion = true;
			$mpdf->charset_in = 'UTF-8';
			
			//$mpdf->SetImportUse();	
			// $img = $mpdf->SetSourceFile(DOCROOT . '/res/Logo.pdf');	
			// $id = $mpdf->ImportPage($img);	
			// $mpdf->UseTemplate($id);	
			
			//Set title of PDF
			$pdfTitle = 'Cut Notice ' . date('m-d-Y');
			$mpdf->SetTitle($pdfTitle);
			
			//send the captured html from the output buffer 
			$stylesheet = file_get_contents(DOCROOT . '/css/style.css');
			$mpdf->WriteHTML($stylesheet, 1);
			
			$mpdf->WriteHTML($cut_instruction_contents);
			$mpdf->Output($pdfTitle . '.pdf', 'D');

			exit; //

		?>
	</body>
</html>