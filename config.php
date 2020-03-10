<?php
	$db_username = "root";
	$db_password = "3include";
	$db_hostname = "localhost";
	$db_database = "synergxdb";
	
	$mysqli = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
	if (!$mysqli) {
		die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	}

	function ErrorMsg($msg) {
		echo " <script>                ";
		echo "   window.alert('$msg'); ";
		echo "   history.go(-1);       ";
		echo " </script>               ";

		exit;
	}
	
	function isNull($element) {
		return ($element!="")?$element:"&nbsp;";
	}
?>
