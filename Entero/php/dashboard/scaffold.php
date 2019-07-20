<!doctype html>
<!-- There having 3 condition in SCAFFOLD tab:
	i.	Genome is selected: first scaffold information of the  genome will be display
	ii.	Scaffold is selected: the scafffold information will be display
	iii. 	None of genome and scaffold selected: Scaffold list will be display
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
	<link href="../../css/style.css" rel="stylesheet" type="text/css" media="all">

    <!--     Fonts and icons     -->
    	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    	<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
	<link href="../../css/popup.css" rel="stylesheet" type="text/css" media="all">
	<script type="text/javascript" src="assets/js/java.js"></script>
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
				if ($_SESSION["LEVEL"] == 1){
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
				if (isset($_SESSION['genomeID'])){
					$id = $_SESSION['genomeID'];
					$querytitle = mysqli_query($conn,"select * from genome where genomeID = '$id'");
					$rowtitle= mysqli_fetch_array($querytitle);
					echo $rowtitle['genomeName'];
				}
				else{
					echo "Scaffold";
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
				if ($_SESSION["USER"] != null){
			?>
					<a href="../logout.php">Logout</a>
				<?php
				}
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
		//if genome is selected, one of the scaffold of the genome will be display
		if (!empty($_SESSION['genomeID'])||isset($_GET['genomeID'])){
			if (!empty($_SESSION['genomeID'])){
				$genomeID=$_SESSION['genomeID'];
			}
			elseif(isset($_GET['genomeID'])){
				$genomeID=$_GET['genomeID'];
			}
			$scaffoldquery = mysqli_query($conn,"select scaffoldID from scaffold where geneID='$genomeID'");
			if(isset($_GET['scaffoldID'])||!(empty($_SESSION['scaffoldID']))){
				if(isset($_GET['scaffoldID'])){
					$scaffoldID=$_GET['scaffoldID'];
					$_SESSION['scaffoldID']=$scaffoldID;
				}
				else if(!empty($_SESSION['scaffoldID'])){
					$scaffoldID=$_SESSION['scaffoldID'];
				}
				$scaffold=mysqli_query($conn,"select * from scaffold where scaffoldID='$scaffoldID'");
			}
			else{
				$scaffold=mysqli_query($conn,"select * from scaffold where geneID='$genomeID'");
			}
			$rows=mysqli_fetch_array($scaffold);
			$scaffoldID=$rows['scaffoldID'];
			$fileName=$rows['scaffoldID'];
			$extension=".png";
			$imgPath=$fileName.$extension;
			$scaffoldPath=$fileName.".fasta"; 
			$geneQuery = mysqli_query($conn,"select count(*) from genes where scaffoldID like '%$scaffoldID%'");
			$row2 = mysqli_fetch_array($geneQuery);
			$rRNAQuery = mysqli_query($conn,"select count(*) from rrna where scaffoldID like '%$scaffoldID%'");
			$tRNAQuery = mysqli_query($conn,"select * from trna where scaffoldID like '%$scaffoldID%'");
			$row3 = mysqli_fetch_array($rRNAQuery);
			$row4 = mysqli_fetch_array($tRNAQuery);
	?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href ="scaffold.php?genomeID=<?php echo $scaffoldID ?>" >Scaffold <?php echo $scaffoldID ?></a></h4>
					<form class="navbar-brand" method='GET' action="scaffold.php">
						<select name='scaffoldID'>
							<?php while ($rows=mysqli_fetch_array($scaffoldquery)){ ?>
							<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="responstable">
                                    	<tr>
						<th data-th="Scaffold ID"><span>Scaffold ID</span></th>
						<td><?php echo $scaffoldID ?></td>
					</tr>
					<tr>
						<th data-th="noGenes"><span>Number of Genes</span></th>
						<td><a href="genes.php?scaffoldID=<?php echo $scaffoldID; ?>"><?php echo $row2['count(*)']?></a></td>
					</tr>
					<?php if ($row3['count(*)']!=0){ ?>
					<tr>
						<th data-th="rRNA"><span>List rRNA</span></th>
						<td><a href="rRNA.php?id=<?php echo $scaffoldID; ?>">rRNA list</a></td>
					</tr>
					<?php } ?>
					<?php if ($row4['tRNAID']!=NULL){ ?>
					<tr>
						<th data-th="tRNA"><span>List tRNA</span></th>
						<td><a href="tRNA.php?id=<?php echo $scaffoldID; ?>">tRNA list</a></td>
					</tr>
					<?php } ?>
					<tr>
						<th data-th="locScaffold"><span>Scaffold in FASTA</span></th>
						<td>
							<a href="../../data/scaffold/<?php echo $scaffoldPath ?>">Link</a>
							<a href="../scaffoldDownload.php?file=<?php echo $scaffoldPath; ?>">Download</a>
						</td>
					</tr>
                                </table>
                            </div>
                        </div>
			<img src="../../data/plotter/<?php echo $imgPath; ?>" width="100%"/>
                    </div>
                </div>
            </div>
        </div>
	<?php } 
	//if scaffold is selected(from GENOME tab or SCAFFOLD tab), the scaffold selected will be display
	else if(isset($_GET['scaffoldID'])){
		$scaffoldquery = mysqli_query($conn,"select scaffoldID from scaffold");
		$scaffoldID=$_GET['scaffoldID'];
		$_SESSION['scaffoldID']=$scaffoldID;
		$scaffold=mysqli_query($conn,"select * from scaffold where scaffoldID='$scaffoldID'");
		$rows5=mysqli_fetch_array($scaffold);
		$_SESSION['genomeID']=$rows5['geneID'];
		//echo$_SESSION['genomeID'];
		$scaffoldID=$rows5['scaffoldID'];
		$fileName=$rows5['scaffoldID'];
		$extension=".png";
		$imgPath=$fileName.$extension;
		$scaffoldPath=$fileName.".fasta"; 
		$geneQuery = mysqli_query($conn,"select count(*) from genes where scaffoldID like '%$scaffoldID%'");
		$row2 = mysqli_fetch_array($geneQuery);
		$rRNAQuery = mysqli_query($conn,"select count(*) from rrna where scaffoldID like '%$scaffoldID%'");
		$tRNAQuery = mysqli_query($conn,"select * from trna where scaffoldID like '%$scaffoldID%'");
		$row3 = mysqli_fetch_array($rRNAQuery);
		$row4 = mysqli_fetch_array($tRNAQuery);
	?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title"><a href ="scaffold.php?genomeID=<?php echo $scaffoldID ?>" >Scaffold <?php echo $scaffoldID ?></a></h4>
					<form class="navbar-brand" method='GET' action="scaffold.php">
						<select name='scaffoldID'>
							<?php while ($rows=mysqli_fetch_array($scaffoldquery)){ ?>
							<option value="<?php echo $rows['scaffoldID']; ?>"><?php echo $rows['scaffoldID']; ?></option>
							<?php } ?>
						</select>
						<button type="submit"><i class="fa fa-search"></i></button>
					</form>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="responstable">
                                    	<tr>
						<th data-th="Scaffold ID"><span>Scaffold ID</span></th>
						<td><?php echo $scaffoldID ?></td>
					</tr>
					<tr>
						<th data-th="noGenes"><span>Number of Genes</span></th>
						<td><a href="genes.php?scaffoldID=<?php echo $scaffoldID; ?>"><?php echo $row2['count(*)']?></a></td>
					</tr>
					<?php if ($row3['count(*)']!=0){ ?>
					<tr>
						<th data-th="rRNA"><span>List rRNA</span></th>
						<td><a href="rRNA.php?id=<?php echo $scaffoldID; ?>">rRNA list</a></td>
					</tr>
					<?php } ?>
					<?php if ($row4['tRNAID']!=NULL){ ?>
					<tr>
						<th data-th="tRNA"><span>List tRNA</span></th>
						<td><a href="tRNA.php?id=<?php echo $scaffoldID; ?>">tRNA list</a></td>
					</tr>
					<?php } ?>
					<tr>
						<th data-th="locScaffold"><span>Scaffold in FASTA</span></th>
						<td>
							<a href="../../data/scaffold/<?php echo $scaffoldPath ?>">Link</a>
							<a href="../scaffoldDownload.php?file=<?php echo $scaffoldPath; ?>">Download</a>
						</td>
					</tr>
                                </table>
                            </div>
                        </div>
			<img src = "../../data/plotter/<?php echo $imgPath; ?>"/>
                    </div>
                </div>
            </div>
        </div>	
<?php		
	}
	//if genome and scaffold is not selected, scaffold list will be display
	else{ ?>
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title">Scaffold LIST</h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Scaffold ID</th>
                                        <th>Number of Genes</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                                $query="select * from scaffold";
                                                $result=mysqli_query($conn,$query);
						while ($rows = mysqli_fetch_array($result)){
							$scaffoldID=$rows['scaffoldID'];
							$geneQuery = mysqli_query($conn,"select count(*) from genes where scaffoldID like '%$scaffoldID%'");
							$row2 = mysqli_fetch_array($geneQuery);
                                            ?>
                                                <td class="rows"><a href="scaffold.php?scaffoldID=<?php echo $scaffoldID; ?>"> <?php echo $scaffoldID; ?></a>
                                                </td>
                                                <td class="rows"><a href="genes.php?scaffoldID=<?php echo $scaffoldID; ?>"><?php echo $row2['count(*)']?></a>
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
