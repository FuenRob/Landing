<?php
header('Content-Type: text/html; charset=UTF-8');
session_start(); // Starting Session
if(isset($_SESSION['login_user'])){
header("location: registro.php");
}
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['email']) || empty($_POST['password'])) {
$error = "Usuario o Contaseña invalidos";
}
else
{
// Define $email and $password
$email=$_POST['email'];
$password=md5($_POST['password']);

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
include("conectordb.php"); // Abrimos la conexion a la base de datos
// SQL query to fetch information of registerd users and finds user match.
$sql = "select * from adminusers where email = '$email' and password='$password'";
$result = mysqli_query($conn,$sql);
//	$data_user = mysqli_fetch_array($result,$conn);
//	$id_login = $data_user['id']
$rows = mysqli_num_rows($result);
	if ($rows == 1) {
$_SESSION['login_user']=$email; // Initializing Session
//$_SESSION['id_user']=$id_login;
header("location: index.php"); // Redirecting To Other Page
} else {
$error = "Usuario o Contaseña inválidos";
}
//Cerramos la BBDD
include("desconectordb.php");
}
}
//Cargamos el header de la página

?>
<h2>Login</h2>
<form action="" method="post">
<label>Email</label><br>
<input id="email" name="email" type="text"><br>
<label>Contraseña</label><br>
<input id="password" name="password" type="password"><br>
<input name="submit" type="submit" value="Login" class="login"><br>
	<span><?= $error; ?></span>
</form>
<?php
//Cargariamos el footer
?>