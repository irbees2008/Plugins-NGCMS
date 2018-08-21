<?php
// JQuery File Upload Plugin v1.4.1 by RonnieSan - (C)2009 Ronnie Garcia
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	
	// Uncomment the following line if you want to make the directory if it doesn't exist
	// mkdir(str_replace('//','/',$targetPath), 0755, true);
	
	move_uploaded_file($tempFile,$targetFile);
}

  			switch ($_FILES['Filedata']['error'])
				{  	case 0:
						$msg = "No Error";
						break;
					case 1:
           				$msg = "The file is bigger than this PHP installation allows";
           				break;
   					case 2:
           				$msg = "The file is bigger than this form allows";
           				break;
    				case 3:
           				$msg = "Only part of the file was uploaded";
           				break;
    				case 4:
          		 		$msg = "No file was uploaded";
           				break;
					case 6:
          		 		$msg = "Missing a temporary folder";
           				break;
    				case 7:
          		 		$msg = "Failed to write file to disk";
           				break;
    				case 8:
          		 		$msg = "File upload stopped by extension";
           				break;
 					default:
						$msg = "unknown error ".$_FILES['Filedata']['error'];
						break;
				}

	$setupFile = "uploadVARresults.txt";
	$fh = fopen($setupFile, 'w');
	if ($fh) {
		$stringData = "path: ".$_GET['folder']."\n targetFile: ".$targetFile."\n Error: ".$_FILES['Filedata']['error']."\nError Info: ".$msg;
	}
	fwrite($fh, $stringData);
	fclose($fh);
	
	echo '1';

?>