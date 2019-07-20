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
		<!-- <script type="text/javascript" src="./php/dashboard/assets/js/java.js"></script> -->
		<script src='https://code.jquery.com/jquery-3.1.0.min.js'></script>
		<script type="text/javascript" src="./php/dashboard/assets/js/userform.js"></script>
		<!-- Old form <link href="./css/popup.css" rel="stylesheet" type="text/css" media="all"> -->
		<link href="./css/userform.css" rel="stylesheet" type="text/css" media="all"> 
	</head>
	<?php 
		if (isset($_POST['username']))
		{
			$myusername=$_POST['username'];
			$mypassword=$_POST['password'];

			$sql="SELECT * FROM user WHERE username='$myusername' and password='$mypassword'";

			$result=mysqli_query($conn,$sql);
			$rows=mysqli_fetch_array($result);
			$user_name=$rows['username'];
			$userID = $rows['userID'];
					
			// mysql_num_row is counting table row
			$count=mysqli_num_rows($result);
			// If result matched $myusername and $mypassword, table row must be 1 row
				 
			if($count==1){		
				
					$_SESSION["Login"] = "YES";
				// Add user information to the session (global session variables)
					$_SESSION['USER'] = $user_name;
					$_SESSION['ID'] = $userID;
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
		    <nav id="mainav" class="fl_right">
		      <ul>
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
						<a class="cd-signin" href="#0">Sign in</a>
						<!--<a nohref onclick="document.getElementsByClassName('loginmodal')[0].style.display='block'" style="cursor:pointer">Login</a> -->			
					<?php
							}
					?>
				</li>		      
				<li>
				<?php
					if($_SESSION["USER"]==null){
				?>
						<a class="cd-signup" href="#0">Sign Up</a>
						 <!--<a nohref onclick="document.getElementsByClassName('registermodal')[0].style.display='block'" style="cursor:pointer">Register</a>-->
				<?php
					}
				?>
				</li>
			  </ul>
		    </nav>
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
		                <li><a class="btn inverse" href="./php/dashboard/entero.php">Genome</a></li>
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
		<?php } ?>
</html>
<!-- OLD login and register form 
<div class="loginmodal" id="modal-wrapper">
  <form class="loginmodal-content animate" method="post" action="home.php" onsubmit="return validate();"/> 
    <div class="imgcontainer">
      <span onclick="document.getElementsByClassName('loginmodal')[0].style.display='none'" class="close" title="Close PopUp">&times;</span>
	  <img src="./images/bacteria.jpg" alt="Avatar" class="avatar">
      <h1 style="text-align:center">Login</h1>
    </div>
    <div class="logincontainer">
	  <p class="username">Username: <input type="text" name="username" id="username" /></p>
	  <p class="password">Password: <input type="password" name="password" id="password" /></p>        
      <button class="loginbutton" type="submit">Login</button> -->
      <!-- <input type="checkbox" style="margin:26px 30px;"> Remember me      
      <a href="#" style="text-decoration:none; float:right; margin-right:34px; margin-top:26px;">Forgot Password ?</a> -->
<!--	  <script> loginPopClose();</script>
    </div>
    
  </form>
  
</div>

<div class="registermodal" id=modal-wrapper>
  <form class="registermodal-content animate" method="post" action="home.php" onsubmit="return validate();"/>
    <div class="imgcontainer">
      <span onclick="document.getElementsByClassName('registermodal')[0].style.display='none'" class="close" title="Close PopUp">&times;</span>
          <img src="./images/bacteria.jpg" alt="Avatar" class="avatar">
      <h1 style="text-align:center">Register</h1>
    </div>
    <div class="registercontainer">
        <p class="username">Username: <input type="text" name="username" id="username" required/></p>
        <p class="password">Password: <input type="password" name="password" id="password" required/></p>
      	<p class="confirmpassword">Confirm Password: <input type="password" name="confirmpassword" id="confirmpassword" required /></p>
	<p class="email">Email: <input type="email" name="email" id="email" required></p>
	<button class="registerbutton" type="submit">Register</button> -->
      <!-- <input type="checkbox" style="margin:26px 30px;"> Remember me
      <a href="#" style="text-decoration:none; float:right; margin-right:34px; margin-top:26px;">Forgot Password ?</a> -->
