function validate(){

	var userName = document.getElementById("username");
	var password = document.getElementById("password");

	if (userName.value == "") {
		alert('Please enter your username.');
		userName.focus();
		return false;
	}

	if (password.value == "") {
		alert('Please enter your password');
		password.focus();
		return false;
	}
	return true;
}
function registerValidate(){
	var pwd1 = document.getElementById("rpassword");
	var pwd2 = document.getElementById("rpassword2");
	var username= document.getElementById("rusername");
	if (pwd1.value!="" && pwd1.value == pwd2.value){
		if(pwd1.value.length < 6) {
        		alert("Error: Password must contain at least six characters!");
	        	pwd1.focus();
      		  	return false;
      		}
     		if(pwd1.value == username.value) {
        		alert("Error: Password must be different from Username!");
       			pwd1.focus();
        		return false;
      		}
      		re = /[0-9]/;
      		if(!re.test(pwd1.value)) {
        		alert("Error: password must contain at least one number (0-9)!");
        		pwd1.focus();
        		return false;
      		}
      		re = /[a-z]/;
      		if(!re.test(pwd1.value)) {
        		alert("Error: password must contain at least one lowercase letter (a-z)!");
        		pwd1.focus();
        		return false;
      		}
      		re = /[A-Z]/;
      		if(!re.test(pwd1.value)) {
        		alert("Error: password must contain at least one uppercase letter (A-Z)!");
        		pwd1.focus();
        		return false;
      		}
		return true;
    	}
	else {
      		alert("Error: Please check that you've entered and confirmed your password!");
      		pwd1.focus();
      		return false;
    	}
}


function loginPopClose(){
	var loginmodal = document.getElementById('login_modal');
	window.onclick = function(event) {
		if (event.target == loginmodal) {
			loginmodal.style.display = "none";
		}
	}
}

function registerPopClose(){
        var registermodal = document.getElementById('register_modal');
        window.onclick = function(event) {
                if (event.target == registermodal) {
                        registermodal.style.display = "none";
                }
        }
}



function chartPopClose(){
	var modal = document.getElementById('chart-wrapper');
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
}

function loginSuccess(){
	alert('Login Successful');
	window.location.replace("./home.php");
}

function loginFail(){
	alert('Username or Password incorrect');
	window.location.replace("./home.php");
}
function logout(){
	alert('Logout Successful');
	window.location.replace("../home.php");
}
function failBLAST(){
	alert('No result found, please try again with different database or BLAST method');
	reload();
}

/*function uploadFailed(){
	alert("Failed to upload due to unknown error.Please try again");
	reload();
}*/

function plotterUploadSuccess(){
	alert("All Plotter diagram upload successfully");
	window.location.replace("./genome.php");
}

function deleteValidate(genomeID){
	var confirmation = prompt("Are you sure to delete genome with ID = "+genomeID+"? Enter 'YES' to proceed");
	if (confirmation=="YES")
	{
		document.getElementById("deleteform"+genomeID).submit();
	}
}

function deleteSuccess(){
	alert("Delete Success");
	window.location.replace("./dashboard/genome.php");
}
function registerSuccess(){
        alert('Register Success');
        window.location.replace("../home.php");
}
function usernameExists(){
        alert('Username already exists');
        window.location.replace("../home.php");
}
function emailExists(){
	alert('Email already exists');
	window.location.replace("../home.php");
}


// var emailID = document.myForm.EMail.value;
// 	atpos = emailID.indexOf("@");
// 	dotpos = emailID.lastIndexOf(".");
	
// 	if (atpos < 1 || ( dotpos - atpos < 2 )){
// 	alert("Please enter correct email ID")
// 	document.myForm.EMail.focus() ;
// 	return false;
// 	}

