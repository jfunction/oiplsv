<?php
	require_once 'includes/global.inc.php';
	$html="";
	if (isset($_SESSION['user'])){
		$user = unserialize($_SESSION['user']);
		$html .="<a href=\"/CS3003S/OIPLSV/logout.php\">Logout</a>.";
		$html .="<p>You are logged in as ".$user->username."</p>";
		$html .="<p>Here's the list of all lectures:</p>";
		$html .="<ul>";//todo: get these from a db or from disk... db is better
		$db = new DB();
		$pages = $db->select("pdf","");
		//~ echo "<pre>";
		//~ print_r($pages);
		//~ echo "</pre>";
		foreach($pages as $key => $val){
			//~ echo "<pre>";
			//~ echo "$key -> $val";
			//~ echo "</pre>";
			if ($key == 'title'){
					$html .="<li><a href=\"slides.php?pdf=".$val."\">".$val."</a></li>";
			}
		}
		$html .="</ul><br>\n";
		if ("admin"==$user->role || "lecturer"==$user->role){
			$html .="<a href=\"upload.php\">Upload PDF</a>";
		}
	}else{
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
