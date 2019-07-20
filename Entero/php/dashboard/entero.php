<!doctype html>
<?php 
	session_start();
	require_once ("../config.php"); 
	unset($_SESSION['genomeID']);
	unset($_SESSION['scaffoldID']);

	if (!isset($_SESSION['USER']))
	{
        	header('Location: ../../home.php');
	}
?>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<!--<link rel="icon" type="image/png" href="assets/img/favicon.ico">-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>DashBoard ENTERO</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    	<meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    	<link href="assets/css/animate.min.css" rel="stylesheet"/>

 	<!--  Light Bootstrap Table core CSS    -->
 	<link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
    	<!--     Fonts and icons     -->
    	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    	<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
	<script type="text/javascript" src="assets/js/java.js"></script>
	<link href="../../css/popup.css" rel="stylesheet" type="text/css" media="all">
</head>
<?php
	/* Delete old BLAST result before BLAST*/
	/* if received BLAST request, this function will delete old BLAST result and BLAST */
	if(isset($_POST['SEQUENCE']) or isset($_FILES["SEQFILE"]["tmp_name"])){
		/* Delete old file function */
		function deleteOldFiles($dirName,$days=0.05,$count = true) {
			if (is_dir($dirName)) {
				if($count) { 
					$countDeletedFiles =0; 
				}
				foreach (new DirectoryIterator($dirName) as $fileInfo) {
					if ($fileInfo->isDot() || $fileInfo->isDir()) {
					  continue;
					}
					if (time() - $fileInfo->getCTime() > ($days *86400)) {
						unlink($fileInfo->getRealPath());
						if($count) { 
							$countDeletedFiles++;
						 }
					}
				}
				return $count ? $countDeletedFiles : FALSE;
			}
			return FALSE;
		}
		/* generate random name for BLAST result output */
		function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		 
		echo(deleteOldFiles("../../blast/query/"));
		$randomName =generateRandomString();
		$program=$_POST['PROGRAM'];
		$dataLib=$_POST['DATALIB'];
		$expect=$_POST['EXPECT'];
		$view=$_POST['ALIGNMENT_VIEW'];
		if ($_POST['SEQUENCE']!=''){
			$sequence=$_POST['SEQUENCE'];
		}
		else{
			$myfile = fopen($_FILES["SEQFILE"]["tmp_name"], "r");
			$sequence = fread($myfile,filesize($_FILES["SEQFILE"]["tmp_name"]));
		}
		$queryFilePath="../query/".$randomName."temp.txt";
		$writefile=fopen("../../blast/query/".$randomName."temp.txt","w")or die ("Unable to open file");
		fwrite($writefile,$sequence);
		/* Execute BLAST command */
		$command="./".$program." -db ../db/".$dataLib." -query $queryFilePath"." -outfmt ".$view." -evalue ".$expect." -out ../query/".$randomName."output.txt";
		//$command=$program." -db /opt/lampp/htdocs/Entero/FrontEnd/blast/db/".$dataLib." -query /opt/lampp/htdocs/Entero/FrontEnd/blast/query/".$randomName."temp.txt"." -outfmt ".$view." -evalue ".$expect." -out /opt/lampp/htdocs/Entero/FrontEnd/blast/query/".$randomName."output.txt ";
		$COMMAND=escapeshellcmd($command);
		chdir('../../blast/bin/');
		exec($COMMAND);
		$readfile=fopen("../query/".$randomName."output.txt","r")or die ("Unable to open file");
		/* BLAST output if fail or success */
		if(filesize("../query/".$randomName."output.txt")==0){
			fclose($readfile);
			fclose($writefile);
			?><script>failBLAST();</script><?php
		}
		else{
			fclose($readfile);
			fclose($writefile);
			?>
			<script>
				alert("BLAST COMPLETED");
				var randomName=<?=json_encode($randomName)?>;
				window.open("../../blast/query/"+randomName+"output.txt");
			</script><?php
		}
	}
	/* BLAST complete */	