<!--          <script> loginPopClose();</script>
    </div>
  </form>  
</div> -->

<div class="cd-user-modal"> <!-- this is the entire modal form, including the background -->
	<div class="cd-user-modal-container"> <!-- this is the container wrapper -->
		<ul class="cd-switcher">
			<li><a href="#0">Sign in</a></li>
			<li><a href="#0">New account</a></li>
		</ul>
		<div id="cd-login"> <!-- log in form -->
			<form class="cd-form">
				<p class="fieldset">
					<label class="image-replace cd-email" for="signin-email">E-mail</label>
					<input class="full-width has-padding has-border" id="signin-email" type="email" placeholder="E-mail">
					<span class="cd-error-message">Error message here!</span>
				</p>
				<p class="fieldset">
					<label class="image-replace cd-password" for="signin-password">Password</label>
					<input class="full-width has-padding has-border" id="signin-password" type="text"  placeholder="Password">
					<a href="#0" class="hide-password">Hide</a>
					<span class="cd-error-message">Error message here!</span>
				</p>
				<p class="fieldset">
					<input type="checkbox" id="remember-me" checked>
					<label for="remember-me">Remember me</label>
				</p>
				<p class="fieldset">
					<input class="full-width" type="submit" value="Login">
				</p>
			</form>
			<p class="cd-form-bottom-message"><a href="#0">Forgot your password?</a></p>
			<!-- <a href="#0" class="cd-close-form">Close</a> -->
		</div> <!-- cd-login -->
		<div id="cd-signup"> <!-- sign up form -->
			<form class="cd-form">
				<p class="fieldset">
					<label class="image-replace cd-username" for="signup-username">Username</label>
					<input class="full-width has-padding has-border" id="signup-username" type="text" placeholder="Username">
					<span class="cd-error-message">Error message here!</span>
				</p>
				<p class="fieldset">
					<label class="image-replace cd-email" for="signup-email">E-mail</label>
					<input class="full-width has-padding has-border" id="signup-email" type="email" placeholder="E-mail">
					<span class="cd-error-message">Error message here!</span>
				</p>
				<p class="fieldset">
					<label class="image-replace cd-password" for="signup-password">Password</label>
					<input class="full-width has-padding has-border" id="signup-password" type="text"  placeholder="Password">
					<a href="#0" class="hide-password">Hide</a>
					<span class="cd-error-message">Error message here!</span>
				</p>
				<p class="fieldset">
					<input type="checkbox" id="accept-terms">
					<label for="accept-terms">I agree to the <a href="#0">Terms</a></label>
				</p>
				<p class="fieldset">
					<input class="full-width has-padding" type="submit" value="Create account">
				</p>
			</form>	<!-- <a href="#0" class="cd-close-form">Close</a> -->
		</div> <!-- cd-signup -->
		<div id="cd-reset-password"> <!-- reset password form -->
			<p class="cd-form-message">Lost your password? Please enter your email address. You will receive a link to create a new password.</p>
			<form class="cd-form">
				<p class="fieldset">
					<label class="image-replace cd-email" for="reset-email">E-mail</label>
					<input class="full-width has-padding has-border" id="reset-email" type="email" placeholder="E-mail">
					<span class="cd-error-message">Error message here!</span>
				</p>
				<p class="fieldset">
					<input class="full-width has-padding" type="submit" value="Reset password">
				</p>
			</form>
			<p class="cd-form-bottom-message"><a href="#0">Back to log-in</a></p>
		</div> <!-- cd-reset-password -->
		<a href="#0" class="cd-close-form">Close</a>
	</div> <!-- cd-user-modal-container -->
</div> <!-- cd-user-modal -->
