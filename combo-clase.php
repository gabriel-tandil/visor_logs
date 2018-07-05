<?php
/* Database connection start */
$array_ini = parse_ini_file("conf.ini", true);

$conn = mysqli_connect($array_ini['bdd']['servername'], $array_ini['bdd']['username'], $array_ini['bdd']['password'], $array_ini['bdd']['dbname']) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */



// getting total number records without any search
$sql = "SELECT distinct clase ";
$sql.=" FROM log_procesos";
$query=mysqli_query($conn, $sql) or die("combo-clase.php: obtener valores");


$data = array();
while( $row=mysqli_fetch_array($query) ) { 
    if ($row[0]==null)
        echo '<option value="nulo">nulo</option>';
    else
        echo '<option value="'.str_replace('\\', '\\\\', $row[0]).'">...'.substr($row[0],strlen($row[0]-20), strlen($row[0])).'</option>';

}





?>
