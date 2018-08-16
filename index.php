<!DOCTYPE html>
<html>
<title>Visor de Bitácora</title>
<head>
<link rel="stylesheet" type="text/css"
	href="DataTables/datatables.min.css" />
<link rel="stylesheet" type="text/css"
	href="custom-loader-css/dataTables.customLoader.walker.css">
<link rel="shortcut icon" type="image/png" href="favicon.png" />
<style>
td.details-control {
	background: url('imagenes/details_open.png') no-repeat center center;
	cursor: pointer;
}

tr.shown td.details-control {
	background: url('imagenes/details_close.png') no-repeat center center;
}
</style>
<script type="text/javascript" src="DataTables/datatables.min.js"></script>

<script type="text/javascript">

function format ( rowData ) {
	var div = $('<div/>')
		.addClass( 'loading' )
		.text( 'Cargando...' );

	$.ajax( {
		url: 'detalles.php',
		data: {
			idLogProcesos: rowData.idLogProcesos,
			tipoDato: rowData.tipoDato,
		},
		dataType: 'json',
		type: 'post',
		success: function ( json ) {
			div
				.html(json.html )
				.removeClass( 'loading' );
		} 
	} );

	return div;
}

			$(document).ready(function() {

			    // Setup - add a text input to each footer cell
			    $('#log-grid thead tr').clone(true).appendTo( '#log-grid thead' );
			    $('#log-grid thead tr:eq(1) th').each( function (i) {
			        var title = $(this).text();
			        if (i==2 || i==3||i==6||i==7||i==9||i==10||i==11||i==12){//todos los que son combo

			        	if (i==2){
							$(this).html( '<select>'
													+'<option value="">Todos</option>'
													+'<option value="debug">Debug</option>'
													+'<option value="error">Error</option>'
													+'<option value="fatal">Fatal</option>'
													+'<option value="info">Info</option>'
													+'<option value="trace">Trace</option>'
													+'<option value="warn">Warning</option>'
													+'</select>');
			        	}else if (i==3){//Tabla
							$(this).html( '<select>'
									<?php include 'combo-tabla.php'; ?>
									+'</select>');
						}else if (i==6){// tipo proceso
							$(this).html( '<select>'
												+'<option value="">Todos</option>'
												+'<option value="general">General</option>'
												+'<option value="channel">Channel</option>'
												+'<option value="extranet">Extranet</option>'
												+'<option value="portal">Portal</option>'
												+'</select>');
					

					}else if (i==7){
						$(this).html( '<select>'
								+'<option value="">Todos</option>'
								+'<option value="texto-libre">texto-libre</option>'
								+'<option value="dato-enviado">dato-enviado</option>'
								+'<option value="dato-recibido">dato-recibido</option>'
								+'</select>');
						
					}else if (i==9){
						$(this).html( '<select>'
								<?php include 'combo-clase.php'; ?>
								+'</select>');
						
					}else if (i==10){
						$(this).html( '<select>'
								<?php include 'combo-metodo.php'; ?>
								+'</select>');
						
					}else if (i==11){
						$(this).html( '<select>'
								<?php include 'combo-operacion.php'; ?>
								+'</select>');
					}else if (i==12){//estado
						$(this).html( '<select>'
								+'<option value="">Todos</option>'
								+'<option value="registrado">Registrado</option>'
								+'<option value="visto">Visto</option>'
								+'<option value="en_proceso">En Proceso</option>'
								+'<option value="solucionado">Solucionado</option>'
								+'<option value="con_bandera">Con Bandera</option>'
								+'</select>');
					}
					//evento de busqueda para los combos
					$( 'select', this ).on( ' change', function () {
				        
						dataTable
				            .column(i)
				            .search( $(this).val() )
				            .draw();
				    
				} );

					}else if (i!=0 ){
			        $(this).html( '<input type="text" placeholder="todos" size=10/>' );
			 
			        $( 'input', this ).on( 'keyup change', function () {
			            if ( dataTable.column(i).search() !== this.value ) {
			            	dataTable
			                    .column(i)
			                    .search( this.value )
			                    .draw();
			            }
			        } );
					}
			    } );
				
				dataTable = $('#log-grid').DataTable( {
					"pageLength": 50,
					"lengthMenu": [[1, 10, 25, 50, 100, 500, 1000], [1,10, 25, 50, 100, '500 (mucho)', '1000 (demasiado)']],
					"processing": true,
					"serverSide": true,
					"language": {
						"processing": "<div></div><div></div><div></div><div></div><div></div>",
						"info": "Registros: _START_ .. _END_ / Página: _PAGE_"						
					},
			        orderCellsTop: true,
			        fixedHeader: true,
			        "dom": '<"top"Bpi>t<"bottom"pi>',
			        "pagingType": "simple",

			        buttons: [
			        	{
			                extend: 'colvis',
			                columns: ':gt(0)'
			            },
			             'pageLength' ,'csv',
			             {
			                 text: 'Volver a Filtrar',
			                 action: function ( e, dt, node, config ) {
			                     dt.ajax.reload();
			                 }
			             }
			        ],			        
					"order": [[ 1, "desc" ]], // orden inicial por id descendente
					"ajax":{
						url :"log-grid-data.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".log-grid-error").html("");
							$("#log-grid").append('<tbody class="log-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#log-grid_processing").css("display","none");
							}
						},
    		        "columns": [
    		            {
    		                "class":          "details-control",
    		                "orderable":      false,
    		                "data":          null,
    		                "defaultContent": ""
    		            },
    		            { "data": 'idLogProcesos' },
    		            { "data": 'nivel' },
    		            { "data": 'tablaIdProceso',
        		            "visible": false },    		            
    		            { 
        		           "data": 'idProceso' ,
        		           "visible": false
        		        },
    		            { "data": 'marcaTemporal'},
    		            {
        		            "data": 'tipoProceso'  ,
        		            "visible": false 
        		        },
    		            { "data": 'tipoDato'   },
    		            { "data": 'dato'          },
    		            { "data": 'clase'         },
    		            { "data": 'metodo'        },
    		            { "data": 'operancion'    },
    		            { "data": 'estado' ,
        		            "visible": false   }
    		            ],
					});


				$("#log-grid_filter").css("display","none");
				// Add event listener for opening and closing details
				$('#log-grid tbody').on('click', 'td.details-control', function () {
					var tr = $(this).closest('tr');
					var row = dataTable.row( tr );

					if ( row.child.isShown() ) {
						// This row is already open - close it
						row.child.hide();
						tr.removeClass('shown');
					}
					else {
						// Open this row
						row.child( format(row.data(),false) ).show();
						tr.addClass('shown');
					}
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
				<th></th>
				<th>idLogProcesos</th>
				<th>Nivel</th>
				<th>Tabla Proceso</th>
				<th>idProceso</th>
				<th>Marca Temporal</th>
				<th>Tipo de Proceso</th>
				<th>Tipo de Dato</th>
				<th>Dato</th>
				<th>Clase</th>
				<th>Método</th>
				<th>Operanción</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<th>idLogProcesos</th>
				<th>Nivel</th>
				<th>Tabla Proceso</th>
				<th>idProceso</th>
				<th>Marca Temporal</th>
				<th>Tipo de Proceso</th>
				<th>Tipo de Dato</th>
				<th>Dato</th>
				<th>Clase</th>
				<th>Método</th>
				<th>Operanción</th>
				<th>Estado</th>
			</tr>
		</tfoot>
	</table>

</body>
</html>
