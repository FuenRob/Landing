<?php
header('Content-Type: text/html; charset=UTF-8');
session_start(); // Starting Session
// Abrimos la conexion a la base de datos
include_once "../conectordb.php";
//Cargamos el header de la página

if(empty($_SESSION['login_user'])){
  header("location: ../login.php");
}else{
	//Hacemos la sentencia SQL para sacar el id del usuario
	$sql = "SELECT id,name,email,state FROM adminusers WHERE email='". $_SESSION['login_user'] ."'";
	$result = $conn->query($sql);
	//Comprobamos si ha sacado algún resultado
	if ($result->num_rows > 0) {
      //Sacamos del datos de SELECT en una variable
			while($row = $result->fetch_assoc()) {
				$email = $row["email"];
?>
    <h1>
    Perfil de usuario  
    </h1>
    <div><label>Nombre</label><br>
    <input type="name" name="name" value="<?php echo $row["name"]; ?>"></div>
    <div><label>Email</label><br>
    <input type="email" name="email" value="<?php echo $row["email"]; ?>"></div>
    <br>
    <?php echo "<a href ='newpassword.php?UserEmail=$email'><input type='submit' value='Cambiar Contraseña' name='newpass'/></a>"; ?>

<?php
      }
  }else {echo "No hay id de usuario";}
}
//Cerramos la BBDD
include("../desconectordb.php");
//Cargamos el pie de la página

?>