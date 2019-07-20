<script type="text/javascript" src="assets/js/upload.js"></script>
<?php
require_once ("../config.php");
session_start();
if (!isset($_SESSION['USER'])||($_SESSION["LEVEL"] != 1))
{
	header('Location: ../../home.php');
}
$target_dir = "../../data/genedetail/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
if($FileType != "gff") {
    echo "Sorry, only gff files are allowed.";
    $uploadOk = 0;
}

$string = file_get_contents($_FILES["fileToUpload"]["tmp_name"]);
$string = str_replace(' ', '', $string);
file_put_contents($_FILES["fileToUpload"]["tmp_name"], $string);

if ($uploadOk!=0)
{
	$myfile = fopen($_FILES["fileToUpload"]["tmp_name"], "r") or die("Unable to open file!");
	while(!feof($myfile)) 
	{
		$entry=fgets($myfile);
		if ($entry[0]!='#')
		{
			$columns = explode("\t", $entry);
			 if ($columns[0]!='')
			 {
				  $sql = "INSERT INTO `genes`(`geneID`, `scaffoldID`, `source`, `type`, `start`, `end`, `score`, `strand`, `unknown`, `description`) VALUES ('','$columns[0]','$columns[1]','$columns[2]','$columns[3]','$columns[4]','$columns[5]','$columns[6]','$columns[7]','$columns[8]')";
				 $result=mysqli_query($conn,$sql) or trigger_error($conn->error."[$sql]");
				 if ($result == FALSE)
				  {
					  $uploadOk=0;
				  } 
			 }
		}
	}
}
if ($uploadOk == 0) {
   echo "<script>uploadFailed()</script>";
// if everything is ok, try to upload file
} else {
	$id = $_SESSION['uploadGenomeID'];
	$query = mysqli_query($conn,"select * from genome where genomeID = '$id'");
	$row = mysqli_fetch_array($query);
	$target_file = $target_dir . basename($row['genomeName'].'.predictedgene.'.$FileType);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
	{
	$jbrowseCommand="../../jbrowse/bin/flatfile-to-json.pl --gff ".$target_file." --trackLabel gene --out ../../jbrowse/data/".$row['genomeName']."/";
	$jbrowseCOMMAND=escapeshellcmd($jbrowseCommand);
	exec($jbrowseCOMMAND);
	$_SESSION['UPLOAD']=2;
        echo "<script>uploadSuccess2()</script>";
    } 
	else 
	{
        echo "<script>uploadFailed()</script>";
    }
}
fclose($myfile);
?>
