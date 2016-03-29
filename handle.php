<?php
	header("Content-Type: application/json");
	$json = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$_GET['sym']);
	echo $json;


?>