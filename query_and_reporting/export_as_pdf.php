<?php
	require_once '../init.php';
	require_once '../res/mpdf/mpdf.php';
	
	if(!$User->isUserLogin()){
		header('Location:' . DOMAIN_NAME . '/index.php');
		exit();
	}
	else{
		if(!$Session->check('user_company_selection_key')){
			$Session->write('select_company', '<strong>Please Select a Company</strong>');	
			header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
			exit();
		}
		else if($Session->read('user_company_selection_key') <=0 ){
			$Session->write('select_company', '<h2>Please Select a Company</h2>');
			header('Location:' . DOMAIN_NAME . '/sysadmin/user-company-selection.php');
			exit();
		}	
	}

	$userCredentials = $User->getUserCredentials();
	$userPK = $userCredentials['UserPk'];
	
	if($Session->check('title') && $Session->check('content')){
		$mpdf = new mPDF();		
		$mpdf->allow_charset_conversion = true;
		$mpdf->charset_in = 'UTF-8';
		
		$mpdf->SetImportUse();	
		$img = $mpdf->SetSourceFile(DOCROOT . '/res/Logo.pdf');	
		$id = $mpdf->ImportPage($img);	
		$mpdf->UseTemplate($id);	
		
		//Set title of PDF
		$pdfTitle = $Session->read('title');
		$mpdf->SetTitle($pdfTitle);
		
		//send the captured html from the output buffer 
		$stylesheet = file_get_contents(DOCROOT . '/css/style.css');
		$mpdf->WriteHTML($stylesheet, 1);
		
		$mpdf->WriteHTML('<br /><br />' . $Session->read('content'));
		$mpdf->Output($pdfTitle . '.pdf', 'D');

		$Session->sessionUnset('sessionUnset');
		$Session->sessionUnset('content');
		exit;
	}
?>