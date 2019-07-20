<?php
	     // login to MySQL Server from PHP, change username and password to your own 
	     $conn = mysqli_connect("localhost","root","","Entero");

	     // If login failed, terminate the page (using function 'die')
	     if (!$conn) die(mysqli_connect_error() );
?>
