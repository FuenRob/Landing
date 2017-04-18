<?php
// Parametros a configurar para la conexion de la base de datos
  $servername = "localhost";
  $user = "sportami_user";
  $pass = "}a=T{Tk4?zAy";
  $dbname = "sportami_prestashop";
  // Nos conectamos
  $conn = new mysqli($servername, $user, $pass, $dbname);
  // Chequeamos que está todo correcto
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
?>