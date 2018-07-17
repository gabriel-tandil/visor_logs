<?php
/* Database connection start */
$array_ini = parse_ini_file("conf.ini", true);

$conn = mysqli_connect($array_ini['bdd']['servername'], $array_ini['bdd']['username'], $array_ini['bdd']['password'], $array_ini['bdd']['dbname']) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */

// storing request (ie, get/post) global array to a variable
$requestData = $_REQUEST;

$columns = array(
    // datatable column index => database column name
    
    0 =>'idFila', 
    1 =>  'idLogProcesos',
    2 =>  'idProceso',    
    3 =>  'marcaTemporal',
    4 =>  'tipoProceso',  
    5 =>  'tipoDato',     
    6 =>  'dato',         
    7 =>  'clase',        
    8 =>  'metodo',       
    9 =>  'operancion'    
);

// getting total number records without any search
$sql = "SELECT count(*) ";
$sql .= " FROM log_procesos";
$query = mysqli_query($conn, $sql) or die("employee-grid-data.php: get log_procesos");
$fila = mysqli_fetch_row($query);
$totalData = $fila[0];
$totalFiltered = $totalData; // when there is no search parameter then total number rows = total number filtered rows.

$sql = " FROM log_procesos WHERE 1 = 1";

if (! empty($requestData['columns'][1]['search']['value']) || ! empty($requestData['columns'][1]['search']['value']) || ! empty($requestData['columns'][2]['search']['value']) || ! empty($requestData['columns'][3]['search']['value']) || ! empty($requestData['columns'][4]['search']['value']) || ! empty($requestData['columns'][5]['search']['value']) || ! empty($requestData['columns'][6]['search']['value']) || ! empty($requestData['columns'][7]['search']['value']) || ! empty($requestData['columns'][8]['search']['value'])) {
    // getting records as per search parameters
    if (! empty($requestData['columns'][1]['search']['value'])) { // idLogProcesos
        $sql .= " AND idLogProcesos = " . $requestData['columns'][1]['search']['value'];
    }
    if (! empty($requestData['columns'][2]['search']['value'])) { // idProceso
        $sql .= " AND idProceso = " . $requestData['columns'][2]['search']['value'];
    }
    if (! empty($requestData['columns'][4]['search']['value'])) { // tipoProceso
        
        $sql .= " AND ( tipoProceso = '" . $requestData['columns'][4]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][5]['search']['value'])) { // tipoDato
        $sql .= " AND ( tipoDato = '" . $requestData['columns'][5]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][7]['search']['value'])) { // clase
        if ($requestData['columns'][7]['search']['value'] == 'nulo')
            $sql .= " AND ( clase is null ) ";
        else
            $sql .= " AND ( clase = '" . $requestData['columns'][7]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][8]['search']['value'])) { // metodo
        if ($requestData['columns'][8]['search']['value'] == 'nulo')
            $sql .= " AND ( metodo is null ) ";
        else
            $sql .= " AND ( metodo = '" . $requestData['columns'][8]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][9]['search']['value'])) { // operancion
        if ($requestData['columns'][9]['search']['value'] == 'nulo')
            $sql .= " AND ( operancion is null ) ";
        else
            $sql .= " AND ( operancion = '" . $requestData['columns'][9]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][3]['search']['value'])) { // marcaTemporal
        $sql .= " AND marcaTemporal like '" . $requestData['columns'][3]['search']['value'] . "%' ";
    }
    if (! empty($requestData['columns'][6]['search']['value'])) { // dato
        $sql .= " AND dato LIKE '%" . $requestData['columns'][6]['search']['value'] . "%' ";
    }
    $query = mysqli_query($conn, ' SELECT count(*) ' . $sql) or die("employee-grid-data.php: get log_procesos");
    $fila = mysqli_fetch_row($query);
    $totalFiltered = $fila[0];
}
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

$query = mysqli_query($conn, "SELECT 
    idLogProcesos,
    idProceso,
    marcaTemporal,
    tipoProceso,
    tipoDato,
    substr(dato,1,250) as dato,
    clase,
    metodo,
    operancion  " . $sql) or die("employee-grid-data.php: get log_procesos");

$data = array();
while ($row = mysqli_fetch_array($query)) { // preparing an array
    $nestedData = array();
  //  $nestedData['idFila'] = 'fila_'.$row['idLogProcesos'];
    $nestedData['idLogProcesos'] = $row['idLogProcesos'];
    $nestedData['idProceso'] = $row['idProceso'];
    $nestedData['marcaTemporal'] = $row['marcaTemporal'];
    $nestedData['tipoProceso'] = $row['tipoProceso'];
    $nestedData['tipoDato'] = $row['tipoDato'];
    $nestedData['dato'] = htmlentities($row['dato']);
    $nestedData['clase'] = $row['clase'];
    $nestedData['metodo'] = $row['metodo'];
    $nestedData['operancion'] = $row['operancion'];
       
    $data[] = $nestedData;
}

$json_data = array(
    "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal" => intval($totalData), // total number of records
    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data" => $data // total data array
);

echo json_encode($json_data); // send data as json format

?>

