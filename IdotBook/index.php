<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<p align="center" style="font-size:14px;">If you want to make an account, just enter a username and password and the account will be created!</p>
			<form action="LoginAuth.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<?php
					if(isset($_SESSION['loggedin'])){
						switch($_SESSION['loggedin']){
							case "falp":
								echo "<p>Incorrect password given!</p>";
							break;
							default:
								echo "";
							break;
						}
					} else {
						echo "";
					}
				?>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>