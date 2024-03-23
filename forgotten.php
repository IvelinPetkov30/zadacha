<?php

require_once "config.php";
require_once "session.php";

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, count((array)$alphabet)-1);
        $pass[$i] = $alphabet[$n];
    }
    return $pass;
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	$email = trim($_POST['email']);
	if(empty($email)){
		$error .='<p class="error">Please enter email.</p>';
	}
		
	if(empty($error)) {
		
		if ($_POST['submit1'] == $_SESSION['image']) {
			$newpass=(string)randomPassword();
			if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
				
				$query->bind_param('s', $email);
				$query->execute();
				$row = $query->fetch();
				if($row) {
					$headers  = 'MIME-Version: 1.0' . "\r\n"
					.'Content-type: text/html; charset=utf-8' . "\r\n"
					.'From: ' . 'site@email.com' . "\r\n";
					$query2 = $db->prepare("UPDATE users SET password='?' WHERE email=?");
					$stmt = $conn->prepare($query2);
					$stmt->bind_param('ss', $newpas, $email);
					
					
					if(mail($email, 'Forgotten Password', $newpass, $headers) && $stmt->execute())
					{
						echo '<p>Check your email for your new password</p>';
					} else {
						echo '<p>Operation failed</p>';
					}
				}else{
					$error .= '<p class="error">The email address is not valid.</p>';
				}
		}
		$query->close();
	}
	}
	mysqli_close($db);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"integrity= "sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
</head>
 
<body >
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<h2>Login</h2>
			<p>Please fill in your email</p>
			<form action="" method="post">
				<div class="form-group">
					<label>Email address</label>
					<input type="email" name="email" class="form-control" required/>
				</div>
				<div class="form-group">
					<input type="submit" name="submit" class="btn btn-primary" value="Submit">
				</div>
			</form>
		</div>
	</div>
	</div>
</body>
</html>
