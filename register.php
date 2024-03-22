<?php

require_once "config.php";
require_once "session.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
	
	$fullname = trim($_POST['name']);
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$password_hash = password_hash($password, PASSWORD_BCRYPT);
	
	if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
		$error = '';
	$query->bind_param('s', $email);
	$query->execute();
	
	$query->store_result();
		if($query->num_rows > 0) {
			$error .= '<p class="error">The email address is already registered!</p>';
		}else{
			if (strlen($password ) < 6) {
				$error .= '<p class="error">Password must have at least 6 characters.</p>';
			}
			if (empty($confirm_password)) {
				$error .= '<p class="error">Please confirm password.</p>';
			} else {
				if (empty($error) && ($password != $confirm_password)) {
					$error .= '<p class="error">Password did not match.</p>';
				}
			}
			if (empty($error) ) {
				$insertQuery = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?);");
				$insertQuery->bind_param("sss", $fullname, $email, $password_hash);
				$result = $insertQuery->execute();
				if ($result) {
					$error .= '<p class="success">Your registration was successful!</p>';
				} else {
					$error .= '<p class="error">Something went wrong!</p>';
				}
			}
		}
	}
	$query->close();
	mysqli_close($db);
}
?>

<script>
let captcha;
function generate() {
    document.getElementById("cap").value = "";
    captcha = document.getElementById("image");
    let uniquechar = "";
    const randomchar = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

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
		alert("Hello\nHow are you?");
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
		<title>Register</title>
		 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"integrity= "sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
	</head>
	<body onload="generate()">
		<div class="container">
			<div class="row">
				<div class="col-md-auto">
					<h2>Register</h2>
					<p>Please fill this form to create an account.</p>
					
					<form action="" method="post">
						<div class="form-group">
							<label>Full Name</label>
							<input type="text" name="name" minlength="3" maxlength="200" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" name="email" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" id="pass" name="password" minlength="6" pattern="[a-zA-Z0-9\s]+" maxlength="36" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Confirm Password</label>
							<input type="password" id="pass2" name="confirm_password" class="form-control" required>
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
							<input type="submit" name="submit" class="btn btn-primary" value="Submit">
						</div>
						<p>Already have an account? <a href="login.php">Login here</a>.</p>
					</form>
				</div>
			</div>			
		</div>				
	</body>
</html>	