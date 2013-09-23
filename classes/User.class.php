<?php 
require_once 'DB.class.php'; 
require_once('FirePHPCore/fb.php');

class User {
	public $user_id;
	public $username;
	public $password;
	public $email;
	public $role;
	public $firstname;
	public $lastname;

	function __construct($options){
		$this->user_id = (isset($options['user_id'])) ? $options['user_id'] : "";
		$this->username = (isset($options['username'])) ? $options['username'] : "";
		$this->password = (isset($options['password'])) ? $options['password'] : "";
		$this->email = (isset($options['email'])) ? $options['email'] : "";
		$this->role = (isset($options['role'])) ? $options['role'] : "";
		$this->firstname = (isset($options['firstname'])) ? $options['firstname'] : "";
		$this->lastname = (isset($options['lastname'])) ? $options['lastname'] : "";
	}
	
	//returns boolean - whether username/password is valid.
	public function exists($username){
		$result = mysql_query("$SELECT * FROM User WHERE username = '$username'");
		return mysql_num_rows($result) == 1;
	}
	
	public function save() {
		//create a new database object.
		$db = new DB();
		if (!$this->user_id){ //if theres no user id then get one from the username
			$tmp_user = $db->select('User', "username = '$this->username'");
			fb($tmp_user,'Temp User');
			if ($tmp_user){
				$this->user_id = $tmp_user['user_id'];
				fb($tmp_user,'isNewUser = false, found id',$this->user_id);
				$isNewUser=false;
			}else{
				fb($tmp_user,'isNewUser = true, no user with the username, and user_id nonexistant');
				$isNewUser=true;
			}
		}else{ //we already have a user_id, so check that it's valid
			fb($this->user_id,'OUR USER ID');
			$isNewUser = $db->select('User', "user_id = '$this->user_id'") ? false : true;
			fb($isNewUser,'=isNewUser since user_id was ',$this->user_id);
		}
		//Update User
		if (!$this->role){
			$this->role='student';
		}
		$data = array(
			"user_id" => "$this->user_id",
			"username" => "$this->username",
			"password" => "$this->password",
			"firstname" => "$this->firstname",
			"lastname" => "$this->lastname",
			"role" => "$this->role",
			"email" => "$this->email"
		);
		if(!$isNewUser) {
			fb('updating',$data,'into User table.');
			//update the row in the database
			$db->update($data, 'User', 'user_id = '.$this->user_id);
		}else {
			$this->user_id = $db->insert($data, 'User');
		}
		return true;
	}
}
?>