?>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
		<img src="../../images/logo-genom.png" style="max-width:100%; max-height:auto;" display="inline-block">                
		<a href="./entero.php" class="simple-text">
                    Entero database
                </a>
            </div>

            <ul class="nav">
                <li>
                    <a href="genome.php">
                        <i class="pe-7s-graph"></i>
                        <p>Genome</p>
                    </a>
                </li>
                <li>
                    <a href="scaffold.php">
                        <i class="pe-7s-user"></i>
                        <p>Scaffold</p>
                    </a>
                </li>
                <li>
                    <a href="genes.php">
                        <i class="pe-7s-note2"></i>
                        <p>Genes</p>
                    </a>
                </li>
				<li>
                    <a href="rRNA.php">
                        <i class="pe-7s-news-paper"></i>
                        <p>rRNA</p>
                    </a>
                </li>
                <li>
                    <a href="tRNA.php">
                        <i class="pe-7s-news-paper"></i>
                        <p>tRNA</p>
                    </a>
                </li>
				<?php
					if ($_SESSION["LEVEL"] == 1)
					{
				?>
					<li>
						<a href="upload1.php">
							<i class="pe-7s-cloud-upload"></i>
							<p>Upload</p>
						</a>
					</li>
				<?php
					}
				?>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
					<form class="navbar-brand" method='GET' action="genome.php">
						<select name="genomeID">
							<option value=NULL selected>Select Genome Here</option>
							<?php   
								$dropdownlist = mysqli_query($conn,"select * from genome");
								while ($row = mysqli_fetch_array($dropdownlist)){
							?>
							<option value="<?php echo $row['genomeID']; ?>"><?php echo $row['genomeName']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="../../home.php">
                               <p>HOME</p>
                            </a>
                        </li>
                        <li>
			<?php
			if ($_SESSION["USER"] != null){
			?>
				<a href="../logout.php">Logout</a>
			<?php 
			}
			else{
			?>
			<a nohref onclick="document.getElementsByClassName('loginmodal')[0].style.display='block'" style="cursor:pointer">Login</a>
			<?php	
			}	
			?>
                        </li>
			<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
		<!-- Simply display genomes, scaffolds and genes total number && direct links to separate overview page -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <a href="genome.php"><h4 class="title">Genome</h4></a>
                                <p class="category">Total</p>
                            </div>
                            <div class="content">
                                <h4 class="title"><?php
                                     $query = mysqli_query($conn,"select count(*)
                                     as genomeCount from genome");
                                     $rows = mysqli_fetch_array($query);
                                     echo $rows['genomeCount'];
                                     
                                ?></h4>
                                <div class="footer">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <a href="scaffold.php"><h4 class="title">Scaffold</h4></a>
                                <p class="category">Total</p>
                            </div>
                            <div class="content">
                                <h4 class="title"><?php

                                     $query1 = mysqli_query($conn,"select count(*)
                                     as scaffoldCount from scaffold");
                                     $rows1 = mysqli_fetch_array($query1);
                                     echo $rows1['scaffoldCount'];
                                     
                                ?></h4>
                                <div class="footer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card ">
                            <div class="header">
                                <a href="genes.php"><h4 class="title">Genes</h4></a>
                                <p class="category">Total</p>
                            </div>
                            <div class="content">
                                <h4 class="title"><?php

                                     $query2 = mysqli_query($conn,"select count(*)
                                     as geneCount from genes");
                                     $rows2 = mysqli_fetch_array($query2);
                                     echo $rows2['geneCount'];
                                     
                                ?></h4>
                            </div
                                <div class="footer">
                                </div>
                            </div>
                        </div>
			<h3>BLAST to</i> Entero Database</h3>		
			<!-- BLAST Start Here -->			
			<table border="1" width="100%">
			<tr><td>
			<table style="margin-left:5%;margin-right:5%" width="100%">
			<tr ><td >					
				<FORM ACTION="./entero.php" METHOD ="POST" NAME="MainBlastForm" ENCTYPE= "multipart/form-data"><br>
					<P>
					<b>Enter sequence below in <a href="../../blast/docs/fasta.html" target="_blank">FASTA</a> format</b>
					<BR>
					<textarea name="SEQUENCE" rows=6 cols=60></textarea>
					<BR>
					Or load it from disk <INPUT TYPE="file" NAME="SEQFILE"><br><br>
					<B>Choose parameters:</B>
					<table>
						<tr>
							<td width="80px"><a href="../../blast/docs/blast_program.html" target="_blank">Program</a></td>
							<td width="10px">:</td>
							<td><select name = "PROGRAM">
								<option> blastn 
								<option> blastp 
								<option> blastx 
								<option> tblastn 
								<option> tblastx 
							</select></td>
						</tr>
						<tr>
							<td><a href="../../blast/docs/blast_databases.html" target="_blank">Database</a></td>
							<td>:</td>
							<td><select name = "DATALIB">
							<?php 
								$getGenomeCount=mysqli_query($conn,"select count(*) from genome");
								$getGenomeCountRows=mysqli_fetch_array($getGenomeCount);
								$genomeCount=$getGenomeCountRows['count(*)'];
								if ($genomeCount>1){
							?>
								<option VALUE = "FASTA"> Whole Genome</option>
								<option VALUE = "FAA"> Protein</option>
								<option VALUE = "FFN"> Nucleotide</option>
							<?php	
								}
								else{
									$getGenome=mysqli_query($conn,"select * from genome");
									$getGenomeRows=mysqli_fetch_array($getGenome);
									$genomeName=$getGenomeRows['genomeName'];
							?>
								<option VALUE = "<?php echo $genomeName ?>FASTA"> Whole Genome</option>
								<option VALUE = "<?php echo $genomeName ?>FAA"> Protein</option>
								<option VALUE = "<?php echo $genomeName ?>FFN"> Nucleotide</option>
							<?php 
								}
							?>
							</select></td>
						</tr>
						<tr>
							<td><a href="../../blast/docs/newoptions.html#expect" target="_blank">Expect</a></td>
							<td>:</td>
							<td><select name = "EXPECT">
								<option selected> 0.0001 
								<option value=0.01> 0.01 
								<option value=1> 1 
								<option value=10> 10 
								<option value=100> 100 
								<option value=1000> 1000 
							</select></td>
						</tr>
					</table>
					<table>
						<tr>
							<td><a href="../../blast/docs/options.html#alignmentviews" target="_blank">Alignment view</a></td>
							<td>:</td>
							<td><select name = "ALIGNMENT_VIEW">
								<option value=0> Pairwise
								<option value=1> query-anchored showing identities
								<option value=2> query-anchored without identities
								<option value=3> flat query-anchored with identities
								<option value=4> flat query-anchored without identities
								<option value=5> BLAST XML
								<option value=6> Tabular
								<option value=7> Tabular with comment lines
								<option value=8> Text ASN.1
								<option value=9> Binary ASN.1
								<option value=10> Comma-separated values
								<option value=11> BLAST archieve format
							</select></td>
						</tr>
					</table><br>
					<br>
					<P align="center">
					<INPUT TYPE="button" VALUE="Clear" onClick="MainBlastForm.SEQUENCE.value='';MainBlastForm.SEQFILE.value='';MainBlastForm.SEQUENCE.focus();">
					<INPUT TYPE="submit" VALUE="BLAST!"><br>
				</FORM>
				</td></tr></td></tr>
			</table><br>
			</table>
		</div>
                </div>
	<footer class="footer">
                <p class="copyright pull-right">
                   Copyright &copy; <script>document.write(new Date().getFullYear())</script> <a href="http://www.mgi-nibm.my" target="_blank">Malaysia Genome Institute</a>
                </p>
        </footer>
	
	</div>
</div>
</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	<script type="text/javascript">
    	$(document).ready(function(){

        	demo.initChartist();

        	$.notify({
            	icon: 'pe-7s-refresh-2',
            	message: "Welcome to <b>Entero Database</b> - Your Genome Session is cleared"

            },{
                type: 'info',
                timer: 4000
            });

    	});
	</script>
</html>
<div class="loginmodal">
  
  <form class="loginmodal-content animate" method="post" action="../../home.php" onsubmit="return validate();"/>
        
    <div class="imgcontainer">
      <span onclick="document.getElementsByClassName('loginmodal')[0].style.display='none'" class="close" title="Close PopUp">&times;</span>
	  <img src="../../images/bacteria.jpg" alt="Avatar" class="avatar">
      <h1 class="logintitle">Login</h1>
    </div>
    <div class="logincontainer">
	  <p class="username">Username: <input type="text" name="username" id="username" /></p>
	  <p class="password">Password: <input type="password" name="password" id="password" /></p>        
      <button class="loginbutton" type="submit">Login</button>
      <!-- <input type="checkbox" style="margin:26px 30px;"> Remember me      
      <a href="#" style="text-decoration:none; float:right; margin-right:34px; margin-top:26px;">Forgot Password ?</a> -->
	  <script> loginPopClose();</script>
    </div>
    
  </form>
  
</div>
