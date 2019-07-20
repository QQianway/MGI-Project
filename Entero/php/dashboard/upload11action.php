<script type="text/javascript" src="assets/js/upload.js"></script>
<?php

require_once ("../config.php");
session_start();
if (!isset($_SESSION['USER'])||($_SESSION["LEVEL"] != 1))
{
	header('Location: ../../home.php');
}
$target_dir = "../../data/plotter/";
$genomeID=$_SESSION['uploadGenomeID'];
$query=mysqli_query($conn,"select * from scaffold where geneID=$genomeID");
while ($rows=mysqli_fetch_array($query))
{
	$target_file = $target_dir . basename($_FILES[$rows['scaffoldID']]["name"]);
	$uploadOk = 1;
	$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$target_file = $target_dir .$rows['scaffoldID'].".".$FileType;

	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
	&& $FileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}

	if ($uploadOk == 0) {
		  echo "<script>uploadFailed()</script>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file ($_FILES[$rows['scaffoldID']]["tmp_name"], $target_file))
		{
			unset($_SESSION['uploadGenomeID']);
			unset($_SESSION['UPLOAD']);	
		}
		else{
			 echo "<script>uploadFailed()</script>";
		}

	}
}
?><script>uploadSuccess11()</script>
