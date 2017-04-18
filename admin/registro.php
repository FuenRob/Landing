<?php
if (isset($_POST['register'])){
include("conectordb.php");	
  
//Se añaden datos de Usuario para empezar a usar la app
$name = $_POST["name"];   
$email = $_POST["email"];  
$password = $_POST["password"];
	//Ciframos la clave
	$passcif = md5($password);

//Se añade un usuario
$_GRABAR_SQL = "INSERT INTO adminusers 
(name,email,password,state) 
VALUES ('$name','$email','$passcif','1')";
if ($conn->query($_GRABAR_SQL) === TRUE) {
      echo "Se ha añadido registros a Usuarios de Gidea. <a href='index.php'>Ir al index</a><br>";
  } else {
      echo "Error creating table: " . $conn->error;
  }
include("desconectordb.php");
}

//Cargamos el header

?>
		<form action="" method="post" class="register">
				<h1>Registro</h1>
				<div>
					<label>Nombre de contacto</label><br><input type="name" name="name" required>
				</div>
				<div>
					<label>Email</label><br> <input type="email" name="email" required>	
				</div>
				<div>
					<label>Contraseña</label><br><input type="password" name="password" required>
				</div>
			<br>
			<input type="submit" value="Registrar" name="register" class="register-button"/>
		 </form>
<?php
//Cargamos el footer

?>