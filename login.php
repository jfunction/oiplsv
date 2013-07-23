<?php 
require_once 'includes/global.inc.php';  

$html="";
$error="";

if(isset($_POST['is_submitted'])) {
	//now get the data out of the form
	$username = $_POST['username'];
	$password = $_POST['password'];
	//now test these against the database
	$userTools = new UserTools();
	if($userTools->login($username,$password)){
		$error.="<p>Success, you are now logged in.</p>\n";
		$error.="<p>If this page does not redirect you, click <a href=\"\">here</a>.</p>";
		echo $error;
		header("Location: index.php");
	}else{
		$error = "<p>Error - that username/password combination is invalid.</p>\n";
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>  
	</head>
	<body>		<?php if ("" != $error){echo $error;} ?>
		<h3>Login:</h3>
		<form action="" method="post">
			<input type="hidden" name="is_submitted" value="1" /><br>
			Name: <input type="text" name="username" /><br />
			Password: <input type="password" name="password" /><br />
			<input type="submit">
		</form>
	</body>
</html>
