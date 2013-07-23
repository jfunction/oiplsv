<?php
//UserTools.class.php

require_once 'User.class.php';
require_once 'DB.class.php';

class UserTools {
	
	//auth returns the user if succesfull, false otherwise.
	public function auth($username,$password) {
		$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$result = mysql_query($sql);
		return mysql_num_rows($result) == 1 ? mysql_fetch_assoc($result) : false;//true if theres a user with this username/password
	}

	//Log the user in. First checks to see if the 
	//username and password match a row in the database.
	//If it is successful, set the session variables
	//and store the user object within.
	public function login($username, $password) {
		//~ $password = md5($password);//formerly hashedPassword - J
		//~ $result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
		$user = $this->auth($username,$password);
		if((bool)$user){
			//~ echo "serializing user";
			$_SESSION["user"] = serialize(new User($user));
			$_SESSION["login_time"] = time();
			$_SESSION["logged_in"] = 1;
		}
		return $user ? true : false;
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
		$result = mysql_query("select * from users where username='$username'");
		$numRows = mysql_num_rows($result);
    	return ($numRows == 0 ? false : true);
	}
	
	//get a user
	//returns a User object. Takes the users id as an input
	public function get($user_id) {
		$db = new DB();
		$result = $db->select('users', "user_id = '$user_id'");
		
		return new User($result);
	}
	
}

?>
