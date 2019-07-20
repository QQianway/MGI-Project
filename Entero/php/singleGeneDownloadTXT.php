<?php
ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script
require_once("config.php");

$seqName = $_GET['gene'];

$query2 = mysqli_query($conn,"select * from mapping where seqName='$seqName'");
$rows2 = mysqli_fetch_array($query2);
$break = explode('_',$seqName);
$scaffold = $break[0];
$_SESSION['scaffoldID']=$scaffold;
$id = '\_'.$break[2];
$BLASTquery = mysqli_query($conn,"select * from blastresult where seqName='$seqName'");
$BLASTqueryRows = mysqli_fetch_array($BLASTquery);
$query3 = mysqli_query($conn,"select * from genes where scaffoldID like '$scaffold%' and description like '%$id%'");
$rows3 = mysqli_fetch_array($query3); 
$query1 = mysqli_query($conn,"select * from geneseq where geneSeqName='$seqName'and type='1'");
$rows1 = mysqli_fetch_array($query1);
$query6 = mysqli_query($conn,"select * from geneseq where geneSeqName like '$seqName%'and type='0'");
$rows6 = mysqli_fetch_array($query6);

$path = "../data/temp/".$seqName.".txt";
$myfile = fopen($path, "w") or die("Unable to open file!");

fwrite($myfile,"Sequence Name\t{$BLASTqueryRows['seqName']}\n");
fwrite($myfile,'Description'."\t"."{$BLASTqueryRows['description']}"."\n");
fwrite($myfile,'Start'."\t"."{$rows3['start']}"."\n");
fwrite($myfile,'End'."\t"."{$rows3['end']}"."\n");
fwrite($myfile,'Strand'."\t"."{$rows3['strand']}"."\n");
fwrite($myfile,'Score'."\t"."{$rows3['score']}"."\n");
fwrite($myfile,'E-value'."\t"."{$BLASTqueryRows['e-value']}"."\n");
if ($BLASTqueryRows['enzymeCode']!=NULL){ fwrite($myfile,'Enzyme Code'."\t"."{$BLASTqueryRows['enzymeCode']}"."\n"); }
if ($BLASTqueryRows['goID']!=NULL){ fwrite($myfile,'GO-ID'."\t"."{$BLASTqueryRows['goID']}"."\n"); }
if ($BLASTqueryRows['goName']!=NULL){ fwrite($myfile,'GO-name'."\t"."{$BLASTqueryRows['goName']}"."\n"); }
fwrite($myfile,'Hit'."\t"."{$BLASTqueryRows['hits']}"."\n");
fwrite($myfile,'interPro GO-ID'."\t"."{$BLASTqueryRows['interproGOID']}"."\n");
fwrite($myfile,'interPro GO-Name'."\t"."{$BLASTqueryRows['interproGOName']}"."\n");
fwrite($myfile,'interPro ID'."\t"."{$BLASTqueryRows['interproID']}"."\n");
fwrite($myfile,'Length'."\t"."{$BLASTqueryRows['length']}"."\n");
fwrite($myfile,'Number of GO'."\t"."{$BLASTqueryRows['numofGo']}"."\n");
fwrite($myfile,'Sim Mean'."\t"."{$BLASTqueryRows['simMean']}"."\n");
fwrite($myfile,'Status'."\t"."{$BLASTqueryRows['status']}"."\n");
fwrite($myfile,'Tags'."\t"."{$BLASTqueryRows['tags']}"."\n");
fwrite($myfile,'Amino Acid Sequences'."\t"."{$rows1['geneSeq']}"."\n");
fwrite($myfile,'Nucleotide Sequences'."\t"."{$rows6['geneSeq']}"."\n");

fclose($myfile);
 
 
if ($fd = fopen ($path, "r")) {
    $fsize = filesize($path);
    $path_parts = pathinfo($path);
    $ext = strtolower($path_parts["extension"]);
    switch ($ext) {
        case "pdf":
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
        break;
        // add more headers for other content types here
        default;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
        break;
    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) {
        $buffer = fread($fd, $fsize);
        echo $buffer;
    }
}
fclose ($fd);
if (file_exists($path)){
	unlink($path);
}
exit;
