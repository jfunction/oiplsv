<?php
	require_once 'includes/global.inc.php';
	$MAX_FILE_SIZE = 20*1000*1000;//20MB
	$head = "<title>Upload PDF</title>";
	$head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
	$html="";
	if (isset($_FILES['userfile']) && isset($_SESSION['user'])){//uploaded file and "logged in"?
		$user = unserialize($_SESSION['user']);

		$target_path = "/var/www/CS3003S/OIPLSV/pdf/"; //<-- where to save the PDF?
		$name = basename( $_FILES['userfile']['name']);
		$target_path = $target_path . $name;
		if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path)) {
			$db=new DB();
			$db->connect();
			//ghostscript here
			/*
			 * One can increase the -r200x200 param to increase quality/filesize.
			 * 
			 * For Windows change "gs" to "gswin32c" or similar.
			 * 
			 * Also, the sOutputFile should probably be changed to point to your projects img folder.
			 * this should further be added to the slides.php file so it pulls it from the right place.
			 * 
			 * The ghostscript program and info on these params can be found here at http://www.ghostscript.com/
			 * */
			$img_folder='/var/www/CS3003S/OIPLSV/img/';
			$cmd="gs -dNOPAUSE -r200x200 -sDEVICE=jpeg -sOutputFile=\"".$img_folder.str_replace(".pdf","",$name)."_%d.jpg\" -dJPEGQ=100 -q \"$target_path\" -c quit";

			$output = shell_exec($cmd);
			$data=array(
				'user_id' => $user->user_id,
				'title' => str_replace(".pdf","",$name), 
				'date_uploaded' => 'CURRENT_TIMESTAMP'
			);
			$pdfdoc_id = $db->insert($data,'PDFDocument');
			$html .= "<p>The file " . $name . " has been uploaded! Click <a href=\"slides.php?pdfdocument_id=".$pdfdoc_id."&pdfslide=1\">here</a> to view it.</p>";//header()
			//Now add all the slides we have just accumulated:
			$i=1;
			while(file_exists($img_folder.str_replace(".pdf","",$name)."_".$i.".jpg")){
				//add a slide
				$data=array(
					'pdfdocument_id' => $pdfdoc_id,
					'pdfslide_order' => $i
				);
				$pdfslide_id = $db->insert($data,'PDFSlide');
				$i++;
			}
		} else{
			$html .= "There was an error uploading the file, please try again!";
		}

	}elseif (isset($_SESSION['user'])){
		$user = unserialize($_SESSION['user']);
		if ("admin"==$user->role || "lecturer"==$user->role){ #only allow these people to upload pdfs
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
		$html.="<a href=\"login.php\">Login</a>";
		$html.=" or <a href=\"register.php\">Register</a><br>\n";
		$html.="<img src=\"img/home.png\">\n";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<?php echo $head; ?>
	</head>
	<body>
		<?php 
		include 'includes/header.inc.php';
		echo $html; ?>
	</body>
</html>
