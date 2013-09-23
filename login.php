<?php 
require_once 'includes/global.inc.php';  
require_once('FirePHPCore/fb.php');
$head = "<title>Login</title>";
$head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
$html = "";
$error = "";
if(isset($_POST['is_submitted'])) {
	//now get the data out of the form
	$username = $_POST['username'];
	$password = $_POST['password'];
	//now test these against the database
	$userTools = new UserTools();
	$user = $userTools->login($username,$password);
	if($user){ //true if login succeeded.
		$error.="<p>Success, you are now logged in.</p>\n";
		$error.="<p>If this page does not redirect you, click <a href=\"index.php\">here</a>.</p>";
		header("Location: index.php");//redirect
	}else{ //will catch this in jQuery later, but need serverside checking either way
		$error = "<p>Error - that username/password combination is invalid.</p>\n";
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<?php echo $head; ?>
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
