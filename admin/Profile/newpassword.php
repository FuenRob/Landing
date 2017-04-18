<?php
header('Content-Type: text/html; charset=UTF-8');
session_start(); // Starting Session
// Abrimos la conexion a la base de datos
include_once "../conectordb.php";
$email =$_REQUEST['UserEmail'];
//Cargamos el header de la página

$error=''; 
if(empty($_SESSION['login_user'])){
  header("location: ../login.php");
}else{
  if (isset($_POST['change'])){
  if (empty($_POST['oldpassword']) || empty($_POST['newpassword'])) {
    $error = "Rellena todos los campos";
    }
    else
    {
      $oldpassword = md5($_POST['oldpassword']);
      $newpassword = $_POST['newpassword'];
      $twonewpassword = $_POST['twonewpassword'];
      //Vamos a coger la contraseña actual de usuario para comprobar que es él.
      $sql = "SELECT password FROM adminusers WHERE email = '$email'";
          $result = $conn->query($sql);
      while($row = $result->fetch_assoc()) {
        if($row['password']==$oldpassword){
        if($newpassword == $twonewpassword){
          $passcif = md5($newpassword);
          $sql2 = "UPDATE adminusers SET password='$passcif' WHERE email = '$email'";
            $result2 = $conn->query($sql2);
            $error = "Contraseña cambiada. <a href='profile.php'> <input type='submit' value='Volver' name='volver' class='index'/> </a>";
        }else{$error = "Confirma la nueva contraseña";}
        }else{$error = "Confirma la contraseña antigua. Está mal.";}
      }
    }
  }
?>
  <form action="" method="post" class="newpass">
    <div>
			<label>Contraseña Antigua</label><br><input type="password" name="oldpassword" required>
		</div>
    <div>
			<label>Nueva Contraseña</label><br><input type="password" name="newpassword" required>
		</div>
    <div>
			<label>Confirmar Nueva Contraseña</label><br><input type="password" name="twonewpassword" required>
		</div>
    <br>
			<input type="submit" value="Cambiar" name="change"/>
  </form>
    <br><br>
      <span><?php echo $error; ?></span>

<?php
}
//Cerramos la BBDD
	include("../desconectordb.php");
	//Añadimos el pie de pagina
	
?>