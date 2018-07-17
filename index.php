<!DOCTYPE html>
<html>
<title>Visor de Bitácora</title>
<head>
<link rel="stylesheet" type="text/css"
	href="DataTables/datatables.min.css" />
<style>
td.details-control {
	background: url('imagenes/details_open.png') no-repeat center center;
	cursor: pointer;
}

tr.shown td.details-control {
	background: url('imagenes/details_close.png') no-repeat center center;
}

/* tr.loading td { */
/* 	text-align: center; */
/* } */
</style>
<script type="text/javascript" src="DataTables/datatables.min.js"></script>
<script type="text/javascript" language="javascript">
var dataTable=null;
function format ( rowData ) {
	var div = $('<div/>')
		.addClass( 'loading' )
		.text( 'Loading...' );

	$.ajax( {
		url: 'detalles.php',
		data: {
			idLogProcesos: rowData.idLogProcesos,
			formatear: rowData.tipoDato,
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
			        if (i==4 || i==5||i==7||i==8||i==9){//todos los que son combo
			        
					if (i==4){
						$(this).html( '<select>'
												+'<option value="">Todos</option>'
												+'<option value="general">General</option>'
												+'<option value="channel">Channel</option>'
												+'</select>');
					

					}else if (i==5){
						$(this).html( '<select>'
								+'<option value="">Todos</option>'
								+'<option value="texto-libre">texto-libre</option>'
								+'<option value="xml-enviado">xml-enviado</option>'
								+'<option value="xml-recibido">xml-recibido</option>'
								+'</select>');
						
					}else if (i==7){
						$(this).html( '<select>'
								<?php include 'combo-clase.php'; ?>
								+'</select>');
						
					}else if (i==8){
						$(this).html( '<select>'
								<?php include 'combo-metodo.php'; ?>
								+'</select>');
						
					}else if (i==9){
						$(this).html( '<select>'
								<?php include 'combo-operacion.php'; ?>
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
					
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					"processing": true,
					"serverSide": true,
			        orderCellsTop: true,
			        fixedHeader: true,
			        dom: 'Bfrtip',
			        buttons: [
			            'colvis', 'pageLength' ,'csv'
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
    		            { "data": 'idProceso'     },
    		            { "data": 'marcaTemporal'},
    		            { "data": 'tipoProceso'   },
    		            { "data": 'tipoDato'      },
    		            { "data": 'dato'          },
    		            { "data": 'clase'         },
    		            { "data": 'metodo'        },
    		            { "data": 'operancion'    }
    		            ],
					});
				

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
		<tfoot>
			<tr>
				<th></th>
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
		</tfoot>
	</table>

</body>
</html>
