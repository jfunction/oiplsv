<?php 
require_once 'DB.class.php'; 

class User {
	public $user_id;
	public $username;
	public $password;
	public $email;
	public $role;

	function __construct($options){
		$this->user_id = (isset($options['user_id'])) ? $options['user_id'] : "";
		$this->username = (isset($options['username'])) ? $options['username'] : "";
		$this->password = (isset($options['password'])) ? $options['password'] : "";
		$this->email = (isset($options['email'])) ? $options['email'] : "";
		$this->role = (isset($options['role'])) ? $options['role'] : "";
	}
	
	//returns boolean - whether username/password is valid.
	
	public function exists($username){
		$result = mysql_query("$SELECT * FROM users WHERE username = '$username'");
		return mysql_num_rows($result) == 1;
	}
	
	public function save($isNewUser = false) {
		//create a new database object.
		$db = new DB();
		
		//if the user is already registered and we're
		//just updating their info.
		if(!$isNewUser) {
			//set the data array
			$data = array(
				"username" => "'$this->username'",
				"password" => "'$this->password'",
				"email" => "'$this->email'",
				"role" => "'$this->role'"
			);
			
			//update the row in the database
			$db->update($data, 'users', 'user_id = '.$this->user_id);
		}else {
		//if the user is being registered for the first time.
			$data = array(
				"username" => "'$this->username'",
				"password" => "'$this->password'",
				"email" => "'$this->email'",
				"role" => "'student'"//assume user is a student for now
			);
			
			$this->id = $db->insert($data, 'users');
		}
		return true;
	}
}

?>
