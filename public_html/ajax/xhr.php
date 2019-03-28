<?php	
	//require_once $_SERVER["DOCUMENT_ROOT"]."/config.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/cloure_sdk.php";

	$topic = isset($_POST["topic"]) ? $_POST["topic"] : "";
	$response_decoded = "";
	
	$CloureSDK = new CloureSDK();
	if($topic=="change_language") 
		$_SESSION["lang"] = $_POST["lang"];
	else {
		$params = [];
		if($_POST){foreach ($_POST as $key => $value) {$params[$key]=$value;}}

		if(isset($_FILES["file"])){
			if ($_FILES["file"]["error"] == UPLOAD_ERR_OK){

				$img = $_FILES["file"]["tmp_name"];
				$img_name = $_FILES["file"]["name"];

				$imagen = isset($_POST["file_name"]) ? $_POST["file_name"] : null;
				$dir = $_SERVER["DOCUMENT_ROOT"]."/uploads/";
				if(!file_exists($dir)) @mkdir($dir);

				$path = $dir.$img_name;

				if(move_uploaded_file($img, $path)){
					$file = new \CurlFile($path);
					$params["file"] = $file;
				}else{
					throw new \Exception("Error Processing Request", 1);
				}
			}
		}
		
		if(isset($_FILES["files"])){
			$files = [];
			$c=0;
			foreach ($_FILES["files"]["error"] as $img => $error) {
				if ($error == UPLOAD_ERR_OK){
					$tmp_name = $_FILES["files"]["tmp_name"][$img];
					$name = basename($_FILES["files"]["name"][$img]);
					$dir = $_SERVER["DOCUMENT_ROOT"]."/uploads/";
					if(!file_exists($dir)) @mkdir($dir);

					$path = $dir.$name;

					if(move_uploaded_file($tmp_name, $path)){
						$files["files[$c]"] = new \CurlFile($path);
						$params = array_merge($params, $files);
					} else {
						throw new \Exception("Error Processing Request", 1);
					}
				}
				$c++;
			}
		}

		if(isset($_FILES["image"])){
			if ($_FILES["image"]["error"] == UPLOAD_ERR_OK){

				$img = $_FILES["image"]["tmp_name"];
				$img_name = $_FILES["image"]["name"];

				$imagen = isset($_POST["image_name"]) ? $_POST["image_name"] : null;
				$dir = $_SERVER["DOCUMENT_ROOT"]."/uploads/";
				if(!file_exists($dir)) @mkdir($dir);

				$path = $dir.$img_name;

				if(move_uploaded_file($img, $path)){
					$image_file = new \CurlFile($path);
					$params["image"] = $image_file;
				}else{
					throw new \Exception("Error Processing Request", 1);
				}
			}
		}

		$response = $CloureSDK->execute($params);
		$response_decoded = json_decode($response);
		
		if($topic=="login"){
			if(isset($response_decoded->Response->access_token)) $_SESSION["user_token"] = $response_decoded->Response->access_token;
		}
		
		if($topic=="get_locales"){
			if(isset($_POST["lang"])){
				$_SESSION["lang"] = $_POST["lang"];
			} else {
				$_POST["lang"] = $_SESSION["lang"];
			}
		}
		
		echo $response;
	}
?>