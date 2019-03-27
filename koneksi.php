<?php
	/*$servername = "sql130.main-hosting.eu";
	$username = "u610112734_baron";
	$password = "barong_123*";
	$dbname = "u610112734_baron";*/
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "deli_shop";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
?>