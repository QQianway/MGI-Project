<script type="text/javascript" src="assets/js/upload.js"></script>
<?php
require_once ("../config.php");
session_start();
if (!isset($_SESSION['USER'])||($_SESSION["LEVEL"] != 1))
{
	header('Location: ../../home.php');
}
$target_dir = "../../data/mapping/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
if($FileType != "csv") {
    echo "Sorry, only csv files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk!=0)
{
	$myfile = fopen($_FILES["fileToUpload"]["tmp_name"], "r") or die("Unable to open file!");
	fgets($myfile);
	while(!feof($myfile)) 
	{
		 $columns = (fgetcsv($myfile));
		 if ($columns[0]!='')
		 {
			  $sql = "INSERT INTO `mapping`(`seqName`, `length`, `bit-score`, `eValue`, `hitName`, `GOs`, `acc`, `mappingID`) VALUES ('$columns[0]','$columns[1]','$columns[2]','$columns[3]',\"$columns[4]\",'$columns[5]','$columns[6]','')";
			 $result=mysqli_query($conn,$sql) or trigger_error($conn->error."[$sql]");
			 if ($result == FALSE)
			  {
				  $uploadOk=0;
			  } 
		 }
	}
}
if ($uploadOk == 0) {
   echo "<script>uploadFailed()</script>";
// if everything is ok, try to upload file
} else {
	$id = $_SESSION['uploadGenomeID'];
	//echo ("Genome ID is ".$id);
	$query = mysqli_query($conn,"select * from genome where genomeID = '$id'");
	$row = mysqli_fetch_array($query);
	$target_file = $target_dir . basename($row['genomeName'].'.mapping.'.$FileType);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
	{
	$_SESSION['UPLOAD']=5;
        echo "<script>uploadSuccess5()</script>";
    } 
	else 
	{
       echo "<script>uploadFailed()</script>";
    }
}
fclose($myfile);
?>
