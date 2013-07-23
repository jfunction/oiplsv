<?php error_reporting(1);
	require_once 'includes/global.inc.php';
	$MAX_FILE_SIZE = 20*1000*1000;//20MB
	$html="";
	//~ echo "<pre>";
	//~ print_r($_FILES);
	//~ print_r($_POST);
	//~ echo "</pre>";
	if (isset($_FILES['userfile']) && isset($_SESSION['user'])){//uploaded file and "logged in"?
		//TODO: verify login
		$user = unserialize($_SESSION['user']);
		$html .="<a href=\"/CS3003S/OIPLSV/logout.php\">Logout</a>.";

		$target_path = "/var/www/CS3003S/OIPLSV/pdf/";
		$name = basename( $_FILES['userfile']['name']);
		$target_path = $target_path . $name;
		//~ echo "Name=".$name."<br>";
		//~ echo "Temp=".$_FILES['userfile']['tmp_name']."<br>";
		if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
			$db=new DB();
			$db->connect();
			//ghostscript here
			$cmd="gs -dNOPAUSE -sDEVICE=jpeg -sOutputFile=\"/var/www/CS3003S/OIPLSV/img/".str_replace(".pdf","",$name)."_%d.jpg\" -dJPEGQ=100 -q \"".$target_path."\" -c quit";
			//~ echo "{{{".$cmd."}}}";
			$output = shell_exec($cmd);
			//~ echo "---<br>".$output."<br>---";
			echo "<pre>";
			print_r($user);
			echo "</pre>";
			$html .= "<p>The file " . $name . " has been uploaded! Click <a href=\"slides?pdf=".str_replace(".pdf","",$name)."\">here</a> to view it.</p>";//header()
			$data=array('title' => str_replace(".pdf","",$name), 'uploader_id' => $user->user_id, 'upload_date' => 'CURRENT_TIMESTAMP');
			$page_id = $db->insert($data);
		} else{
			$html .= "There was an error uploading the file, please try again!";
		}

	}elseif (isset($_SESSION['user'])){
		$user = unserialize($_SESSION['user']);
		$html .="<a href=\"/CS3003S/OIPLSV/logout.php\">Logout</a>.";
		if ("admin"==$user->role || "lecturer"==$user->role){
			$html.="<h1>Upload a PDF</h1><br>\n";
			$html.="<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\"><br>\n";
			$html.="<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$MAX_FILE_SIZE."\" /><br>\n";
			$html.="<input type=\"file\" name=\"userfile\"><br>\n";
			$html.="<input type=\"submit\" value=\"Upload File\" /><br>\n";
			$html.="</form>";
		}else{
			$html.="<p>You are not a lecturer/admin, so are unable to view this page.</p>";
		}
	}else{
		$html.="<p>You are not logged in, so are unable to view this page.</p>";
		$html.="<a href=\"/CS3003S/OIPLSV/login.php\">Login</a>";
		$html.=" or <a href=\"/CS3003S/OIPLSV/register.php\">Register</a><br>\n";
		$html.="<img src=\"img/home.png\">\n";
	}

?>
<!DOCTYPE html>
<html>
<head></head>
<body>
	<?php echo $html ?>
</body>
</html>
