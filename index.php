<!DOCTYPE html>
<html>
<title>Visor de Bitácora</title>
<head>
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript"
	src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				var dataTable = $('#log-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					"order": [[ 0, "desc" ]], // orden inicial por id descendente
					"ajax":{
						url :"log-grid-data.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".log-grid-error").html("");
							$("#log-grid").append('<tbody class="log-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#log-grid_processing").css("display","none");
							
						}
					}
				} );
				$("#log-grid_filter").css("display","none");  // hiding global search box
				$('.search-input-text').on( 'keyup click', function () {   // for text boxes
					var i =$(this).attr('data-column');  // getting column index
					var v =$(this).val();  // getting search input value
					dataTable.columns(i).search(v).draw();
				} );
				$('.search-input-select').on( 'change', function () {   // for select box
					var i =$(this).attr('data-column');  
					var v =$(this).val();  
					dataTable.columns(i).search(v).draw();
				} );
				
				
				
			} );
		</script>
<style>

body {
	background: #f7f7f7;
	color: #333;
	font: 90%/1.45em "Helvetica Neue", HelveticaNeue, Verdana, Arial,
		Helvetica, sans-serif;
}
</style>
</head>
<body>

	<h1>Visor de Bitácora de TodoAlojamiento</h1>

	<table id="log-grid" cellpadding="0" cellspacing="0" border="0"
		class="display" width="100%">
		<thead>
			<tr>

				<th>idLogProcesos</th>
				<th>idProceso</th>
				<th>Marca Temporal</th>
				<th>Tipo de Proceso</th>
				<th>Tipo de Dato</th>
				<th>Dato</th>
				<th>Clase</th>
				<th>Método</th>
				<th>Operanción</th>
			</tr>
		</thead>
		<thead>
			<tr>
				<td><input type="text" data-column="0" class="search-input-text"
					size="10"></td>
				<td><input type="text" data-column="1" class="search-input-text"
					size="10"></td>
				<td><input type="text" data-column="2" class="search-input-text"
					size="20"></td>
				<td><select data-column="3" class="search-input-select">
						<option value="">Todos</option>
						<option value="general">General</option>
						<option value="channel">Channel</option>
				</select></td>
				<td><select data-column="4" class="search-input-select">
						<option value="">Todos</option>
						<option value="texto-libre">texto-libre</option>
						<option value="xml-enviado">xml-enviado</option>
						<option value="xml-recibido">xml-recibido</option>
				</select></td>


				<td><input type="text" data-column="5" class="search-input-text"></td>
				<td><select data-column="6" class="search-input-select">
							<?php include 'combo-clase.php'; ?>
					</select></td>
				<td><select data-column="7" class="search-input-select">
							<?php include 'combo-metodo.php'; ?>
				</select></td>
				<td><select data-column="8" class="search-input-select">
							<?php include 'combo-operacion.php'; ?>
				</select></td>
			</tr>


		</thead>
	</table>

</body>
</html>
