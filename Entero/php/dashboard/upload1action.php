<script type="text/javascript" src="assets/js/upload.js"></script>
<?php
require_once ("../config.php");
session_start();
if (!isset($_SESSION['USER'])||($_SESSION["LEVEL"] != 1))
{
	header('Location: ../../home.php');
}
$genomename = str_replace(' ', '_', $_POST['genomename']);
$target_dir = "../../data/fasta/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
if($FileType != "fasta" and $FileType != "fa") {
    echo "Sorry, only fasta files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk!=0)
{
	$myfile = fopen($_FILES["fileToUpload"]["tmp_name"], "r") or die("Unable to open file!");
	$full = fread($myfile,filesize($_FILES["fileToUpload"]["tmp_name"]));
	$scaffolds = explode (">", $full);
	$noScaffold = sizeof($scaffolds)-1;
	$genomesize = filesize($_FILES["fileToUpload"]["tmp_name"]);
	$userid = $_SESSION['ID'];
	$genomelength = 0;
	foreach ($scaffolds as $scaffold) 
	{
		if ($scaffold != "")
		{
			$lines = explode("\n", $scaffold);
			foreach ($lines as $i => $line)
			{
				if ($i>0)
				{
					$genomelength=$genomelength+strlen($line);
				}
			}
		}
	}
	$sql = "INSERT INTO genome(size, length, noScaffold, genomeName, userID) VALUES ('$genomesize ','$genomelength','$noScaffold','$genomename','$userid')";
	$result=mysqli_query($conn,$sql) or trigger_error($conn->error."[$sql]");
	if ($result != FALSE)
	{	
		$query = mysqli_query($conn,"select * from genome where genomeName = '$genomename'");
		$row = mysqli_fetch_array($query);
		$genomeID = $row['genomeID'];
		$_SESSION['uploadGenomeID']=$genomeID;
		foreach ($scaffolds as $scaffold) 
		{
			if ($scaffold != "")
			{
				$lines = explode("\n", $scaffold);
				$pieces = explode("|", $lines[0]);
				$scaffoldID = $pieces[0];
				$sql1 = "INSERT INTO scaffold(scaffoldID, geneID) VALUES ('$scaffoldID','$genomeID')";
				$result1=mysqli_query($conn,$sql1) or trigger_error($conn->error."[$sql1]");
				$scaffoldfile = fopen("../../data/scaffold/".$scaffoldID.".fasta", "w") or die("Unable to open file!");
				fwrite($scaffoldfile, $scaffold);
				fclose($scaffoldfile);		
			}
		}
		if ($result == FALSE)
		{
			$uploadOk = 0;
		}
	}
	else
	{
		$uploadOk = 0;
	}
}
if ($uploadOk == 0) {
     echo "<script>uploadFailed()</script>";
// if everything is ok, try to upload file
} else {
	$target_file = $target_dir . basename($genomename.'.'.$FileType);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
	{
        	$makeDB="./makeblastdb ";
		$input="-in ../../data/fasta/".$genomename.".fasta ";
		$output="-out ../db/".$genomename."FASTA ";
		$dbtype="-dbtype nucl ";
		$command=$makeDB.$input.$output.$dbtype;
		$COMMAND=escapeshellcmd($command);
		chdir("../../blast/bin/");
		exec($COMMAND);
		$getAllGenomeName=mysqli_query($conn,"select* from genome");
		$allDB="";
		while($getAllGenomeNameRows=mysqli_fetch_array($getAllGenomeName)){
			$allgenomeName=$getAllGenomeNameRows['genomeName'];
			$allDB=$allDB."../db/".$allgenomeName."FASTA ";
		}
		$mergeDB="./blastdb_aliastool ";
		$listDB="-dblist \"".$allDB."\" ";
		$outMergeDB="-out ../db/FASTA ";
		$title="-title \"Whole Genome Database\"";
		system('pwd');
		$mergeCommand=$mergeDB.$listDB.$outMergeDB.$dbtype.$title;
		$MERGECOMMAND=escapeshellcmd($mergeCommand);
		exec($MERGECOMMAND." 2>&1");

		$jbrowseCommand="../../jbrowse/bin/prepare-refseqs.pl --fasta ".$target_file." --out ../../jbrowse/data/".$genomename."/";
		$jbrowseCOMMAND=escapeshellcmd($jbrowseCommand);
		exec($jbrowseCOMMAND);
		$_SESSION['UPLOAD']=1;
		?>
			<script>uploadSuccess1()</script>
		<?php
    } 
	else 
	{
        echo "<script>uploadFailed()</script>";
    }
	fclose($myfile);
}
?>
