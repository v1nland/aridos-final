@section('content')
    <div class="row">
        <div class="col">
            <h1 class="title">Buscador</h1>
            <hr>
        </div>
    </div>
    <div>
        <center>
            <form id="busqueda">
                <div class="row">
                    <!-- desde form -->
                    <div class="form-group col-md-6">
                        <label>Desde (obligatorio):</label>
                        <select class="form-control" id="anomin">
                            <option value="2017">Seleccione inicio</option>
                            <?php
                                $start_year = 2018;
                                $end_year = 2038;
                                while ($start_year <= $end_year) {
                                    echo "<option value='". $start_year ."'>{$start_year}</option>";
                                    $start_year++;
                                }
                            ?>
                        </select>
                    </div>
                    <!-- hasta form -->
                    <div class="form-group col-md-6">
                        <label>Hasta (obligatorio):</label>
                        <select class="form-control" id="anomax">
                            <option value="2018">Seleccione fin</option>
                            <?php
                                $start_year = 2019;
                                $end_year = 2038;
                                while ($start_year <= $end_year) {
                                    echo "<option value='". $start_year ."'>{$start_year}</option>";
                                    $start_year++;
                                }
                             ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- estado form -->
                    <div class="form-group col-md-6">
                        <label>Fase: </label>
                        <select class="form-control" onchange="myChangeFunction(this, 'Fase')" id="fase">
                            <option value=''>Seleccionar fase</option>
                            <option>FACTIBILIDAD</option>
                            <option>PROYECTO</option>
			</select>
                    </div>


                    <div class="form-group col-md-6">
                        <label>Estado:</label>
                        <select class="form-control" onchange="myChangeFunction(this, 'Estado')" id="estado">
                            <option value=''>Seleccionar estado</option>
                            <option>EN REVISION</option>
                            <option>CON OBSERVACIONES</option>
                            <option>RECHAZADO</option>
                            <option>CON VISTO BUENO</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- region/comuna input -->
                    <div class="form-group col-md-12">
                        <div class="campo control-group" data-id="10" data-dependiente-campo="dependiente" style="display: block;">
                            <label class="control-label">Region/Comuna</label>
                            <div class="controls">
                                <select class="form-control" id="regiones_10" data-id="10" onchange="myChangeFunction(this, 'Región')"  name="regioncomuna[region]" style="width:100%">
                                    <option value="">Seleccione Regi&oacute;n</option>
                                </select><br />

                                <select class="form-control" id="comunas_10" data-id="10" onchange="myChangeFunction(this, 'Comuna')" name="regioncomuna[comuna]" style="width:100%">
                                    <option value="">Seleccione Comuna</option>
                                </select>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    var justLoadedRegion=true;
                                    var justLoadedComuna=true;
                                    var defaultRegion="";
                                    var defaultComuna="";
                                    var opcion = "nombre";

                                    $("#regiones_10").chosen({placeholder_text: "Seleccione Regi\u00F3n"});
                                    $("#comunas_10").chosen({placeholder_text: "Seleccione Comuna"});

                                    updateRegiones();

                                    function updateRegiones(){
                                        $.getJSON("https://apis.digital.gob.cl/dpa/regiones?callback=?",function(data){
                                            var regiones_obj = $("#regiones_10");
                                            regiones_obj.empty();
                                            $.each(data, function(idx, el){
                                                regiones_obj.append("<option data-id=\""+el.codigo+"\" value=\""+el.nombre+"\">"+el.nombre+"</option>");
                                            });

                                            regiones_obj.change(function(event){
                                                var selectedId=$(this).find("option:selected").attr("data-id");
                                                updateComunas(selectedId);
                                                regiones_obj.attr("cstateCode_10",$(this).find("option:selected").attr("data-id"));
                                                regiones_obj.attr("cstateName_10",regiones_obj.val());
                                                $("#cstateCode_10").val($(this).find("option:selected").attr("data-id"));
                                                $("#cstateName_10").val(regiones_obj.val());
                                            });

                                            if(justLoadedRegion){
                                                regiones_obj.val(defaultRegion).change();
                                                justLoadedRegion=false;
                                            }
                                            regiones_obj.trigger("chosen:updated");
                                        });
                                    }

                                    function updateComunas(regionId){
                                        var comunas_obj = $("#comunas_10");
                                        comunas_obj.empty();

                                        if(typeof regionId === "undefined")
                                            return;

                                        $.getJSON("https://apis.digital.gob.cl/dpa/regiones/"+regionId+"/comunas?callback=?",function(data){
                                            if(data){
                                                comunas_obj.append('<option data-id="-1" value="">Seleccione Comuna</option>');

                                                $.each(data, function(idx, el){
                                                    var op = el[opcion];
                                                    comunas_obj.append("<option data-id=\""+el.codigo+"\" lat=\""+el.lat+"\" lng=\""+el.lng+"\" value=\""+op+"\" >"+el.nombre+"</option>");
                                                });
                                            }
                                            comunas_obj.trigger("chosen:updated");
                                            if(justLoadedComuna){
                                                comunas_obj.val(defaultComuna).change();
                                                justLoadedComuna=false;
                                            }
                                            comunas_obj.trigger("chosen:updated");

                                            $("#ccityCode_10").val($(comunas_obj).find("option:selected").val());
                                            $("#ccityName_10").val($(comunas_obj).find("option:selected").text());

                                            comunas_obj.change(function(event){
                                                $("#ccityCode_10").val($(this).find("option:selected").attr("data-id"));
                                                $("#ccityName_10").val($(this).find("option:selected").text());
                                            });
                                        });
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- cauce form -->
                    <div class="form-group col-md-12">
                        <label>Cauce:</label>
                        <input type="text" class="form-control" id="caucein" onchange="myChangeFunction(this, 'Cauce')" placeholder="Clarillo"> </input>
                    </div>
                </div>

                <div class="row">
                    <!-- volumen min -->
                    <div class="form-group col-md-6">
                        <label>Volumen mínimo:</label>
                        <input type="text" class="form-control" id="volmin" name="volmin" placeholder="0"> </input>
                    </div>

                    <!-- volumen max -->
                    <div class="form-group col-md-6">
                        <label>Volumen máximo:</label>
                        <input type="text" class="form-control" id="volmax" name="volmax" placeholder="50000"> </input>
                    </div>
                </div>
            </form>

            <button class="btn btn-danger">Buscar</button>
            <button class="btn btn-success" onclick="resetForm()">Reiniciar</button>
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
            <?php if (count($tramites) > 0): ?>
            <div class="table-responsive">
            <table id="mainTable" class="table table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>Código</th>
                    <th>Involucrados</th>
                    <th>Asignador</th>
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

                <tbody>
                    <?php $registros = false; ?>
                    <?php foreach ($tramites as $t): ?>
                    <?php
                        $file = false;
                        if (\App\Helpers\Doctrine::getTable('File')->findByTramiteId($t->id)->count() > 0) {
                            $file = true;
                            $registros = true;
                        }
                    ?>

                    <tr>
                        <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
                        <?php if ($file): ?>
                        <td>
                            <div class="checkbox"><label><input type="checkbox" class="checkbox1" name="select[]"
                                                                value="<?=$t->id?>"></label></div>
                        </td>
                        <?php else: ?>
                        <td></td>
                        <?php endif; ?>
                        <?php else: ?>
                        <td></td>
                        <?php endif; ?>

                        <!-- Código proyecto -->
                        <td>
                            <?php
                                $etapas = $t->getAllEtapas();
                                if (count($etapas) >= 1) {
                                    echo "<a href='" . url('etapas/ver/' . $etapas[0]->id) . "'>";
                                }
                                echo ( \App\Helpers\Doctrine::getTable('Etapa')->makeIDRegionByRegion($t->id, \App\Helpers\Doctrine::getTable('Etapa')->idByRegion($t->id)) );
                                if (count($etapas) >=1) {
                                    echo "</a>";
                                }

                                $hasBadge = \App\Helpers\Doctrine::getTable('Etapa')->hasFileForBadge($t->id, Cuenta::cuentaSegunDominio());
                                echo ($hasBadge != 'null' && $hasBadge != '') ? ' <span class="badge badge-primary">En consulta</span>' : '';
                            ?>
                        </td>
                        <!-- Código proyecto -->

                        <!-- Nombre involucrados -->
                        @php
                            $involuc_array = array();
                            $asignadores_array = array();
                            foreach($t->getAllEtapas() as $e){
                                if ($e->Usuario->nombres != "" && !$e->Usuario->hasGrupoUsuariosByNombre("Coordinador Regional")){
                                    $involuc_array[] = $e->Usuario->nombres;
                                }

                                if($e->Usuario->nombres != "" && $e->Usuario->hasGrupoUsuariosByNombre("Coordinador Regional")){
                                    $asignadores_array[] = $e->Usuario->nombres;
                                }
                            }
                        @endphp
                        <td class="name">
                            <?= implode(', ', array_unique($involuc_array)) ?>
                        </td>
                        <!-- Nombre involucrados -->

                        <!-- Nombre asignador -->
                        <td class="name">
                            <?= implode(', ', array_unique($asignadores_array)) ? implode(', ', array_unique($asignadores_array)) : 'Sin asignador' ?>
                        </td>
                        <!-- Nombre asignador -->

                        <!-- Nombre solicitante -->
                        @php
                            $fetchn = \App\Helpers\Doctrine::getTable('Etapa')->getSolicitante($t->id, Cuenta::cuentaSegunDominio());
                        @endphp
                        <td class="name">
                            <?php echo $fetchn; ?>
                        </td>
                        <!-- Nombre solicitante -->

                        <!-- Nombre region -->
                        @php
                            $region = \App\Helpers\Doctrine::getTable('Etapa')->getRegion($t->id, Cuenta::cuentaSegunDominio());
                        @endphp
                        <td>
                            <?php echo $region; ?>
                        </td>
                        <!-- Nombre region -->

                        <!-- Nombre comuna -->
                        @php
                            $comuna = \App\Helpers\Doctrine::getTable('Etapa')->getComuna($t->id, Cuenta::cuentaSegunDominio());
                        @endphp
                        <td>
                            <?php echo $comuna; ?>
                        </td>
                        <!-- Nombre comuna -->

                        <!-- Cantidad de volumen -->
                        <td>
                            <?php echo $t->getVolumen(); ?>
                        </td>
                        <!-- Cantidad de volumen -->

                        <!-- Nombre cauce -->
                        <td>
                            <?php echo $t->getCauce(); ?>
                        </td>
                        <!-- Nombre cauce -->

                        <!-- Fecha de ingreso -->
                        <td class="time"><?= strftime('%d.%b.%Y', mysql_to_unix($t->created_at)) ?>
                            <br/><?= strftime('%H:%M:%S', mysql_to_unix($t->created_at)) ?>
                        </td>
                        <!-- Fecha de ingreso -->

                        <!-- Muestra de bitácoras y estado -->
                        @php
                            $fetch = \App\Helpers\Doctrine::getTable('Etapa')->verBitacora($t->id, Cuenta::cuentaSegunDominio());
                            $bitacora = json_decode($fetch, true);

                            $bitacora_str = '';
                            foreach ($bitacora as $k => $v) {
                                $bitacora_str .= '+' . strftime('%d.%b.%Y', mysql_to_unix($v['fecha'])) . ', ' . strftime('%H:%M:%S', mysql_to_unix($v['fecha'])) . ': ' . $v['content'] . ', escrito por ' . $v['escritor'] . '<br />';
                            }

                            if( $bitacora_str == '' ){
                                $bitacora_str = '¡No hay bitácoras disponibles!';
			    }

				$fase_etapa =  $t->getUltimaEtapaReal();
                                $fase = explode("/", $fase_etapa)[0];
				$etapa = explode("/", $fase_etapa)[1]; 
                        @endphp
                        <td>
                            <?php
//                              echo $t->getUltimaEtapaRealColor( $fase );
				echo $t->getColor($fase);
                            ?>
			</td>

			<td>
                            <?php echo $t->getColor($etapa); ?>
			</td>

                        <!-- <td>
                            <?= $t->getUltimaEtapa()->Tarea->nombre ?>
                        </td> -->
                        <!-- Muestra de bitácoras y estado -->

                        <!-- Acciones (historial, agregar bitácora) -->
                        <td class="actions">
                            <?php
                                $form = "
                                    <form action='/bitacoras/agregar/{$t->id}/participados' method='POST'>
                                        " . csrf_field() . "
                                        <div class='form-group'>
                                            <textarea class='form-control' name='contenido' id='{{$t->id}}' placeholder='Ingresa la bitácora' rows='5'></textarea>
                                            <input class='form-control' type='hidden' name='escritor' id='escritor' value='" . Auth::user()->nombres . "'></input>
                                        </div>
                                        <center><button type='submit' class='btn btn-primary'>Agregar</button></center>
                                    </form>
                                "
                            ?>
                            <a href="#" data-toggle="popover" title="Agregar bitácora" data-html="true" data-content="{{$form}}" >
                                (+)Bitácora
                            </a>

                            <br>

                            <?php $etapas = $t->getEtapasParticipadas(UsuarioSesion::usuario()->id) ?>
                            <?php if (count($etapas) == 3e4354) : ?>
                            <a href="<?= url('etapas/ver/' . $etapas[0]->id) ?>">Historial</a>
                            <?php else: ?>
                            <div class="btn-group">
                                <a data-toggle="dropdown" href="#">Historial</a>
                                <ul class="dropdown-menu">
                                    <?php $i = 1 ?>
                                    <?php foreach ($etapas as $e): ?>
                                    <li><a href="<?= url('etapas/ver/' . $e->id) ?>"><?= $i . ". " . $e->Tarea->nombre ?></a></li>
                                    <?php $i = $i + 1 ?>
                                    <?php endforeach ?>
                                </ul>
                            </div>

                            <br>
                            <?php endif ?>


                            <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
                            <?php if ($file): ?>
                            <a href="#" onclick="return descargarDocumentos(<?=$t->id?>);">Descargar</a>
                            <?php endif; ?>
                            <?php endif; ?>

                            <?php
                                $tramite_nro = '';
                                foreach ($t->getValorDatoSeguimientoAll() as $tra_nro) {
                                    if ($tra_nro->nombre == 'historial_estados') {
                                        $tramite_nro = $tra_nro->valor;
                                    }
                                }
                                if(!empty($tramite_nro)):
                            ?>

                            <br>

                            <a href="#" onclick="return verEstados(<?=$t->id?>);">Estados</a>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

                <tfoot style="display: none;">
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Involucrados</th>
                        <th>Asignador</th>
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

            <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
            <?php if ($registros): ?>
            <div class="pull-right">
                <div class="checkbox">
                    <input type="hidden" id="tramites" name="tramites"/>
                    <label>
                        <input type="checkbox" id="select_all" name="select_all"/> Seleccionar todos
                        <a href="#" onclick="return descargarSeleccionados();"
                           class="btn btn-light preventDoubleRequest">Descargar seleccionados</a>
                    </label>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="modal" role="dialog"></div>
            <?php endif; ?>
            <?php endif; ?>

            <p><?= $tramites->links('vendor.pagination.bootstrap-4') ?></p>
            <?php else: ?>
            <p>Ud no ha participado en tr&aacute;mites.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal hide" id="modal"></div>
    <div class="modal hide" id="modalSelectIcon"></div>
@endsection

@push('script')
    <script type="text/javascript">
        function myChangeFunction(input1, inputid2) {
            var input2 = document.getElementById(inputid2);
            input2.value = input1.value;

            var e = document.createEvent('HTMLEvents');
            e.initEvent('change', false, false);
            input2.dispatchEvent(e);
        }

        function resetForm(){
            window.location.reload(false);
	}
    </script>

    <script>
        $(document).ready(function() {
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var min = parseInt( $('#volmin').val(), 10 );
                    var max = parseInt( $('#volmax').val(), 10 );
                    var volumen = parseFloat( data[7] ) || 0; // use data for the volumen column

                    if ( ( isNaN( min ) && isNaN( max ) ) ||
                         ( isNaN( min ) && volumen <= max ) ||
                         ( min <= volumen   && isNaN( max ) ) ||
                         ( min <= volumen   && volumen <= max ) )
                    {
                        return true;
                    }
                    return false;
                }
            );

            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var min = parseInt( $('#anomin').val(), 10 );
                    var max = parseInt( $('#anomax').val(), 10 );
                    var ano = parseFloat( data[9].substring(7,11) ) || 0; // use data for the ano column
                    // console.log(ano);

                    if ( ( isNaN( min ) && isNaN( max ) ) ||
                         ( isNaN( min ) && ano <= max ) ||
                         ( min <= ano   && isNaN( max ) ) ||
                         ( min <= ano   && ano <= max ) )
                    {
                        return true;
                    }
                    return false;
                }
            );

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

            // buscador
            $('#mainTable tfoot th').each( function () {
                var title = $(this).text();

                if (title !== 'Volumen' && title !== 'Ingreso' && title !== '' && title !== 'Código' && title !== 'Solicitante' && title !== 'Acciones') {
                    $(this).html( '<input type="hidden" id="'+title+'" value="" />' );
                }
            });

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

            var table = $('#mainTable').DataTable({
                "search": {
                    "searching": true,
                    "caseInsensitive": true
                },
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
                ]
            });

            // Apply the search
            table.columns().every(function () {
                var that = this;

                $('input', this.footer() ).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that.search( this.value ).draw();
                    }
                });
	    });

            $('#volmin, #volmax').keyup( function() {
                table.draw();
            });

            $('#anomin, #anomax').change( function() {
                table.draw();
            });
        });

        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                sanitize: false,
            }).on('click', function(e) { e.preventDefault(); return true; });
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

