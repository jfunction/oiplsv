<?php
	require_once 'includes/global.inc.php'; 
	$head="<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
	
	$html="";
	if (isset($_SESSION['user']) && isset($_SESSION['logged_in'])){
		$user = unserialize($_SESSION["user"]);
		
		$html .="<p>Here's the list of all lectures:</p>";
		$html .="<ul>";//todo: get these from a db or from disk... db is better
		$db = new DB();
		$pdfs = $db->select("pdf","");//everything from pdf table
		//list them out
		foreach($pdfs as $i => $pdf){
			$html .="<li><a href=\"slides.php?pdf=".$pdf["title"]."\">".$pdf["title"]."</a></li>";
		}
		$html .="</ul><br>\n";
		//only certain users can upload
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
<head><?php echo $head; ?></head>
<body>
	<?php 
	include 'includes/header.inc.php';
	echo $html ?>
</body>
</html>
