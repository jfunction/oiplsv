<?php
require_once('classes/DB.class.php');
require_once('classes/User.class.php');
require_once('classes/UserTools.class.php');
require_once('FirePHPCore/fb.php');
//connect to the database
$db = new DB();
$db->connect();

//initialize UserTools object
$userTools = new UserTools();

//start the session
session_start();

//refresh session variables if logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==1) {
	$user = unserialize($_SESSION['user']);
	fb($user,'user from REFRESH!!!');
	fb($user->user_id,'users id');
	$u = $userTools->getUser($user->user_id);
	$_SESSION['user'] = serialize($u);
}
?>
