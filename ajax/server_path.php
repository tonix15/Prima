<?php
	$script = basename(__FILE__); // the name of this script
	$path = !empty($_REQUEST['path']) ? $_REQUEST['path'] : dirname(__FILE__); // the path the script should access
  
	$directories = array();
	$files = array();
  
	// Check we are focused on a dir
	if (is_dir($path)) {
		chdir($path); // Focus on the dir
		if ($handle = opendir('.')) {
			while (($item = readdir($handle)) !== false) {
				// Loop through current directory and divide files and directorys
				if(is_dir($item)){ array_push($directories, realpath($item)); }
				else{ array_push($files, ($item)); }
			}
			closedir($handle); // Close the directory handle
		}
		else { echo "<p class=\"error\">Directory handle could not be obtained.</p>"; }
	}
	else{ echo "<p class=\"error\">Path is not a directory</p>"; }
  
	// There are now two arrays that contians the contents of the path. 
  
	// List the directories as browsable navigation
	echo "<h2>Navigation</h2>";
	echo "<ul>";
	foreach( $directories as $directory ){
		echo ($directory != $path) ? "<li><a href=\"{$script}?path={$directory}\">{$directory}</a></li>" : "";
	}
	echo "</ul>";  
?>