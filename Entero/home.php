<?php
session_start();
if (!isset($_SESSION['USER']))
{
	$_SESSION['USER'] = null;
	$_SESSION['ID'] = null;
	$_SESSION['LEVEL']=null;
}
include('./php/config.php');
?> 

<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link href="./layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
		<script type="text/javascript" src="./php/dashboard/assets/js/java.js"></script>
		<link href="./css/popup.css" rel="stylesheet" type="text/css" media="all">
	</head>
	<?php 
		if (isset($_POST['username']))
		{
			$myusername=$_POST['username'];
			$mypassword=$_POST['password'];
			$password=md5($mypassword);
			$sql="SELECT * FROM user WHERE username='$myusername' and password='$password'";

			$result=mysqli_query($conn,$sql);
			$rows=mysqli_fetch_array($result);
			$user_name=$rows['username'];
			$userID = $rows['userID'];
			$level = $rows['is_Admin'];
					
			// mysql_num_row is counting table row
			$count=mysqli_num_rows($result);
			// If result matched $myusername and $mypassword, table row must be 1 row
				 
			if($count==1){		
				
					$_SESSION["Login"] = "YES";
				// Add user information to the session (global session variables)
					$_SESSION['USER'] = $user_name;
					$_SESSION['ID'] = $userID;
					$_SESSION['LEVEL'] = $level;
					?><script type="text/javascript"> loginSuccess();</script><?php
				
			}
			else
			{
				$_SESSION["Login"] = "NO";
				?><script type="text/javascript"> loginFail();</script><?php
			}
		}
		else
		{		
	?>
	<body id="top">
	
		<div class="wrapper row0">
		  <div id="topbar" class="hoc clear"> 
		    <!-- ################################################################################################ -->
		    <div class="fl_left">
		      <ul>
		        <li><i class="fa fa-phone"></i> +6 03 8926 7446</li>
		        <li><i class="fa fa-envelope-o"></i> contact@genomemalaysia.gov.my</li>
		      </ul>
		    </div>
			<div class="fl_right">
		      <ul>
		        <li><?php echo $_SESSION['USER'] ?></li>
		      </ul>
		    </div>
		    <!-- ################################################################################################ -->
		  </div>
		</div>
		<div class="wrapper row1">
		  <header id="header" class="hoc clear"> 
		    <!-- ################################################################################################ -->
		    <div id="logo" class="fl_left">	
			<h1><a href="http://www.mgi-nibm.my"><img  src="./images/logo-genom.png" style="max-width:200px; min-width:150px; max-height:20%; display:inline-block;"></a></h1>
		    </div>
		    <div id="mainav2" class="fl_right">
		      <ul class="clear">
		        <li><a href="home.php">Home</a></li>
				<!--<li><a class="drop" href="#">List</a>
					  <ul>
						<li><a href="php/genome.php">Genome</a></li>
						<li><a href="php/viewScaffold.php">Scaffold</a></li>
						<li><a href="php/genelist.php">Gene</a></li>
						<li><a href="php/viewrRNAlist.php">rRNA</a></li>
						<li><a href="php/viewtRNAlist.php">tRNA</a></li>
					  </ul>
				</li> -->
				<li><?php
						if ($_SESSION["USER"] != null)
						{
					?>
						<a href="./php/logout.php">Logout</a>
					<?php
							}
						else {
					?>
						<a nohref onclick="document.getElementsByClassName('loginmodal')[0].style.display='block'" style="cursor:pointer">Login</a>			
					<?php
							}
					?>
				</li>		      
				<li>
				<?php
					if($_SESSION["USER"]==null){
				?>
						 <a nohref onclick="document.getElementsByClassName('registermodal')[0].style.display='block'" style="cursor:pointer">Register</a>
				<?php
					}
				?>
				</li>
			  </ul>
		    </div>
		    <!-- ################################################################################################ -->
		  </header>
		</div>
		
		<div class="bgded overlay" style="background-image:url('../images/demo/backgrounds/01.png');">
		  <div id="pageintro" class="hoc clear"> 
		    <!-- ################################################################################################ -->
		    <div class="flexslider basicslider">
		      <ul class="slides">
		        <li>
		          <article>
		            <h3 class="heading">Entero Database</h3>
		            <p class="font-x1 uppercase bold">Database of Bacteria</p>
		            <!-- <p >Select Data to Search</p> -->
		              <ul class="nospace inline pushright">
				<?php 
					if($_SESSION["USER"]==null){
				?>				
				<a nohref onclick="document.getElementsByClassName('loginmodal')[0].style.display='block'" class="btn inverse">Genome</a>
				<?php				
					}
					else{
				?>
		                <li><a class="btn inverse" href="./php/dashboard/entero.php">Genome</a></li>
				<?php	}	?>
		              </ul>
		          </article>
		        </li>
		       </ul>
		    </div>
		    <!-- ################################################################################################ -->
		  </div>
		</div>
		<div class="wrapper row5">
		  <div id="copyright" class="hoc clear"> 
		    <!-- ################################################################################################ -->
		    <center><p>Copyright &copy;  <script>document.write(new Date().getFullYear())</script> <a href="http://www.mgi-nibm.my" target="_blank">Malaysia Genome Institute</a></p></center>
		    <!-- ################################################################################################ -->
		  </div>
		</div>
		<script src="./layout/scripts/jquery.min.js"></script>
		<script src="./layout/scripts/jquery.backtotop.js"></script>
		<script src="./layout/scripts/jquery.mobilemenu.js"></script>
		<script src="./layout/scripts/jquery.flexslider-min.js"></script>
	</body>
      <!--   Core JS Files   -->
        <script src="./php/dashboard/assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
        <script src="./php/dashboard/assets/js/bootstrap.min.js" type="text/javascript"></script>
        <!--  Charts Plugin -->
        <script src="./php/dashboard/assets/js/chartist.min.js"></script>
        <!--  Notifications Plugin    -->
        <script src="./php/dashboard/assets/js/bootstrap-notify.js"></script>
        <!--  Google Maps Plugin    -->
        <script type="./php/dashboard/text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
        <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
        <script src="./php/dashboard/assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

		<?php } ?>
