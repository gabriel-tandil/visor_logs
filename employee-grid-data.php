<?php
/* Database connection start */
$servername = "127.0.0.1:23306";
$username = "vtauser";
$password = "Torito1974";
$dbname = "vta";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'clase', 
	1 => 'metodo',
	2=> 'marcaTemporal'
);




// getting total number records without any search
$sql = "SELECT count(*) ";
$sql.=" FROM log_procesos";
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get log_procesos");
$fila = mysqli_fetch_row($query);
$totalData = $fila[0];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.




$sql = "SELECT count(*)  ";
$sql.=" FROM log_procesos WHERE 1 = 1";

// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) ){   //name
	$sql.=" AND employee_name LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}
if( !empty($requestData['columns'][1]['search']['value']) ){  //salary
	$sql.=" AND employee_salary LIKE '".$requestData['columns'][1]['search']['value']."%' ";
}
if( !empty($requestData['columns'][2]['search']['value']) ){ //age
	$rangeArray = explode("-",$requestData['columns'][2]['search']['value']);
	$minRange = $rangeArray[0];
	$maxRange = $rangeArray[1];
	$sql.=" AND ( employee_age >= '".$minRange."' AND  employee_age <= '".$maxRange."' ) ";
}
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get log_procesos");
$fila = mysqli_fetch_row($query);
$totalFiltered = $fila[0];

//$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get log_procesos");

	


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["log_procesos_clase"];
	$nestedData[] = $row["log_procesos_metodo"];
	$nestedData[] = $row["log_procesos_marcaTemporal"];
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
