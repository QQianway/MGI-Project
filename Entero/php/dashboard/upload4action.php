<script type="text/javascript" src="assets/js/upload.js"></script>
<?php
require_once ("../config.php");
session_start();
if (!isset($_SESSION['USER'])||($_SESSION["LEVEL"] != 1))
{
	header('Location: ../../home.php');
}
$target_dir = "../../data/genesequenceN/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
if($FileType != "fasta" and $FileType != "fa" and $FileType != "ffn") {
    echo "Sorry, only fasta files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk !=0)
{
	$myfile = fopen($_FILES["fileToUpload"]["tmp_name"], "r") or die("Unable to open file!");
	$full = fread($myfile,filesize($_FILES["fileToUpload"]["tmp_name"]));
	$pieces = explode (">", $full);
	foreach ($pieces as $piece) {
		if ($piece != "")
		{
			$lines = explode("\n", $piece);
			$seq = "";
			foreach ($lines as $i => $line)
			{
				if ($i>0)
				{
					$seq = $seq.$line;
				}
			}
			$sql = "insert into geneseq(geneSeqName,geneSeq,type) values('$lines[0]','$seq','0')";
			$result=mysqli_query($conn,$sql) or trigger_error($conn->error."[$sql]");
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
		$genomeName=$row['genomeName'];
		$makeDB="./makeblastdb ";
		$input="-in ../../data/genesequenceN/".$genomeName.".predictedgene.ffn ";
		$output="-out ../db/".$genomeName."FFN ";
		$dbtype="-dbtype nucl ";
		$command=$makeDB.$input.$output.$dbtype;
		chdir("../../blast/bin/");
		$COMMAND=escapeshellcmd($command);
		exec($COMMAND);
		$getAllGenomeName=mysqli_query($conn,"select* from genome");
		$allDB="";
		while($getAllGenomeNameRows=mysqli_fetch_array($getAllGenomeName)){
			$allgenomeName=$getAllGenomeNameRows['genomeName'];
			$allDB=$allDB."../db/".$allgenomeName."FFN ";
		}
		$mergeDB="./blastdb_aliastool ";
		$listDB="-dblist \"".$allDB."\" ";
		$outMergeDB="-out ../db/FFN ";
		$title="-title \"Nuclueotide Seqeunces Database\"";
		$mergeCommand=$mergeDB.$listDB.$outMergeDB.$dbtype.$title;
		$MERGECOMMAND=escapeshellcmd($mergeCommand);
		exec ($MERGECOMMAND);
		$_SESSION['UPLOAD']=4;
        echo "<script>uploadSuccess4()</script>";
    } 
	else 
	{
       echo "<script>uploadFailed()</script>";
    }
}
fclose($myfile);
?>