</html>
<div class="loginmodal" id="login_modal">
  <form class="loginmodal-content animate" method="post" action="home.php" /> 
    <div class="imgcontainer">
      <span onclick="document.getElementsByClassName('loginmodal')[0].style.display='none'" class="close" title="Close PopUp">&times;</span>
	  <img src="./images/bacteria.jpg" alt="Avatar" class="avatar">
      <h1 style="text-align:center">Login</h1>
    </div>
    <div class="logincontainer">
	<p class="username">Username: <input type="text" name="username" id="username" /></p>
	<p class="password">Password: <input type="password" name="password" id="password" /></p>        
    </div>
    	<button class="loginbutton" type="submit">Login</button>
    <div>
        <input type="checkbox" style="float-left; display:inline;">Remember me </input>
        <a href="#" style="float:right; display:inline;">Forgot Password ?</a>
    </div>
	<script> loginPopClose();</script>
  </form>
  
</div>

<div class="registermodal" id="register_modal">
  <form class="registermodal-content animate" method="post" action="./php/registerAction.php" onsubmit="return registerValidate();">
    <div class="imgcontainer">
      <span onclick="document.getElementsByClassName('registermodal')[0].style.display='none'" class="close" title="Close PopUp">&times;</span>
          <img src="./images/bacteria.jpg" alt="Avatar" class="avatar">
      <h1 style="text-align:center">Register</h1>
    </div>
    <div class="registercontainer">
        <p class="username">Username: <input type="text" name="username" id="rusername" required/></p>
        <p class="password">Password: <input type="password" name="password" id="rpassword" required/></p>
      	<p class="password">Password Confirmed: <input type="password" name="password" id="rpassword2" required/></p>
	<p class="email">Email: <input type="email" name="email" id="remail" required></p>
	<button class="registerbutton" type="submit">Register</button>
      <!-- <input type="checkbox" style="margin:26px 30px;"> Remember me
      <a href="#" style="text-decoration:none; float:right; margin-right:34px; margin-top:26px;">Forgot Password ?</a> -->
          <script> registerPopClose();</script>
    </div>
  </form>
</div>
