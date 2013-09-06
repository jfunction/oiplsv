<?php 
require_once 'includes/global.inc.php';  

$head = "<title>Register</title>";
$head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
$html="";
//if POST request from register page
if (array_key_exists('is_submitted', $_POST)) {
	//get the data out of the form
	$username = $_POST['username'];
	$password = $_POST['password'];
	if ($userTools->checkUsernameExists($username)) {
		//return user exists page, with optional back button
		$html.="<h3>Error:</h3><br>\n";
		$html.="We're sorry, but '$username' is already taken.<br>\n";
		$html.="Please hit back or click <a href=\"/CS3003S/OIPLSV/register.php\">here</a> to try again.";
	}else{
		//create user
		$user = new User($_POST);//The constructor only grabs the user-relevant data from POST
		$user->save(true);//newUser=true
		//now login
		$userTools->login($username, $password);
		//send to index page.
		$html = "Success!!! You will be redirected shortly.";
		header("Location: /CS3003S/OIPLSV/index.php?registered=True");//send a redirect
	}
}else{
	$html.="<h3>Registration:</h3><br>\n";
	$html.="<form action=\"\" method=\"post\">\n";
	$html.="<input type=\"hidden\" name=\"is_submitted\" value=\"1\" /><br>\n";//POST variable forced to contain this
	$html.="Name: <input type=\"text\" name=\"username\" /><br />\n";//TODO: ajax to test username
	$html.="Password: <input type=\"password\" name=\"password\" /><br />\n";//TODO: test password strength
	$html.="<input type=\"submit\">\n";
	$html.="</form>\n";
}
?>

<!DOCTYPE html>
<html>
<head><?php echo $head; ?></head>
<body>
<?php echo $html ?>
</body>
</html>
