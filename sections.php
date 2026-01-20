<?php
//Let you see classes
$baseDir = 'C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/';
//List of folders in classes
$course = $_GET['course'] ?? "";

//List path. See subfolders in class folder
$coursePath = $baseDir . $course . "\\";

//Empty array to be appeneded
$sections = [];

//In course choses
if (is_dir($coursePath)) {
	//For each Class foler
    foreach (glob($coursePath . "*") as $folder) {
		//Add other folders in class folder to sections
        if (is_dir($folder)) {
            $sections[] = basename($folder);
        }
    }
}

header('Content-Type: application/json');
echo json_encode($sections);
?>
