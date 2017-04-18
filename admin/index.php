<?php
header('Content-Type: text/html; charset=UTF-8');
session_start(); // Starting Session
// Abrimos la conexion a la base de datos
	include_once "conectordb.php";
if(empty($_SESSION['login_user'])){
header("location: login.php");
}else{
	echo "<div class='session'>
				Hola <a href='Profile/profile.php'>".
				 $_SESSION['login_user'] ."</a>,  
 				<a href='Profile/logout.php'>Cerrar sesión</a>
		</div>";

//Incluimos el Header de la página

?>
 <h1>Panel control</h1>
  <table>
	<tr><td><a href="registro.php">Añadir usuarios</a></td><td><a href="listadminuser.php">ver usuarios</a></td></tr>
    <tr><td><a href="Users/listuser.php">Ver usuarios de la Landing</a></td></tr>
  </table>
<?php

}
//Cargamos el pie de la página

?>