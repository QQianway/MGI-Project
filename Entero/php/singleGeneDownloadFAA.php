<?php
ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script
require_once("config.php");

$geneid = $_GET['gene'];
$query = mysqli_query($conn,"select * from geneseq where geneSeqName='$geneid'and type='1'");
$rows = mysqli_fetch_array($query);
$path = "../data/temp/".$geneid.".faa";

$myfile = fopen($path, "w") or die("Unable to open file!");
fwrite($myfile, '>'.$geneid."\n");
fwrite($myfile, $rows['geneSeq']);
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
