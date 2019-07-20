<!doctype html>
<!-- 	
	Here having 3 main function which including search result,gene list and gene details in this php file
	Suggestion to backup before any modification
-->

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
	<link href="../../css/style.css" rel="stylesheet" type="text/css" media="all">
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
        <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
	<link href="../../css/popup.css" rel="stylesheet" type="text/css" media="all">
	<script type="text/javascript" src="assets/js/java.js"></script>
</head>
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
			<?php if ($_SESSION["LEVEL"] == 1){ ?>
				<li>
					<a href="upload1.php"><i class="pe-7s-cloud-upload"></i><p>Upload</p></a>
				</li>
			<?php	} ?>
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
					<p class="navbar-brand">Gene List</p>
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
				<?php	}
				else {
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

	<?php 
		//When scaffold is selected, genome is selected, no search request and no gene selected
		//Gene list of specific scaffold displayed
		if ((isset($_GET['scaffoldID']))||isset($_SESSION['scaffoldID'])&&!(isset($_GET['option']))&&!(empty($_SESSION['genomeID']))&&!(isset($_GET['seqName']))){
			if(isset($_GET['scaffoldID'])||!(empty($_SESSION['scaffoldID']))&&!(isset($_GET['seqName']))){
				if(isset($_GET['scaffoldID'])){
					$scaffoldID=$_GET['scaffoldID'];
					$_SESSION['scaffoldID']=$scaffoldID;
				}
				else if(!empty($_SESSION['scaffoldID'])){
					$scaffoldID=$_SESSION['scaffoldID'];
				}
			if(empty($_SESSION['genomeID'])){
				
				$genomeIDQuery=mysqli_query($conn,"select geneID from scaffold where scaffoldID='$scaffoldID'");
				$genomeIDQueryRows=mysqli_fetch_array($genomeIDQuery);
				$genomeID=$genomeIDQueryRows['geneID'];
			}
			else if(!empty($_SESSION['genomeID'])){
				$genomeID=$_SESSION['genomeID'];
			}
			$genomeNameQuery=mysqli_query($conn,"select * from genome where genomeID='$genomeID'");
			$genomeNameQueryName=mysqli_fetch_array($genomeNameQuery);
			$genomeName=$genomeNameQueryName['genomeName'];
			$scaffoldQuery = mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'") or die (mysqli_error($conn));
				$gene=mysqli_query($conn,"select * from blastresult where seqName like '%$scaffoldID%'");?>
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href ="genes.php?scaffoldID=<?php echo $scaffoldID ?>" >Gene List of <?php echo $scaffoldID ?></a></h4>
				<form class="navbar-brand" method='GET' action="genes.php">
					<select name='scaffoldID'>
					<?php while ($rows=mysqli_fetch_array($scaffoldQuery)){ ?>
						<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
					<?php } ?>
					</select>
					<button type="submit"><i class="fa fa-search"></i></button>
				</form>
				<form action='genes.php' method='GET'>
					<div class="content table-responsive table-full-width">
                                                <table class="table table-hover table-striped">
							<tr>
								<td>Genome</td>
								<td><select name="genomeName">
								<?php
									$searchGenomeQuery=mysqli_query($conn,"select genomeName from genome"); ?>
									<option value="NULL">All</option><?php
									while($searchGenomeQueryRows=mysqli_fetch_array($searchGenomeQuery)){ ?>
									<option value="<?php echo($searchGenomeQueryRows['genomeName']); ?>"><?php echo($searchGenomeQueryRows['genomeName']); ?></option>
								<?php } ?>
								</select>
								<td><b>Option :</td>				
								<td><select name="option">
									<option value="description">Description</option>
									<option value="goID">GO ID</option>
									<option value="goName">GO  Name</option>
									<option value="enzymeName">Enzyme Name</option>
									<option value="interproGOID">Interpro GO ID</option>
									<option value="interproGOName">Interpro GO Name</option>
								</select>
								</td><td><b>Keywords :</td>
								<td>
									<input type='text' name="keyword" size="30">
									<input name="submit" value="Search" type="submit"></td>
							</tr>
						</table>
					</div>
				</form>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Genes ID</th>
                                        <th>Description</th>
                                    </thead>
                                    <tbody>
                                            <?php
						while ($rows = mysqli_fetch_array($gene)){
							$description=$rows['description'];
							$seqName=$rows['seqName']
                                            ?>
                                        <tr>
                                                <td class="rows">
							<a href="genes.php?seqName=<?php echo $seqName; ?>"><?php echo $seqName; ?> </a>
                                                </td>
                                                <td class="rows"><?php echo $description?>
                                                </td>
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
	<?php 	} 
		else{ 
			$scaffoldQuery2 = mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'") or die (mysqli_error($conn));
	?>
	<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href ="genes.php" >Gene List of <?php echo $genomeName ?></a></h4>
					<form class="navbar-brand" method='GET' action="genes.php">
						<select name='scaffoldID'>
							<?php while ($rows=mysqli_fetch_array($scaffoldQuery)){ ?>
							<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
					<form action='genes.php' method='GET'>
						<div class="content table-responsive table-full-width">
                                                	<table class="table table-hover table-striped">
								<tr>
									<td>Genome</td>
									<td><select name="genomeName">
									<?php
										$searchGenomeQuery=mysqli_query($conn,"select genomeName from genome")?>
										<option value="NULL">All</option><?php
										while($searchGenomeQueryRows=mysqli_fetch_array($searchGenomeQuery)){ ?>
										<option value="<?php echo($searchGenomeQueryRows['genomeName']); ?>"><?php echo($searchGenomeQueryRows['genomeName']); ?></option>
									<?php } ?>
									</select>
									<td><b>Option :</td>				
									<td><select name="option">
										<option value="description">Description</option>
										<option value="goID">GO ID</option>
										<option value="goName">GO  Name</option>
										<option value="enzymeName">Enzyme Name</option>
										<option value="interproGOID">Interpro GO ID</option>
										<option value="interproGOName">Interpro GO Name</option>
									</select>
									</td><td><b>Keywords :</td>
									<td>
										<input type='text' name="keyword" size="30">
										<input name="submit" value="Search" type="submit"></td>
								</tr>
							</table>
						</div>	
					</form>
                		</div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Genes ID</th>
                                        <th>Description</th>
                                    </thead>
                                    <tbody>
					<?php	while($scaffoldRows=mysqli_fetch_array($scaffoldQuery2)or die (mysqli_error($conn))){
						$scaffoldID=$scaffoldRows['scaffoldID'];
						$gene=mysqli_query($conn,"select * from blastresult where seqName like '%$scaffoldID%'");
						while($genesRows=mysqli_fetch_array($gene)){ ?>
							<tr>
								<td class="rows">
									<a href="genes.php?seqName=<?php echo $genesRows['seqName']; ?>"><?php echo $genesRows['seqName']; ?></a>
								</td>
								<td class="rows"><?php echo $genesRows['description']; ?></td>
							</tr>
						<?php		}
					}
					?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php		} 
	}
	else if(isset($_GET['seqName'])&&!(isset($_GET['option']))){ 
		$seqName=$_GET['seqName'];
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
		//type 1 for protein seqeunces, type 0 for nucleotide sequences
		$query1 = mysqli_query($conn,"select * from geneseq where geneSeqName='$seqName'and type='1'");
		$rows1 = mysqli_fetch_array($query1);
		$query6 = mysqli_query($conn,"select * from geneseq where geneSeqName like '$seqName%'and type='0'");
		$rows6 = mysqli_fetch_array($query6);?>
		<div class="content">
            		<div class="container-fluid">
                	<div class="row">
                    	<div class="col-md-12">
                       	<div class="card card-plain">
                        <div class="header">
                                <h4 class="title"><a href="genes.php?scaffoldID=<?php echo $_SESSION['scaffoldID'] ?>">Gene List</a>
				<?php echo(">".$BLASTqueryRows['seqName']); ?>
				</h4>			
			    </div>
                            <div class="content table-responsive table-full-width">
                                <table class="responstable">
                                    <tbody>
                                        <tr>
						<th data-th="Sequence Name"><span>Sequence Name</span></th>
						<td><?php echo $BLASTqueryRows['seqName']; ?></td>
					</tr>
					<tr>
						<th data-th="Description"><span>Description</span></th>
						<td><?php echo $BLASTqueryRows['description']; ?></td>
					</tr>
					<tr>
						<th data-th="Start"><span>Start</span></th>
						<td><?php echo $rows3['start']; ?></td>
					</tr>
					<tr>
						<th data-th="End"><span>End</span></th>
						<td><?php echo $rows3['end']; ?></td>
					</tr>
					<tr>
						<th data-th="Strand"><span>Strand</span></th>
						<td><?php echo $rows3['strand']; ?></td>
					</tr>
					<tr>
						<th data-th="Score"><span>Score</span></th>
						<td><?php echo $rows3['score']; ?></td>
					</tr>
					<tr>
						<th data-th="EValue"><span>Expected value</span></th>
						<td><?php echo $BLASTqueryRows['e-value']; ?></td>
					</tr>
					<?php if ($BLASTqueryRows['enzymeCode']!=NULL){ ?>
					<tr>
						<th data-th="EnzymeCode"><span>Enzyme Code</span></th>
						<td><?php echo $BLASTqueryRows['enzymeCode']; ?></td>
					</tr>
					<?php } 
					if ($BLASTqueryRows['goID']!=NULL){
					?>
					<tr>
						<th data-th="goID"><span>GO ID</span></th>
						<td>
							<?php	
								$GOs=explode(';',$BLASTqueryRows['goID']);
								foreach($GOs as $GO){
								$GOpieces = explode(':',$GO,2);
							?>
							<!-- if link cannot open, modify a_href target here-->
							<a href="https://www.ebi.ac.uk/QuickGO/term/<?php echo $GOpieces[1];?>" target="_blank">
								<?php echo "$GO, "?>
							</a>
							<?php
								}
							?>
						</td>
					</tr>
					<?php }
					if ($BLASTqueryRows['goName']!=NULL){
					?>
					<tr>
						<th data-th="goName"><span>GO-Name</span></th>
						<td><?php echo $BLASTqueryRows['goName']; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th data-th="Hits"><span>Hits</span></th>
						<td><?php echo $BLASTqueryRows['hits']; ?></td>
					</tr>
					<tr>
						<th data-th="interProGOID"><span>InterPro GO-ID</span></th>
						<td>
							<?php	
								$InterProGOs=explode(';',$BLASTqueryRows['interproGOID']);
								foreach($InterProGOs as $InterProGO){
								$InterProGOpieces = explode(':',$InterProGO,2);
							?>
							<!-- if link cannot open, modify a_href target here-->
							<a href="https://www.ebi.ac.uk/QuickGO/term/<?php echo $InterProGOpieces[1];?>" target="_blank">
								<?php echo "$InterProGO, "?>
							</a>
							<?php
								}
							?>
						</td>
					</tr>
					<tr>
						<th data-th="interProGOName"><span>InterPro GO Name</span></th>
						<td><?php echo $BLASTqueryRows['interproGOName']; ?></td>
					</tr>
					<tr>
						<th data-th="interProID"><span>Interpro ID</span></th>
						<td><?php echo $BLASTqueryRows['interproID']; ?></td>
					</tr>
					<tr>
						<th data-th="length"><span>Length</span></th>
						<td><?php echo $BLASTqueryRows['length']; ?></td>
					</tr>
					<?php if ($BLASTqueryRows['numofGo']!=NULL){ ?>
					<tr>
						<th data-th="Number of GO"><span>Number of GO</span></th>
						<td><?php echo $BLASTqueryRows['numofGo']; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th data-th="simMean"><span>Sim Mean</span></th>
						<td><?php echo $BLASTqueryRows['simMean']; ?></td>
					</tr>
					<tr>
						<th data-th="status"><span>Status</span></th>
						<td><?php echo $BLASTqueryRows['status']; ?></td>
					</tr>
					<tr>
						<th data-th="tags"><span>Tags</span></th>
						<td><?php echo $BLASTqueryRows['tags']; ?></td>
					</tr>
					<?php if ($rows2['mappingID']!=NULL){ ?>
					<tr>
						<th data-th="mapping"><span>Mapping</span></th>
						<td class="rows"><a href="viewMapping.php?name=<?php echo $BLASTqueryRows['seqName']; ?>">Mapping Link</a></td>
					</tr>
					<?php } ?>
					<tr>
						<th data-th="AminoAcidSeqeunces"><span>Amino Acid Sequences</span></th>
						<td><?php echo $rows1['geneSeq'];?></td>
					</tr>
					<tr>
						<th data-th="NucleotideSeqeunces"><span>Nucleotide Sequences</span></th>
						<td><?php echo $rows6['geneSeq'];?></td>
					</tr>
					<tr>
						<th><span>Download Sequence</span></th>
						<td>	
							 <form>
								<input type="button" value="Amino Acid" onclick="window.location.href='../singleGeneDownloadFAA.php?gene=<?php echo $seqName ?>'" />
								<input type="button" value="Nucleotide" onclick="window.location.href='../singleGeneDownloadFFN.php?gene=<?php echo $seqName ?>'" />
								<input type="button" value="Whole" onclick="window.location.href='../singleGeneDownloadTXT.php?gene=<?php echo $seqName ?>'" />
							</form> 
						</td>
					</tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php		}  
		//if search function requested and order is inactivated
		//search result list will be display
		//2 condition included: select genome or without select genome
		else if(isset($_GET['option'])&&!(isset($_GET['orderby']))){
			$option=$_GET['option'];
			$keyword=$_GET['keyword'];
			//when genome selected, search against specific genome
			if (isset($_SESSION['genomeName'])){
				$genomeName=$_GET['genomeName'];
				$query4=mysqli_query($conn,"select * from genome where genomeName='$genomeName'");
				$rows4=mysqli_fetch_array($query4) or die (mysqli_error($conn));
				$genomeID=$rows4['genomeID'];
				$query5=mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'");
				$scaffoldArray=[];
				while($rows5=mysqli_fetch_array($query5)){
					array_push($scaffoldArray,"'%".$rows5['scaffoldID']."%'");
				}
				$string = implode(' OR seqName LIKE ', $scaffoldArray);
				$query2=mysqli_query($conn,"select * from blastresult where (seqName like $string) and $option like '%$keyword%'"); 	
				$scaffoldQuery = mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'") or die (mysqli_error($conn));		
?>
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href="genes.php?scaffoldID=<?php echo $SESSION['scaffoldID'] ?>">Gene List</a></h4>
					<form class="navbar-brand" method='GET' action="genes.php">
						<select name='scaffoldID'>
							<?php while ($rows=mysqli_fetch_array($scaffoldQuery)){ ?>
								<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
					<form action='genes.php' method='GET'>
						<div class="content table-responsive table-full-width">
                                                	<table class="table table-hover table-striped">
								<tr>
									<td>Genome</td>
									<td><select name="genomeName">
									<?php $searchGenomeQuery=mysqli_query($conn,"select genomeName from genome"); ?>
										<option value="NULL">All</option><?php
										while($searchGenomeQueryRows=mysqli_fetch_array($searchGenomeQuery)){ ?>
										<option value="<?php echo($searchGenomeQueryRows['genomeName']); ?>"><?php echo($searchGenomeQueryRows['genomeName']); ?></option>
										<?php } ?>
									</select>
									<td><b>Option :</td>				
									<td><select name="option">
										<option value="description">Description</option>
										<option value="goID">GO ID</option>
										<option value="goName">GO  Name</option>
										<option value="enzymeName">Enzyme Name</option>
										<option value="interproGOID">Interpro GO ID</option>
										<option value="interproGOName">Interpro GO Name</option>
									</select>
									</td><td><b>Keyword :</td>
									<td>
										<input type='text' name="keyword" size="30">
										<input name="submit" value="Search" type="submit"></td>
								</tr>
							</table>
						</div>
					</form>
				</div>
                            <div class="content table-responsive table-full-width" style="height:25%; overflow:scroll;">
                                <!-- <table class="table table-hover table-striped"> -->
				<table class="responstable">
                                    <tbody>
					<tr>
						<th data-th="SequenceName"><a href ='?genomeName=<?php echo $genomeName ?>&option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby="seqName"'><span>Sequence Name</span></th>
						<th data-th="<?php  echo $option ?>"><a href ='?genomeName=<?php echo $genomeName ?>&option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby=<?php echo $option ?>'><span><?php echo $option ?></span></th>
					</tr>
					<?php while ($rows2=mysqli_fetch_array($query2)){ ?>
					<tr>
						<td><a href="genes.php?name=<?php echo($rows2['seqName']); ?>"><?php echo $rows2['seqName']; ?></a></td>
						<td><?php echo $rows2[$option]; ?></td>
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
		<?php 	}
			//search without genome selected 	
			else{
				$query2=mysqli_query($conn,"select * from blastresult where $option like '%$keyword%'"); 
				$scaffoldQuery = mysqli_query($conn,"select scaffoldID from scaffold") or die (mysqli_error($conn));	?>
	<div class="content">
            <div class=container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
				<?php
				if (isset($_SESSION['scaffoldID'])){
				?>
                                <h4 class="title"><a href="genes.php?scaffoldID=<?php echo $SESSION['scaffoldID'] ?>">Gene List</a></h4>
				<?php 
				}
				else{
				?>
				<h4 class="title"><a href="genes.php">Gene List</a></h4>
				<?php
				}
				?>
				<form class="navbar-brand" method='GET' action="genes.php">
					<select name='scaffoldID'>
						<?php while ($rows=mysqli_fetch_array($scaffoldQuery)){ ?>
						<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
						<?php } ?>
					</select>
						<button type="submit"><i class="fa fa-search"></i></button>
				</form>
				<form action='genes.php' method='GET'>
					<div class="content table-responsive table-full-width">
                                                <table class="table table-hover table-striped">
							<tr>
								<td>Genome</td>
								<td><select name="genomeName">
								<?php
									$searchGenomeQuery=mysqli_query($conn,"select genomeName from genome"); ?>
									<option value=NULL>All</option><?php
									while($searchGenomeQueryRows=mysqli_fetch_array($searchGenomeQuery)){ ?>
										<option value="<?php echo($searchGenomeQueryRows['genomeName']); ?>"><?php echo($rows['genomeName']); ?></option>
									<?php } ?>
								</select>
								<td><b>Option :</td>				
								<td><select name="option">
									<option value="description">Description</option>
									<option value="goID">GO ID</option>
									<option value="goName">GO  Name</option>
									<option value="enzymeName">Enzyme Name</option>
									<option value="interproGOID">Interpro GO ID</option>
									<option value="interproGOName">Interpro GO Name</option>
								</select>
								</td><td><b>Keywords :</td>
								<td>
									<input type='text' name="keyword" size="30">
									<input name="submit" value="Search" type="submit"></td>
							</tr>
						</table>
					</div>
				</form>
			    </div>
                            <div class="content table-responsive table-full-width" style="height:25%; overflow:scroll;">
			<!-- <table class="table table-hover table-striped"> -->
				<table class="responstable">
					<tbody>
						<tr>
							<th data-th="SequenceName"><a href ='?&option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby="seqName"'><span>Sequence Name</span></th>
							<th data-th="<?php  echo $option ?>"><a href ='?&option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby=<?php echo $option ?>'><span><?php echo $option ?></span></th>
						</tr>
						<?php while ($rows2=mysqli_fetch_array($query2)){ ?>
						<tr>
							<td><a href="genes.php?seqName=<?php echo($rows2['seqName']); ?>"><?php echo $rows2['seqName']; ?></a></td>
							<td><?php echo $rows2[$option]; ?></td>
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
	<?php 	} ?>
<?php } 
		//if order funciton is activated, search result will be sort by desc or asc
		else if(isset($_GET['orderby'])){ 
			$option=$_GET['option'];
			$keyword=$_GET['keyword'];
			$orderby =  $_GET['orderby'];
			if (isset($_GET['order'])){
				if ($_GET['order']=='asc'){
					$order='desc';
				}
				if ($_GET['order']=='desc'){
					$order='asc';
				}
			}
			if(!isset($_GET['order'])){
				$order='asc';
			}
			if($_GET['orderby']){
				$orderby=' order by '.$orderby;
			}
			if (isset($_GET['genomeName'])){
				$genomeName=$_GET['genomeName'];
				$query4=mysqli_query($conn,"select * from genome where genomeName='$genomeName'");
				$rows4=mysqli_fetch_array($query4) or die (mysqli_error($conn));
				$genomeID=$rows4['genomeID'];
				$query5=mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'");
				$scaffoldArray=[];
				while($rows5=mysqli_fetch_array($query5)){
					array_push($scaffoldArray,"'%".$rows5['scaffoldID']."%'");
				}
				$string = implode(' OR seqName LIKE ', $scaffoldArray);
				$query2=mysqli_query($conn,"select * from blastresult where (seqName like $string) and $option like '%$keyword%' ".$orderby." ".$order."");
				$scaffoldQuery = mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'") or die (mysqli_error($conn));
			}
			else if(!isset($_GET['genomeName'])){
				$query2=mysqli_query($conn,"select * from blastresult where $option like '%$keyword%'".$orderby." ".$order.""); 
				$scaffoldQuery = mysqli_query($conn,"select scaffoldID from scaffold") or die(mysqli_error($conn));
			}
?>
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href="genes.php?scaffoldID=<?php echo $SESSION['scaffoldID'] ?>">Gene List</a></h4>
					<form class="navbar-brand" method='GET' action="genes.php">
						<select name='scaffoldID'>
							<?php while ($rows=mysqli_fetch_array($scaffoldQuery)){ ?>
							<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
					<form action='genes.php' method='GET'>
						<div class="content table-responsive table-full-width">
                                                	<table class="table table-hover table-striped">
								<tr>
									<td>Genome</td>
									<td><select name="genomeName">
									<?php
										$searchGenomeQuery=mysqli_query($conn,"select genomeName from genome");?>
										<option value=NULL>All</option><?php
										while($searchGenomeQueryRows=mysqli_fetch_array($searchGenomeQuery)){ ?>
											<option value="<?php echo($searchGenomeQueryRows['genomeName']); ?>"><?php echo($searchGenomeQueryRows['genomeName']); ?></option>
										<?php } ?>
									</select>
									<td><b>Option :</td>				
									<td><select name="option">
										<option value="description">Description</option>
										<option value="goID">GO ID</option>
										<option value="goName">GO  Name</option>
										<option value="enzymeName">Enzyme Name</option>
										<option value="interproGOID">Interpro GO ID</option>
										<option value="interproGOName">Interpro GO Name</option>
									</select>
									</td><td><b>Keywords :</td>
									<td>
										<input type='text' name="keyword" size="30">
										<input name="submit" value="Search" type="submit"></td>
								</tr>
							</table>
						</div>
					</form>
				</div>
                            <div class="content table-responsive table-full-width" style="height:25%; overflow:scroll;">
                                <table class="responstable">
					<tbody>
						<tr>
							<?php if(!isset($_GET['genomeName'])){ ?>
							<th data-th="SequenceName"><a href ='?option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby="seqName"&order=<?php echo $order ?>'><span>Sequence Name</span></th>
							<?php } ?>
							<?php if(isset($_GET['genomeName'])){ ?>
							<th data-th="SequenceName"><a href ='?genomeName=<?php echo $genomeName ?>&option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby="seqName"&order=<?php echo $order ?>'><span>Sequence Name</span></th>
							<?php } ?>
							<th data-th="<?php  echo $option ?>"><a href ='?&option=<?php echo $option ?>&keyword=<?php echo $keyword ?>&orderby=<?php echo $option ?>&order=<?php echo $order ?>'><span><?php echo $option ?></span></th>
						</tr>
							<?php 	while ($rows2=mysqli_fetch_array($query2)){ ?>
						<tr>
							<td><a href="genes.php?seqName=<?php echo($rows2['seqName']); ?>"><?php echo $rows2['seqName']; ?></a></td>
							<td><?php echo $rows2[$option]; ?></td>
						</tr>
							<?php 	} ?>
					</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php 	} 
		else{ 
			$allQuery=mysqli_query($conn,"select * from blastresult");
			$scaffoldQuery = mysqli_query($conn,"select scaffoldID from scaffold") or die(mysqli_error($conn));
?>
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href="genes.php">Gene List</a></h4>
					<form class="navbar-brand" method='GET' action="genes.php">
						<select name='scaffoldID'>
							<?php while ($rows=mysqli_fetch_array($scaffoldQuery)){ ?>
							<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
					<form action='genes.php' method='GET'>
						<div class="content table-responsive table-full-width">
							<table class="table table-hover table-striped">
								<tr>
								<td>Genome</td>
								<td><select name="genomeName">
								<?php
									$searchGenomeQuery=mysqli_query($conn,"select genomeName from genome"); ?>
									<option value=NULL>All</option><?php
									while($searchGenomeQueryRows=mysqli_fetch_array($searchGenomeQuery)){ ?>
									<option value="<?php echo($searchGenomeQueryRows['genomeName']); ?>"><?php echo($searchGenomeQueryRows['genomeName']); ?></option>
								<?php } ?>
								</select>
									<td><b>Option :</td>				
									<td><select name="option">
										<option value="description">Description</option>
										<option value="goID">GO ID</option>
										<option value="goName">GO  Name</option>
										<option value="enzymeName">Enzyme Name</option>
										<option value="interproGOID">Interpro GO ID</option>
										<option value="interproGOName">Interpro GO Name</option>
									</select>
									</td><td><b>Keywords :</td>
									<td>
										<input type='text' name="keyword" size="30">
										<input name="submit" value="Search" type="submit"></td>
								</tr>
							</table>
						</div>
					</form>
				</div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Genes ID</th>
                                        <th>Description</th>
				
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
						while ($rows = mysqli_fetch_array($allQuery)){
							$seqName=$rows['seqName'];
							$description=$rows['description'];
                                            ?>
                                                <td class="rows">
							<a href="genes.php?seqName=<?php echo $seqName; ?>">
							<?php echo $seqName; ?>
                                                    	</a>
                                                </td>
                                                <td class="rows"><?php echo $description ?></a>
                                                </td>
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
