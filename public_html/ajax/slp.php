<?php	
	require_once $_SERVER["DOCUMENT_ROOT"]."/config.php";

	$params = [];
	if($_POST){foreach ($_POST as $key => $value) {$params[$key]=$value;}}
	echo json_encode($MarosticaAPI->execute($params, true));
?>