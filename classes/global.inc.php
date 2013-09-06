<?php
require_once 'classes/DB.class.php';
require_once 'classes/User.class.php';
require_once 'classes/UserTools.class.php';

//connect to the database
$db = new DB();
$db->connect();

//initialize UserTools object
$userTools = new UserTools();

//start the session
function _log($str){
	$log_filename = '/var/www/CS3003S/OIPLSV/log.txt';
	file_put_contents( $log_filename , $str , FILE_APPEND );
}
_log("\n---global.inc.php---\n");

_log("\nsession_start() called\n");
session_start();

//refresh session variables if logged in
if(isset($_SESSION['logged_in'])) {
	_log("\nuser var being set to\n");
	_log($_SESSION['user']);
	$user = unserialize($_SESSION['user']);
	_log("\n_SESSION[user] being set to user with id'$user->id'\n");
	_log($_SESSION['user']);
	$_SESSION['user'] = serialize($userTools->get($user->id));
}
?>
