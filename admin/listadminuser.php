<?php
header('Content-Type: text/html; charset=UTF-8');
session_start(); // Starting Session

//Cargamos el header de la página

if(empty($_SESSION['login_user'])){
  header("location: login.php");
}else{
?>
<HTML>
<HEAD>
<TITLE>List Users</TITLE>
</HEAD>
<BODY>
<style>
table, th, td {
   border: 1px solid black;
}
</style>
<div class='admin'>
	<a href ='index.php'><input type='submit' value='Salir' name='exit'/></a>
</div>
<div class='export'>
	<a href ='export.php'><input type='submit' value='Exportar' name='export'/></a>
</div>
<?php
//Añadimos le fichero de conexión
include("conectordb.php");
		//Cogemos los datos de adminusers
		$sql = "SELECT * FROM adminusers";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		//Creamos la tabla con los datos
		echo "<table>";
		echo "<caption>Listado de Usuarios</caption>";
		echo "<tr>";
		echo "<td>ID</td>";
		echo "<td>Nombre</td>";
		echo "<td>Correo Electrónico</td>";
		echo "</tr>";
	 
    while($row = $result->fetch_assoc())
		{
	$email=$row['email'];
	$id=$row['id'];
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['name']."</td>";
		echo "<td>".$row['email']."</td>";
		//Se añade un botón de eliminar por id partnerUID
		echo "<td><a href ='updateadminuser.php?UserEmail=$email'><input type='submit' value='Actualizar' name='update'/></a></td>";
		echo "<td><a href ='delete.php?UserID=$id'><input type='submit' value='Eliminar' name='delete'/></a></td>";
		echo "</tr>";
		}
		echo "</table>";
		}
		//Cerramos la BBDD
include("desconectordb.php");
}
?>
</BODY>
</HTML>