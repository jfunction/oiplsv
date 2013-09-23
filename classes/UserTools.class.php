<?php
//UserTools.class.php

require_once ('User.class.php');
require_once ('DB.class.php');
require_once('FirePHPCore/fb.php');
class UserTools {

	//auth returns the user if succesfull, false otherwise.
	public function auth($username,$password) {
		$db = new DB();
		$result = $db->select('User', "username = '$username' AND password = '$password'");
		if ($result) {
			return $result[0];
		}
		else return false;
	}

	//Log the user in. First checks to see if the 
	//username and password match a row in the database.
	//If it is successful, set the session variables
	//and store the user object within. Return $user
	public function login($username, $password) {
		//~ $password = md5($password);//formerly hashedPassword - J
		//~ $result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
		$user = $this->auth($username,$password);
		if((bool)$user){
			$t = new User($user);
			$s = serialize($t);
			$_SESSION['user'] = $s;
			$_SESSION['login_time'] = time();
			$_SESSION['logged_in'] = 1;
		}
		return $t ? $t : false;
	}
	
	//Log the user out. Destroy the session variables.
	public function logout() {
		unset($_SESSION['user']);
		unset($_SESSION['login_time']);//TODO: use something like this to autologout user.
		unset($_SESSION['logged_in']);
		session_destroy();
	}

	//Check to see if a username exists.
	//This is called during registration to make sure all user names are unique.
	public function checkUsernameExists($username) {
		$result = mysql_query("select * from User where username='$username'");
		$numRows = mysql_num_rows($result);
    	return ($numRows == 0 ? false : true);
	}
	
	//get a user
	//returns a (possibly empty) User object. Takes the users id as an input
	public function getUser($user_id) {
		$db = new DB();
		$result = $db->select('User', "user_id = '$user_id'");
		if ($result){//grab the 1st returned result
			$result = $result[0];
		}
		$options['user_id']=$user_id;
		$options['username']=(isset($result['username'])) ? $result['username'] : "";
		$options['password']=(isset($result['password'])) ? $result['password'] : "";
		$options['firstname']=(isset($result['firstname'])) ? $result['firstname'] : "";
		$options['lastname']=(isset($result['lastname'])) ? $result['lastname'] : "";
		$options['role']=(isset($result['role'])) ? $result['role'] : "";
		$options['email']=(isset($result['email'])) ? $result['email'] : "";
		return new User($options);
	}
	
}

?>
