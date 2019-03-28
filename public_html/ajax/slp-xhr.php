<?php	
	require_once $_SERVER["DOCUMENT_ROOT"]."/config.php";
	$params = [];
	if($_POST){foreach ($_POST as $key => $value) {$params[$key]=$value;}}
    echo $MarosticaAPI->get_notifications();
?>