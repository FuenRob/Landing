<?php
  // Abrimos la conexion a la base de datos
	include_once "../conectordb.php"; 
  //Cogemos el id del user
	$id =$_REQUEST['UserID'];
	
	// Hacemos la query
	$sql ="DELETE FROM landing_users WHERE id = '$id'";
	$result = $conn->query($sql);
	
	if ($conn->query($sql) === TRUE) {
		header("Location: listuser.php");
		} else {
				echo "Error connect: " . $conn->error;
		}
	//Cerramos la BBDD
		include("../desconectordb.php");
?> 