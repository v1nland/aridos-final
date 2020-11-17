@section('style')
    <style>
        .map { width: 100%; height:400px; }
        #poly { position: relative; top: -400px; width: 10%; min-heigth:400px; backgound-color:#FFFFFF; }
        .add { top: 65px; left: .5em; }
        .ol-popup { position: absolute; background-color: white; -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
                    filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2)); padding: 15px; border-radius: 10px; border: 1px solid #cccccc; min-width: 280px; }
        .ol-popup:after, .ol-popup:before { top: 100%; border: solid transparent; content: " "; height: 0; width: 0;
                                            position: absolute; pointer-events: none; }
        .ol-popup:after { border-top-color: white; border-width: 10px; left: 48px; margin-left: -10px; }
        .ol-popup:before { border-top-color: #cccccc; border-width: 11px; left: 48px; margin-left: -11px; }
        .ol-popup-closer { text-decoration: none; position: absolute; top: 2px; right: 8px; }
        .ol-popup-closer:after { content: "✖"; }
        .map { width: 100%; height:400px; }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h1 class="title">Buscador por mapa</h1>
            <hr>
        </div>
    </div>
    <div>
        <center>
            <div id="map" class="map"></div>
                <form id="busqueda">
                    <div class="row">
                        <!-- latitud form -->
                        <div class="form-group col-md-6">
                            <!-- <label>Latitud:</label> -->
                            <input type="hidden" class="form-control" id="lat"> </input>
                        </div>

                        <!-- longitud form -->
                        <div class="form-group col-md-6">
                            <!-- <label>Longitud:</label> -->
                            <input type="hidden" class="form-control" id="lng"> </input>
                        </div>
                    </div>

                    <div class="row">
                        <!-- distancia form -->
                        <div class="form-group col-md-12">
                            <label>Radio de búsqueda (km):</label>
                            <input type="text" class="form-control" id="dist" placeholder='(e.g: 100)'> </input>
                        </div>
                    </div>
                </form>

                <button id="search-btn" onclick="updateTable()" class="btn btn-danger">Buscar</button>
        </center>
    </div>

    <hr>

    <div class="row">
        <div class="col">
            <h1 class="title">Resultados</h1>
            <hr>
        </div>
    </div>

    <div class="row">
    	<div class="col-xs-12 col-md-12">
            <div class="table-responsive">
        	    <table id="mainTable" class="table table-hover" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Solicitante</th>
                            <th>Región</th>
                            <th>Comuna</th>
                            <th>Volumen</th>
                            <th>Cauce</th>
			    <th>Ingreso</th>
				<th>Fase</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tfoot style="display: none;">
                        <tr>
                            <th>Código</th>
                            <th>Solicitante</th>
                            <th>Región</th>
                            <th>Comuna</th>
                            <th>Volumen</th>
                            <th>Cauce</th>
			    <th>Ingreso</th>
                                <th>Fase</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="modal hide" id="modal"></div>
    <div class="modal hide" id="modalSelectIcon"></div>
@endsection

@push('script')
    <script type="text/javascript">
	var table_arr_data = [ ];
	var table;

	function getColor(etapa) {
	if (etapa == "FACTIBILIDAD") {
		return "<font color='gold'><b>" + etapa + "</b></font>";
	} else if (etapa == "PROYECTO") {
		return "<font color='blue'><b>" + etapa + "</b></font>";
	} else if (etapa == "EN REVISION") {
		return "<font color='gray'><b>" + etapa + "</b></font>";
	} else if (etapa == "CON OBSERVACIONES") {
		return "<font color='aqua'><b>" + etapa + "</b></font>";
	} else if (etapa == "RECHAZADO") {
		return "<font color='red'><b>" + etapa + "</b></font>";
	} else if (etapa == "CON VISTO BUENO") {
		return "<font color='green'><b>" + etapa + "</b></font>";
	} else {
		return etapa;
	}
}

	function convertStateFase( etapa ){
		fact_revi = [
	"Solicitud de permiso para extracción de áridos",
	"Asignación de tareas",
	"Análisis factibilidad técnica DOH I",
	"Informe a municipio sobre la no factibilidad técnica de la solicitud con observaciones"
];
fact_vis = ["Solicitud de ingreso de antecedentes técnicos (proyecto) I"];
fact_rech = ["Rechazo municipio", "No factibilidad técnica de la solicitud", "Análisis factibilidad técnica DOH II"];
proy_revi = ["Análisis de proyecto I"];
proy_obs = ["Solicitud de ingreso de antecedentes técnicos (proyecto) II", "Solicitud de ingreso de antecedentes técnicos (proyecto) III", "Análisis de proyecto II", "Análisis de proyecto III"];
proy_rech = ["Informe sobre rechazo de solicitud sin observaciones", "Informe sobre rechazo de solicitud con observaciones"];
proy_vist = ["Informe sobre aprobación de solicitud"];

if (fact_revi.includes(etapa)) {
	return "FACTIBILIDAD";
} else if (fact_vis.includes(etapa)) {
	return "FACTIBILIDAD";
} else if (fact_rech.includes(etapa)) {
	return "FACTIBILIDAD";
} else if (proy_revi.includes(etapa)) {
	return "PROYECTO";
} else if (proy_obs.includes(etapa)) {
	return "PROYECTO";
} else if (proy_rech.includes(etapa)) {
	return "PROYECTO";
} else if (proy_vist.includes(etapa)) {
	return "PROYECTO";
} else {
	return "NA";
}

	}

	function convertStateEstado( etapa ){
fact_revi = [
        "Solicitud de permiso para extracción de áridos",
        "Asignación de tareas",
        "Análisis factibilidad técnica DOH I",
        "Informe a municipio sobre la no factibilidad técnica de la solicitud con observaciones"
];
fact_vis = ["Solicitud de ingreso de antecedentes técnicos (proyecto) I"];
fact_rech = ["Rechazo municipio", "No factibilidad técnica de la solicitud", "Análisis factibilidad técnica DOH II"];
proy_revi = ["Análisis de proyecto I"];
proy_obs = ["Solicitud de ingreso de antecedentes técnicos (proyecto) II", "Solicitud de ingreso de antecedentes técnicos (proyecto) III", "Análisis de proyecto II", "Análisis de proyecto III"];
proy_rech = ["Informe sobre rechazo de solicitud sin observaciones", "Informe sobre rechazo de solicitud con observaciones"];
proy_vist = ["Informe sobre aprobación de solicitud"];

if (fact_revi.includes(etapa)) {
        return "EN REVISION";
} else if (fact_vis.includes(etapa)) {
        return "CON VISTO BUENO";
} else if (fact_rech.includes(etapa)) {
        return "RECHAZADO";
} else if (proy_revi.includes(etapa)) {
        return "EN REVISION";
} else if (proy_obs.includes(etapa)) {
        return "CON OBSERVACIONES";
} else if (proy_rech.includes(etapa)) {
        return "RECHAZADO";
} else if (proy_vist.includes(etapa)) {
        return "CON VISTO BUENO";
} else {
        return "NA";
}

        }

	function searchInArray( arr, target ){
		for(var i in arr){
			for(var j in arr[i]){
				if( j == target ){
					return arr[i][j]
				}
			}
		}

		return 'N/A';
	}

	function cleanDuplicates( arr ){
		var uniques_ids = [];
		var uniques = [];

		for(var i=0; i<arr.length; i++){
			var id = arr[i]['id'];

			if ( !uniques_ids.includes(id) ){
				uniques_ids.push( id );
				uniques.push( arr[i] );
			}
		}

		return uniques;
	}

	function updateTable() {
	    lat = document.getElementById('lat').value;
	    lng = document.getElementById('lng').value;
	    dist = document.getElementById('dist').value;

	    if(lat == '' || lng == ''){
		    alert('Por favor selecciona un punto en el mapa.');
		    return;
	    }

	    if( dist == '' ){
		    alert('Por favor ingrese un radio de búsqueda.');
		    return;
	    }

	    console.log(lat, lng, dist);

	    fetch('https://aridos.eit.technology/backend/api/geo_near/1/'+lat+'/'+lng+'/'+dist+'?token=EyMRb8Uhx')
            .then( function(response) {
                return( response.json() );
            })
            .then( function(datajson) {
    		    table_arr = [ ];
    		    datajson = cleanDuplicates( datajson );

                for(var k in datajson) {
    			    var obj = [ ];
    			    var cur_obj = datajson[k];
    			    var datos = datajson[k]['datos'];
    			    var etapas = datajson[k]['etapas'];
    			    var last_etapa = etapas[etapas.length-1]
                    console.log( cur_obj );

                    var user = "<?php echo Auth::user()->nombres ?>";
                    var bitacoraForm = `<form action="/bitacoras/agregar/${cur_obj["id"]}/participados" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <textarea class="form-control" name="contenido" id="{${cur_obj["id"]}}" placeholder="Ingresa la bitácora" rows="5"></textarea>
                            <input class="form-control" type="hidden" name="escritor" id="escritor" value="${user}"></input>
                        </div>
                        <center><button type="submit" class="btn btn-primary">Agregar</button></center>
                    </form>`;

                    obj.push( `<a href='/etapas/ver/${last_etapa.id}'>${searchInArray(datos, 'id_tramite')}</a>` );
                    obj.push( searchInArray( datos, 'nombre_persona').toUpperCase() );
                    obj.push( searchInArray( datos, 'comunasfact' )['region'] );
                    obj.push( searchInArray( datos, 'comunasfact' )['comuna'] );
                    obj.push( searchInArray( datos, 'volu' ) );
                    obj.push( searchInArray( datos, 'cauce' ) );
                    obj.push( cur_obj['fecha_inicio'] );
		    obj.push( getColor( convertStateFase(cur_obj['etapas'][0]['tarea']['nombre']) ) );
		    obj.push( getColor(convertStateEstado(cur_obj['etapas'][0]['tarea']['nombre']) ) );
                    obj.push( `<a href="#" data-toggle="popover" title="Agregar bitácora" data-html="true" data-content='${bitacoraForm}' >
                                    (+)Bitácora
                                </a>` )

                    table_arr.push( obj );
                }

    		    table_arr_data = table_arr;
    	    })
    	    .then( function() {
    		    if(table) { table.destroy(); }

                // export button
                var buttonCommon = {
                    exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                return data.replace( /="(.*?)"/g, '' ).replace( /<[^>]*>/g, '' );
                            }
                        }
                    }
                };

		        table = $('#mainTable').DataTable({
                    "language":{
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Buscar:",
                        "sUrl":            "",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        },
                        "buttons": {
                            "copy": "Copiar",
                            "colvis": "Visibilidad"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        $.extend( true, {}, buttonCommon, {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }
                        } ),
                        $.extend( true, {}, buttonCommon, {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }
                        } ),
                        $.extend( true, {}, buttonCommon, {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8 ]
                            }
                        } )
                    ],
                	data: table_arr_data,
                	columns: [
                    	{title: "Código"},
                    	{title: "Solicitante"},
                    	{title: "Región"},
                    	{title: "Comuna"},
                    	{title: "Volumen"},
                    	{title: "Cauce"},
			    {title: "Ingreso"},
			    {title: "Fase"},
                    	{title: "Estado"},
                        {title: "Acciones"},
                	]
            	});

                $('body').on('click', function (e) {
                    $('[data-toggle="popover"]').each(function () {
                        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                            $(this).popover('hide');
                        }
                    });
                });

                $(function () {
                    $('[data-toggle="popover"]').popover({
                        html: true,
                        sanitize: false,
                    }).on('click', function(e) { e.preventDefault(); return true; });
                })
    	    })
        }

    	var vector2, map, marker;

    	$(document).ready(function() {
            vector2 = new ol.layer.Vector({
                source: new ol.source.Vector({
                    format: new ol.format.GeoJSON(),
                    url: "https://aridos.eit.technology/backend/api/geo_all/1?token=EyMRb8Uhx"
                })
            });

            map = new ol.Map({
                layers: [
                    new ol.layer.Tile({
                        source: new ol.source.OSM()
                    }),vector2
                ],
                target: 'map',
                view: new ol.View({
                    center:  ol.proj.fromLonLat([-70.73576,-33.47004]),
                    zoom: 7
                })
            });

            vector2.getSource().on('tileloadend', function() {
                map.getView().fit(vector2.getSource().getExtent(), map.getSize());
            });

            var iconStyle = new ol.style.Style({
                image: new ol.style.Icon({
                    anchor: [0.5, 1],
                    offset: [1, 00],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'fraction',
                    opacity: 0.75,
                    src: '/images/marker.png',
                    scale: 0.05
                })
            });

            map.on('click', function(evt){
                var coords = ol.proj.transform(evt.coordinate, 'EPSG:3857','EPSG:4326');
                console.log(coords);
                $("#lat").val(coords[1]);
                $("#lng").val(coords[0]);

                var feature = new ol.Feature(
                    new ol.geom.Point(evt.coordinate)
                );

                feature.setStyle(iconStyle);
                if(marker)
                    vector2.getSource().removeFeature(marker);
                marker = feature;
                vector2.getSource().addFeature(feature);
    	    });

    	    // descarga
            $('#select_all').click(function (event) {
                var checked = [];
                $('#tramites').val();
                if (this.checked) {
                    $('.checkbox1').each(function () {
                        this.checked = true;
                    });
                } else {
                    $('.checkbox1').each(function () {
                        this.checked = false;
                    });
                }
                $('#tramites').val(checked);
    	    });
    	});

        $( "#search-btn" ).click(function() {
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        function descargarDocumentos(tramiteId) {
            $("#modal").load("/etapas/descargar/" + tramiteId);
            $("#modal").modal();

            return false;
        }

        function descargarSeleccionados() {
            var numberOfChecked = $('.checkbox1:checked').length;
            if (numberOfChecked == 0) {
                alert('Debe seleccionar al menos un trámite');
                return false;
            } else {
                var checked = [];
                $('.checkbox1').each(function () {
                    if ($(this).is(':checked')) {
                        checked.push(parseInt($(this).val()));
                    }
                });
                $('#tramites').val(checked);
                var tramites = $('#tramites').val();
                $("#modal").load("/etapas/descargar/" + tramites);
                $("#modal").modal();
                return false;
            }
        }

        function verEstados(tramiteId) {
            $("#modal").load("/etapas/estados/" + tramiteId);
            $("#modal").modal();

            return false;
        }
    </script>
@endpush

