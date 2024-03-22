<?php

require_once "config.php";
require_once "session.php";
require_once "simple_html_dom.php";
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$html = str_get_html('<div id="image" name="captc" class="form-group" selectable="False"></div>');
	$text = $html->find('div[id=image]', 0)->innertext;
	
	if(empty($email)){
		$error .='<p class="error">Please enter email.</p>';
	}
	
	if(empty($password)){
		$error .='<p class="error">Please enter your password.</p>';
	}
	
	if(empty($error)) {
		
		if ($_POST['submit1'] == $text) {
			
			if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
				
				$query->bind_param('s', $email);
				$query->execute();
				$row = $query->fetch();
				if($row) {
					
					if (password_verify($password, password_hash($row['password'], PASSWORD_BCRYPT))) {
						$_SESSION["userid"] = $row['userid'];
						//Redirect the user to welcome page
						header("location: welcome.php");
						exit;
					}else{
						echo '<p class="error">Incorrect password</p>';
						}
				}else{
					echo '<p class="error">The email address is not valid.</p>';
				}
			}
			$query->close();
		}
	}
	mysqli_close($db);
}

?>

<script>
let captcha;
function generate() {

    document.getElementById("cap").value = "";

    captcha = document.getElementById("image");
    let uniquechar = "";

    const randomchar =
"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 1; i < 5; i++) {
        uniquechar += randomchar.charAt(
            Math.random() * randomchar.length)
    }

    captcha.innerHTML = uniquechar;
}

function printmsg() {
    const usr_input = document
        .getElementById("cap").value;
    if (usr_input == captcha.innerHTML) {
		
        let s = document.getElementById("key")
            .innerHTML = "Matched";
        generate();
    }
    else {
        let s = document.getElementById("key")
            .innerHTML = "not Matched";
        generate();
    }
}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"integrity= "sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
</head>
 
<body onload="generate()">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<h2>Login</h2>
			<p>Please fill in your email and password.</p>
			<form action="" method="post">
				<div class="form-group">
					<label>Email address</label>
					<input type="email" name="email" class="form-control" required/>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" class="form-control" required/>
				</div>
				<div id="user-input" class="form-group">
					<input type="text" name="submit1" id="cap" placeholder="Captcha code" />
				</div>
 
				<div class="form-group" onclick="generate()">
					<i class="fas fa-sync">
					</i>
				</div>
 
				<div id="image" name="captc" class="form-group" selectable="False"></div>
								
				<div class="form-group">
					<input type="submit" name="submit" onclick="printmsg()" value="Submit">
				</div>
				
				<p id="key"></p>
 
				<p> Don't have an account?<a href="register.php">Register here</a>.</p>
				</br>
				<p> Forgotten password?<a href="forgotten.php">Click here</a>.</p>
			</form>
		</div>
	</div>
	</div>
</body>
</html>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	