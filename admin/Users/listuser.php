<?php
session_start(); // Starting Session

//Cargamos el header de la página

if(empty($_SESSION['login_user'])){
  header("location: ../login.php");
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
	<a href ='../index.php'><input type='submit' value='Salir' name='exit'/></a>
</div>
<div class='export'>
	<a href ='export.php'><input type='submit' value='Exportar' name='export'/></a>
</div>
<?php
//Añadimos le fichero de conexión
include("../conectordb.php");
		//Cogemos los datos de landing_users
		$sql = "SELECT * FROM landing_users";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		//Creamos la tabla con los datos
		echo "<table>";
		echo "<caption>Listado de Usuarios</caption>";
		echo "<tr>";
		echo "<td>ID</td>";
		echo "<td>partnerUID</td>";
		echo "<td>PartnerKey</td>";
		echo "<td>CouponsList</td>";
		echo "<td>FirstName</td>";
		echo "<td>LastName</td>";
		echo "<td>AddressPostalCode</td>";
		echo "<td>AddressCity</td>";
		echo "<td>URN</td>";
		echo "<td>customProperty1</td>";
		echo "<td>customProperty2</td>";
		echo "<td>participaciones</td>";
		echo "<td>fecha</td>";
		echo "</tr>";

    while($row = $result->fetch_assoc())
		{
	$id=$row['id'];
		echo "<tr>";
		echo "<td>".$row['id']."</td>";
		echo "<td>".$row['partnerUID']."</td>";
		echo "<td>".$row['PartnerKey']."</td>";
		echo "<td>".$row['CouponsList']."</td>";
		echo "<td>".$row['FirstName']."</td>";
		echo "<td>".$row['LastName']."</td>";
		echo "<td>".$row['AddressPostalCode']."</td>";
		echo "<td>".$row['AddressCity']."</td>";
		echo "<td>".$row['URN']."</td>";
		echo "<td>".$row['customProperty1']."</td>";
		echo "<td>".$row['customProperty2']."</td>";
		echo "<td>".$row['participaciones']."</td>";
		echo "<td>".$row['fecha']."</td>";
		//Se añade un botón de eliminar por id partnerUID
		echo "<td><a href ='delete.php?UserID=$id'><input type='submit' value='Eliminar' name='delete'/></a></td>";
		echo "</tr>";
		}
		echo "</table>";
		}
		//Cerramos la BBDD
include("../desconectordb.php");
}
?>
</BODY>
</HTML>