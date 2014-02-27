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
	
	if($Session->check('termination_excel') || $Session->check('planning_excel')){
			
		//$html = str_get_html($content);
		
		$title = $_REQUEST['tab'] == 'planning' ? 'Reading Plan for '.$_REQUEST['period'] : 'Termination Plan';
		$filename = $title . ".csv";
		//$filename = $Session->read('title') . ".csv";
		
		header('Content-type: application/ms-excel');
		header("Content-Disposition: attachment; filename=$filename");
	
		$fp = fopen('php://output', 'w');
		
		$team_list = $Session->read('teamlist_export');
		if ( $_REQUEST['tab'] == 'planning' ) {
			$content = $Session->read('planning_excel');
			$csvString = "Building,Team,Reading Day,Sequence" ."\n";
		} else {
			$content = $Session->read('termination_excel');
			$csvString = "Building,Unit,Team,Reading Day,Sequence" ."\n";
		}
		
		foreach($content as $row){
			$td = array();
			if ( $_REQUEST['tab'] == 'planning' ) 
				$td = $row['BuildingName'] . "," . getTeamName($team_list, $row['TeamFk']) . "," . $row['ReadingDay'] . "," . $row['Sequence'] ;
			else 
				$td = $row['BuildingName'] . "," . $row['UnitNumberBk'] . "," . getTeamName($team_list, $row['TeamFk']) . "," . $row['ReadingDay'] . "," . $row['Sequence'] ;
			$csvString .=  $td . "\n";
		}
		echo $csvString;
		fclose($fp);
		exit;
	}
		
	function getTeamName($teamlist, $teamfk) {
		foreach($teamlist as $team) {
			if ($team['TeamPk'] ==$teamfk ) 
				return $team['Value'];
		}
	}
?>