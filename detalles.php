<?php
/* Database connection start */
$array_ini = parse_ini_file("conf.ini", true);

$conn = mysqli_connect($array_ini['bdd']['servername'], $array_ini['bdd']['username'], $array_ini['bdd']['password'], $array_ini['bdd']['dbname']) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */

// storing request (ie, get/post) global array to a variable
$requestData = $_REQUEST;

// getting total number records without any search
$sql = "SELECT dato FROM log_procesos where idLogProcesos=?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', intval($requestData['idLogProcesos']));
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $dato);

mysqli_stmt_fetch($stmt);

$dom = $dom = new DOMDocument();
$dom->formatOutput = true;
$dom->preserveWhiteSpace = false;

if ($dom->loadXML($dato) != false)
    $dato = $dom->saveXML();
else {
    $jsonDato = json_decode($dato);
    if ($jsonDato != null)
        $dato = json_encode($jsonDato, JSON_PRETTY_PRINT);
}

$json_data = [
    'html' => '<pre>' . htmlentities(mb_convert_encoding($dato, 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8") . '</pre>'
];

echo json_encode($json_data); // send data as json format

?>

