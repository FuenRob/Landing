<?php
header('Content-Type: text/html; charset=UTF-8');
session_start(); // Starting Session
// Abrimos la conexion a la base de datos
include_once "conectordb.php";
$email =$_REQUEST['UserEmail'];
//Cargamos el header de la página
//Vamos a coger la contraseña actual de usuario para comprobar que es él.
      
		  

$error=''; 
if(empty($_SESSION['login_user'])){
  header("location: login.php");
}else{
  if (isset($_POST['change'])){

      $name = $_POST['name'];
      $newpassword = $_POST['newpassword'];
      $passcif = md5($newpassword);
	  $newemail = $_POST['email'];
          $sql2 = "UPDATE adminusers SET name='$name', email='$newemail', password='$passcif' WHERE email = '$email'";
            $result2 = $conn->query($sql2);
            $error = "Datos actualizados. <a href='Profile/profile.php'> <input type='submit' value='Volver' name='volver' /> </a>";
  }
  $sql = "SELECT * FROM adminusers WHERE email = '$email'";
          $result = $conn->query($sql);
      while($row = $result->fetch_assoc()) {
?>
  <form action="" method="post" class="newpass">
    <div>
			<label>Nombre</label><br><input type="text" name="name" value="<?php echo $row['name']; ?>" required>
		</div>
    <div>
			<label>Email</label><br><input type="email" name="email" value="<?php echo $row['email']; ?>" required>
		</div>
    <div>
			<label>Contraseña</label><br><input type="password" name="newpassword" value="<?php echo $row['password']; ?>" required>
		</div>
    <br>
			<input type="submit" value="Cambiar" name="change"/>
  </form>
    <br><br>
      <span><?php echo $error; ?></span>
  
<?php
  }
}
//Cerramos la BBDD
	include("desconectordb.php");
	//Añadimos el pie de pagina
	
?>