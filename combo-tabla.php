<?php
/* Database connection start */
$array_ini = parse_ini_file("conf.ini", true);

$conn = mysqli_connect($array_ini['bdd']['servername'], $array_ini['bdd']['username'], $array_ini['bdd']['password'], $array_ini['bdd']['dbname']) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


$sql = "SELECT distinct tablaIdProceso ";
$sql.=" FROM log_procesos";
$query=mysqli_query($conn, $sql) or die("combo-tabla.php: obtener valores");

echo '+\'<option value="">Todos</option>\'';
$data = array();
while( $row=mysqli_fetch_array($query) ) { 
    if ($row[0]==null)
        echo '+\'<option value="nulo">nulo</option>\'';
    else
        echo '+\'<option value="'.$row[0].'">'.$row[0].'</option>\'';

}





?>

