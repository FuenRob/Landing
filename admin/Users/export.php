<?php

//Cargamos la conexión con BBDD
include("../conectordb.php");

$delimiter = ';';
//Cogemos la fecha del servidor
$date = date("Y-m-d");

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=landingusers-'.$date.'.csv');

//Creamos la variable que vamos a usar como puntero
$output = fopen('php://output', 'w');

//La cabecera de las columnas del excel
fputcsv($output, array('id','partnerUID','PartnerKey','CouponsList','FirstName','LastName','AddressPostalCode','AddressCity','URN','customProperty1','customProperty2','participaciones','fecha'), $delimiter);

//datos de exportación
$sql = 'SELECT * FROM landing_users';
$result = $conn->query($sql);

//Generamos las columnas
while ($row = $result->fetch_assoc()) fputcsv($output, $row, $delimiter);

//Desconectados la BBDD
include("../desconectordb.php");
?>