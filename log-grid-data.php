<?php
/* Database connection start */
$array_ini = parse_ini_file("conf.ini", true);

$conn = mysqli_connect($array_ini['bdd']['servername'], $array_ini['bdd']['username'], $array_ini['bdd']['password'], $array_ini['bdd']['dbname']) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */

// storing request (ie, get/post) global array to a variable
$requestData = $_REQUEST;

$columns = array(
    // datatable column index => database column name
    
    1 =>  'idLogProcesos',
    2 =>  'nivel',
    3 =>  'tablaIdProceso',
    4 =>  'idProceso',    
    5 =>  'marcaTemporal',
    6 =>  'tipoProceso',  
    7 =>  'tipoDato',     
    8 =>  'dato',         
    9 =>  'clase',        
    10 =>  'metodo',       
    11 =>  'operancion',
    12 =>  'estado'   
);

$sql = "SELECT 
    idLogProcesos,
    idProceso,
    marcaTemporal,
    tipoProceso,
    tipoDato,
    substr(dato,1,250) as dato,
    clase,
    metodo,
    nivel,
    tablaIdProceso,
    estado,
    operancion FROM log_procesos WHERE 1 = 1";

if (! empty($requestData['columns'][1]['search']['value']) || ! empty($requestData['columns'][1]['search']['value']) || ! empty($requestData['columns'][2]['search']['value']) || ! empty($requestData['columns'][3]['search']['value']) || ! empty($requestData['columns'][4]['search']['value']) || ! empty($requestData['columns'][5]['search']['value']) || ! empty($requestData['columns'][6]['search']['value']) || ! empty($requestData['columns'][7]['search']['value']) || ! empty($requestData['columns'][8]['search']['value'])) {
    // getting records as per search parameters
    if (! empty($requestData['columns'][1]['search']['value'])) { // idLogProcesos
        $sql .= " AND idLogProcesos = " . $requestData['columns'][1]['search']['value'];
    }
    if (! empty($requestData['columns'][2]['search']['value'])) { // nivel
        $sql .= " AND ( nivel = '" . $requestData['columns'][2]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][3]['search']['value'])) { // tablaIdProceso
        if ($requestData['columns'][3]['search']['value'] == 'nulo')
            $sql .= " AND ( tablaIdProceso is null ) ";
            else
                $sql .= " AND ( tablaIdProceso = '" . $requestData['columns'][3]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][4]['search']['value'])) { // idProceso
        $sql .= " AND idProceso = " . $requestData['columns'][2]['search']['value'];
    }
    if (! empty($requestData['columns'][5]['search']['value'])) { // marcaTemporal
        $sql .= " AND marcaTemporal like '" . $requestData['columns'][5]['search']['value'] . "%' ";
    }
    if (! empty($requestData['columns'][6]['search']['value'])) { // tipoProceso
        $sql .= " AND ( tipoProceso = '" . $requestData['columns'][6]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][7]['search']['value'])) { // tipoDato
        $sql .= " AND ( tipoDato = '" . $requestData['columns'][7]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][8]['search']['value'])) { // dato
        $sql .= " AND dato LIKE '%" . $requestData['columns'][8]['search']['value'] . "%' ";
    }
    if (! empty($requestData['columns'][9]['search']['value'])) { // clase
        if ($requestData['columns'][9]['search']['value'] == 'nulo')
            $sql .= " AND ( clase is null ) ";
        else
            $sql .= " AND ( clase = '" . $requestData['columns'][9]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][10]['search']['value'])) { // metodo
        if ($requestData['columns'][10]['search']['value'] == 'nulo')
            $sql .= " AND ( metodo is null ) ";
        else
            $sql .= " AND ( metodo = '" . $requestData['columns'][10]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][11]['search']['value'])) { // operancion
        if ($requestData['columns'][11]['search']['value'] == 'nulo')
            $sql .= " AND ( operancion is null ) ";
        else
            $sql .= " AND ( operancion = '" . $requestData['columns'][11]['search']['value'] . "' ) ";
    }
    if (! empty($requestData['columns'][12]['search']['value'])) { // estado
        $sql .= " AND ( estado = '" . $requestData['columns'][12]['search']['value'] . "' ) ";
    }

}
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 

$query = mysqli_query($conn, $sql) or die("employee-grid-data.php: get log_procesos");

$data = array();
$cuenta=0;
while ($row = mysqli_fetch_array($query)) { // preparing an array
    $nestedData = array();
    $nestedData['idLogProcesos'] = $row['idLogProcesos'];
    $nestedData['idProceso'] = $row['idProceso'];
    $nestedData['marcaTemporal'] = $row['marcaTemporal'];
    $nestedData['tipoProceso'] = $row['tipoProceso'];
    $nestedData['tipoDato'] = $row['tipoDato'];
    //si se necesita mejorar la velocidad ver esto de aca
    //quiza se pueda procesar en la base
    $nestedData['dato'] =htmlentities(mb_convert_encoding($row['dato'], 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8");
    $nestedData['clase'] = $row['clase'];
    $nestedData['metodo'] = $row['metodo'];
    $nestedData['operancion'] = $row['operancion'];
    $nestedData['nivel'] = $row['nivel'];
    $nestedData['tablaIdProceso'] = $row['tablaIdProceso'];
    $nestedData['estado'] = $row['estado'];
    $data[] = $nestedData;
    $cuenta++;
}

if ($cuenta==$requestData['length'])
    $falsaCantRegistros=$requestData['start']+$requestData['length']+1;
else 
    $falsaCantRegistros=$requestData['start']+$cuenta;

$json_data = array(
    "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal" => $falsaCantRegistros, 
    "recordsFiltered" => $falsaCantRegistros,
    "data" => $data // total data array
);

echo json_encode($json_data); // send data as json format

?>

