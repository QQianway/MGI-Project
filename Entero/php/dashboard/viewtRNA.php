<!doctype html>
<html lang="en">
<?php 
	session_start();
	if (!isset($_SESSION['USER']))
        {
                header('Location: ../../home.php');
        }
	if (!isset($_GET['id']))
	{
		header ('Location: tRNA.php');
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
		require_once ("../config.php");
			$id=$_GET['id'];
			$query = mysqli_query($conn,"select * from trna where tRNAID='$id'");
			$rows = mysqli_fetch_array($query);
			$start=$rows['start'];
			$end=$rows['end'];
			$query2 = mysqli_query($conn, "select * from trnaseq where tags like '%$start%' and tags like '%$end%' ");
			$rows2 = mysqli_fetch_array($query2);
	?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-plain">
                            <div class="header">
                                <h4 class="title">tRNA <?php echo $_GET['id']; ?></h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="responstable">
                                    <tr>
										<th data-th="Scaffold ID"><span>Scaffold ID</span></th>
										
										<td><?php echo $rows['scaffoldID']; ?>
										</td>
									</tr>
									<tr>
										<th data-th="type"><span>Source</span></th>
										<td><?php echo $rows['source']; ?></td>
									</tr>
									<tr>
										<?php if ($rows['score']<0){ ?>
										<th data-th="score"><span>Score</span></th>
										<td><?php echo $rows['score']; ?></td>
										<?php } ?>
									</tr>
									<tr>
										<th data-th="Start End"><span>Start/End</span></th>
										<td><?php echo $rows['start']."/".$rows['end']; ?></td>
									</tr>
									<?php
										$attributes = explode(";",$rows['attribute']);
										foreach($attributes as $attribute)
										{
											$content=explode("=",$attribute);
									?>
									<tr>
										<th><span><?php echo $content[0]; ?></span></th>
										<td><?php echo $content[1]; ?></td>
									</tr>
									<?php	
										}
									?>
									<tr>
										<th data-th="seq"><span>Sequence</span></th>
										<td><?php echo $rows2['seq']; ?></td>
									</tr>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
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

	<<!-- script type="text/javascript">
    	$(document).ready(function(){

        	demo.initChartist();

        	$.notify({
            	icon: 'pe-7s-gift',
            	message: "Welcome to <b>Light Bootstrap Dashboard</b> - a beautiful freebie for every web developer."

            },{
                type: 'info',
                timer: 4000
            });

    	});
	</script> -->

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
