<!doctype html>
<html lang="en">
<?php 
	session_start();
	require_once("../config.php");
        if (!isset($_SESSION['USER']))
        {
                 header('Location: ../../home.php');
        }

?>
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
	<link href="../../css/style.css" rel="stylesheet" type="text/css" media="all">
	<script type="text/javascript" src="./assets/js/java.js"></script>
	<link href="../../css/popup.css" rel="stylesheet" type="text/css" media="all">

</head>
<?php
	//BLAST program: Delete old BLAST result, generate new String for new BLAST, BLAST program command
	if(isset($_POST['SEQUENCE']) or isset($_FILES["SEQFILE"]["tmp_name"])){
		function deleteOldFiles($dirName,$days=0.1,$count = true) {
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
		function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		 
		echo deleteOldFiles("../../blast/query/");

		//BLAST command start here
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
		$writefile=fopen("../../blast/query/".$randomName."temp.txt","w")or die ("Unable to open file");
		fwrite($writefile,$sequence);
		//$command=$program." -db ../db/".$dataLib." -query $sequence"." -outfmt ".$view." -evalue ".$expect;
		$command="./".$program." -db ../db/".$dataLib." -query ../query/".$randomName."temp.txt"." -outfmt ".$view." -evalue ".$expect." -out ../query/".$randomName."output.txt";
		chdir('../../blast/bin/');
		$COMMAND=escapeshellcmd($command);
		exec($COMMAND);
		$readfile=fopen("../query/".$randomName."output.txt","r")or die ("Unable to open file");
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
				var randomName=<?=json_encode($randomName)?>;
				alert("BLAST COMPLETED");
				window.open("../../blast/query/"+randomName+"output.txt");
			</script><?php
		}
	}	
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
					<p class="navbar-brand">
						<?php 
							if (isset($_SESSION['genomeID']))
							{
								$id = $_SESSION['genomeID'];
								$querytitle = mysqli_query($conn,"select * from genome where genomeID = '$id'");
								$rowtitle= mysqli_fetch_array($querytitle);
								echo $rowtitle['genomeName'];
							}
							else if (isset($_GET['genomeID']))
							{
								$id = $_GET['genomeID'];
								$querytitle = mysqli_query($conn,"select * from genome where genomeID = '$id'");
								$rowtitle= mysqli_fetch_array($querytitle);
								echo $rowtitle['genomeName'];
							}
							else
							{
								echo "Genome";
							}
						?>	
					</p>
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
								if ($_SESSION["USER"] != null)
								{
							?>
								<a href="../logout.php">Logout</a>
							<?php
									}
								else {
							?>
								<a nohref onclick="document.getElementsByClassName('loginmodal')[0].style.display='block'" style="cursor:pointer">
									Login
								</a>
							<?php
									}
							?>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>

	<?php 
		//if genome session is selected or genome is select, genome details will be display
		//otherwise, genome list will be display

		//Genome detail display
		if (isset($_GET['genomeID'])||!(empty($_SESSION['genomeID']))){
			if(isset($_GET['genomeID'])){
				$_SESSION['genomeID']=$_GET['genomeID'];
				$genomeID = $_GET['genomeID'];
			}
			else if(!empty($_SESSION['genomeID'])){
				$genomeID=$_SESSION['genomeID'];
			}
			$dropdownlist = mysqli_query($conn,"select * from genome");
			$scaffoldquery = mysqli_query($conn,"select scaffoldID from scaffold where geneID=$genomeID");
			$rowscaffold = mysqli_fetch_array($scaffoldquery);
			$scaffoldID = $rowscaffold['scaffoldID'];
			$query = mysqli_query($conn,"select * from genome where genomeID=$genomeID");
			$rows = mysqli_fetch_array($query);
			$genomeName=$rows['genomeName'];
	?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title">Genome Detail <?php echo " of ".(strtok($genomeName,'_')) ?></h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                 <tr>
					<th data-th="Genome Name"><span>Genome Name</span></th>
					<?php 
						$genomePath=$genomeName.".fasta";
					?>
					<td><?php echo $rows['genomeName']."	"?><a href="../fastaDownload.php?file=<?php echo $genomePath; ?>">Download</a></td>
					<td rowspan="9">
					<div id="axle_bogie_border">
						<img src="../../images/chart.jpg"  alt="Chart" usemap="#GenomeMap">	
						<map name="GenomeMap">
							<area shape="rect" coords="238,37,325,74" href="genome.php?genomeID=<?php echo $genomeID ?>" alt="genome">
							<area shape="rect" coords="460,280,520,300" href="tRNA.php?id=<?php echo $scaffoldID ?>" alt="tRNA">
							<area shape="rect" coords="60,280,110,300" href="rRNA.php?id=<?php echo $scaffoldID ?>" alt="rRNA">
							<area shape="rect" coords="260,280,310,300" href="genes.php?scaffoldID=<?php echo $scaffoldID ?>" alt="genes">
							<area shape="rect" coords="245,160,320,180" href="scaffold.php?genomeID=<?php echo $genomeID ?>" alt="scaffold">
						</map>	
					</div>
					</td>
				</tr>
				<tr>
					<th data-th="Size"><span>Size</span></th>
					<td><?php echo $rows['size']?></td>
				</tr>
				<tr>
					<th data-th="Length"><span>Length</span></th>
					<td><?php echo $rows['length']?></td>
				</tr>	
				<tr>
					<th data-th="NoScaffold"><span>Number of Scaffold</span></th>
					<td>
					<?php 
						$genequery = mysqli_query($conn,"select count(*) from scaffold where geneID=$genomeID");
						$rowgene = mysqli_fetch_array($genequery);
						echo $rowgene['count(*)'];
					?></td>
				</tr>	
				<tr>
					<th data-th="NoGene"><span>Number of Gene</span></th>
					<td>
					<?php 
						$genequery = mysqli_query($conn,"select count(*) from genes where scaffoldID LIKE '$scaffoldID%'");
						$rowgene = mysqli_fetch_array($genequery);
						echo $rowgene['count(*)'];
					?>
					<a href="../geneDownloadGFF.php?file=<?php echo ($genomeName.".predictedgene.gff"); ?>">GFF</a>
					<a href="../geneDownloadFAA.php?file=<?php echo ($genomeName.".predictedgene.faa"); ?>">FAA</a>
					<a href="../geneDownloadFFN.php?file=<?php echo ($genomeName.".predictedgene.ffn"); ?>">FFN</a>
					</td>
				</tr>
				<tr>
					<th data-th="NotRNA"><span>Number of tRNA</span></th>
					<td>
					<?php 
						$tRNAquery = mysqli_query($conn,"select count(*) from trna where scaffoldID like '$scaffoldID%'");
						$rowtRNA = mysqli_fetch_array($tRNAquery);
						echo $rowtRNA['count(*)'];
					?>
					<a href="../tRNADownload.php?file=<?php echo ($genomeName.".trna.fasta"); ?>">Download</a>
					</td>
				</tr>
				<tr>
					<th data-th="NorRNA"><span>Number of rRNA</span></th>
					<td>
					<?php 
						$rRNAquery = mysqli_query($conn,"select count(*) from rrna where scaffoldID like '$scaffoldID%'");
						$rowrRNA = mysqli_fetch_array($rRNAquery);
						echo $rowrRNA['count(*)'];
					?>
					<a href="../rRNADownload.php?file=<?php echo ($genomeName.".rrna.fasta"); ?>">Download</a>
					</td>
				</tr>
				<tr>
					<th data-th="scaffold"><span>Scaffold</span></th>
					<td><ul>
					<?php 
						$scaffoldquery2 = mysqli_query($conn,"select scaffoldID from scaffold where geneID=$genomeID");
						while($rowscaffold2 = mysqli_fetch_array($scaffoldquery2)){
						$scaffoldID=$rowscaffold2['scaffoldID'];
					?>
					<li><a href="scaffold.php?scaffoldID=<?php echo $scaffoldID; ?>"> <?php echo $scaffoldID ?></a></li>
					<?php } ?>
						</ul></td>
				</tr>
				<tr>
					<th colspan="2" style="text-align: center;"><a href="../../jbrowse/?data=data/<?php echo $genomeName ?>">Genome Browser</a></th>
				</tr>
                                </table>
                            </div>
							<div class="header">
                                <h4 class="title">Genome BLAST To <?php echo(strtok($genomeName,'_')); ?></h4>
                            </div>
				<!-- BLAST Start Here -->					
 	                       <table border="1" width="100%">
 		                        <tr><td>
                		       	<table style="margin-left:5%;margin-right:5%" width="100%">
                        			<tr ><td >
                        			        <FORM ACTION="./genome.php" METHOD ="POST" NAME="MainBlastForm" ENCTYPE= "multipart/form-data"><br>
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
											<?php $genomeDBName=$genomeName; ?>
											<option VALUE = "<?php echo $genomeDBName ?>FASTA"> Whole Genome</option>
											<option VALUE = "<?php echo $genomeDBName ?>FAA"> Protein</option>
											<option VALUE = "<?php echo $genomeDBName ?>FFN"> Nucleotide</option>
										</select></td>
									</tr>
									<tr>
										<td><a href="../../blast/docs/newoptions.html#expect" target="_blank">Expect</a></td>
										<td>:</td>
										<td><select name = "EXPECT">
											<option selected> 0.0001 
											<option> 0.01 
											<option> 1 
											<option> 10 
											<option> 100 
											<option> 1000 
										</select></td>
									</tr>
								</table>
								<table>
									<tr>
										<td><a href="../blast/docs/options.html#alignmentviews" target="_blank">Alignment view</a></td>
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
								</td></tr>
							</td></tr>
						</FORM>
					</table><br>
				</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php } 
	//Genome list display
	else{ ?>
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title">Genome LIST</h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Genome ID</th>
                                        <th>Number of Scaffold</th>
                                        <th>Genome Name</th>
                                        <th>Size</th>
					<th>Length</th>
					<?php
						if ($_SESSION["LEVEL"] == 1){
					?>
					<th>Delete</th>
					<?php
						}
					?>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                                $query="select * from genome";
                                                $result=mysqli_query($conn,$query);
                                                while ($rows = mysqli_fetch_array($result))
                                                {
                                            ?>
                                                <td class="rows">
                                                    <?php echo $rows['genomeID']; ?>
                                                    </a>
                                                </td>
                                                <td class="rows"><?php echo $rows['noScaffold']; ?>
                                                </td>
                                                <td class="rows"><a href="genome.php?genomeID=<?php echo $rows['genomeID']; ?>"><?php echo $rows['genomeName']; ?></a>
                                                </td>
                                                <td class="rows"><?php echo $rows['size'] ?>
                                                </td>
						<td class="rows"><?php echo $rows['length'] ?>
                                                </td>
						<?php
							if ($_SESSION["LEVEL"] == 1){
						?>
						<td>
						<form method="POST" action="../deleteGenome.php" id="deleteform<?php echo $rows['genomeID']; ?>">
							<input type="hidden" name="genomeID" value="<?php echo $rows['genomeID']; ?>">
							<?php
								$geneID=$rows['genomeID'];
								echo "<a class='btn btn-danger btn-fill' nohref onclick='deleteValidate({$geneID})'> "; 
							?>
							Delete</a>
						</form>
						</td>
							<?php
							}
							?>
                                         </tr>
                                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php } ?>
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
