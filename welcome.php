<?php
ini_set('display_errors', 1);
session_start();

//if(!isset($_SESSION["userid"]) || $_SESSION["userid"] !== true){
//	header("location: login.php");
//	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Sign Up</title>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>Hello, <strong><?php echo $_SESSION["name"]; ?></strong>. Welcome to demo site.</h1>
				</div>
				<p>
					<a href="logout.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Log out</a>
				</p>
			</div>
		</div>		
	</body>
</html>	
				
				
				
				
				