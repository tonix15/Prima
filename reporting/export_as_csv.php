<?php
	require_once '../init.php';
	require_once '../res/simplehtmldom/simple_html_dom.php';
	
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
		$html = str_get_html($Session->read('content'));
		
		$filename = $Session->read('title') . ".csv";
		
		header('Content-type: application/ms-excel');
		header("Content-Disposition: attachment; filename=$filename");
	
		$fp = fopen('php://output', 'w');
		
		$csvString = "";
		foreach($html->find('tr') as $element){
			$td = array();
			foreach($element->find('th') as $row){
				$row->plaintext = "\"$row->plaintext\"";
				$td[] = $row->plaintext;
			}
			$td = array_filter($td);
			$csvString .= implode(',', $td);
			
			$td = array();
			foreach($element->find('td') as $row){
				$row->plaintext = "\"$row->plaintext\"";
				$td[] = $row->plaintext;
			}
			$td = array_filter($td);
			$csvString .= implode(',', $td) . "\n";
		}
		echo $csvString;
		fclose($fp);
		exit;
	}
?>