 <script type="text/javascript" src="./dashboard/assets/js/java.js"></script>
<?php 
	require("config.php");
	if (isset($_POST['username'])){
		$username=$_POST['username'];
		$pwd=$_POST['password'];
		$email=$_POST['email'];
		$checkExistsUsername="select * from user where username='$username' or email='$email'";
		$userNameResult=mysqli_query($conn,$checkExistsUsername)or die(mysqli_error($conn));
		$userName=mysqli_fetch_assoc($userNameResult);
		if($userName){
			if($userName['username']===$username){
				?><script>usernameExists();</script><?php	
			}
			if($userName['email']===$email){
				?><script>emailExists();</script><?php
			}
		}
		else{
			$password=md5($pwd);
			$insertQuery="insert into user(username,email,password) values('$username','$email','$password')";
			mysqli_query($conn,$insertQuery)or die(mysqli_error($conn));
			?><script>registerSuccess();</script><?php
		}
	}
?>
