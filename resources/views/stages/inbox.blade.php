@extends('layouts.procedure')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12">
	    <h1 class="title">
@if(!Auth::user()->belongsToGroup("Coordinador Regional"))
		Solicitudes pendientes
@else
		Solicitudes en proceso de asignación
@endif
		</h1>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?php if (count($etapas) > 0): ?>
            <div class="table-responsive">
                <table id="mainTable" class="table table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Involucrados</th>
			<th>Asignador</th>
			<th>Revisor</th>
                        <th>Solicitante</th>
                        <th>Ubicación</th>
                        <th>Ingreso</th>
                        <th>Fase/Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $registros = false; ?>
                    <?php foreach ($etapas as $e): ?>
                    <?php
                    $t = $e->Tramite;

                    $file = false;
                    if (\App\Helpers\Doctrine::getTable('File')->findByTramiteId($e->Tramite->id)->count() > 0) {
                        $file = true;
                        $registros = true;
                    }
                    ?>
                    <tr <?=$e->getPrevisualizacion() ? 'data-toggle="popover" data-html="true" data-title="<h4>Previsualización</h4>" data-content="' . htmlspecialchars($e->getPrevisualizacion()) . '" data-trigger="hover" data-placement="bottom"' : ''?>>
                        <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
                        <?php if ($file): ?>
                        <td>
                            <div class="checkbox"><label><input type="checkbox" class="checkbox1" name="select[]" value="<?=$e->Tramite->id?>"></label></div>
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
                    $revisores_array = array();

                    foreach($t->getAllEtapas() as $e){
                        if ($e->Usuario->nombres != ""){
                            $involuc_array[] = $e->Usuario->nombres;
                        }

                        if($e->Usuario->nombres != "" && $e->Usuario->hasGrupoUsuariosByNombre("Coordinador Regional")){
                            $asignadores_array[] = $e->Usuario->nombres;
                        }
                        if($e->Usuario->nombres != "" && $e->Usuario->hasGrupoUsuariosByNombre("Usuario DOH")){
                            $revisores_array[] = $e->Usuario->nombres;
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

                <!-- Nombre revisor -->
                <td class="name">
                    <?= implode(', ', array_unique($revisores_array)) ? implode(', ', array_unique($revisores_array)) : 'Sin revisor' ?>
                </td>
                <!-- Nombre revisor -->

                        <!-- Nombre solicitante -->
                        @php
                            $fetchn = \App\Helpers\Doctrine::getTable('Etapa')->getSolicitante($t->id, Cuenta::cuentaSegunDominio());
                        @endphp
                        <td class="name">
                            <?php echo $fetchn; ?>
                        </td>
                        <!-- Nombre solicitante -->

                        <!-- Region/comuna -->
                        @php
                            $fetchrc = \App\Helpers\Doctrine::getTable('Etapa')->getRegionComuna($t->id, Cuenta::cuentaSegunDominio());
                        @endphp
                        <td>
                            <?php echo $fetchrc; ?>
                        </td>
                        <!-- Region/comuna -->

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
                        @endphp
                        <td>
                            <?php
                                if(!Auth::user()->belongsToGroup("Coordinador Regional"))
                                    echo '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Bitacora</b> <br />' . $bitacora_str . '">';

                                echo $t->getUltimaEtapaRealColor( $t->getUltimaEtapaReal() );

                                if(!Auth::user()->belongsToGroup("Coordinador Regional"))
                                    echo "</div>";
                            ?>
                        </td>

                        <!-- <td>
                            <?= $t->getUltimaEtapa()->Tarea->nombre ?>
                        </td> -->
                        <!-- Muestra de bitácoras y estado -->

                        <!-- Acciones -->
                        <td class="actions">
                            <a href="<?=url('etapas/ejecutar/' . $e->id)?>"
                               class="btn btn-sm btn-primary preventDoubleRequest"><i class="icon-edit icon-white"></i>
                                <?php echo Auth::user()->belongsToGroup('Asignador') ? "Asignar" : "Realizar"; ?></a>
                            <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
                            <?php if ($file): ?>
                            <a href="#" onclick="return descargarDocumentos(<?=$e->Tramite->id?>);"
                               class="btn btn btn-sm btn-success"><i
                                        class="icon-download icon-white"></i> Descargar</a>
                            <?php endif; ?>
                            <?php endif; ?>
                            @if(Auth::check() && Auth::user()->open_id && !is_null($e->Tarea->Proceso->eliminar_tramites) && $e->Tarea->Proceso->eliminar_tramites)
                                <a href="#" onclick="return eliminarTramite(<?=$e->Tramite->id?>);"
                                   class="btn btn-sm btn-danger preventDoubleRequest"><i class="icon-edit icon-red"></i>
                                    Borrar</a>
                        @endif
                        <!--<?php if($e->netapas == 1):?><a href="<?=url('tramites/eliminar/' . $e->tramite_id)?>" class="btn" onclick="return confirm('¿Esta seguro que desea eliminar este tramite?')"><i class="icon-trash"></i></a><?php endif ?>-->
                        </td>
                        <!-- Acciones -->
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
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
            <?php endif; ?>
            <?php endif; ?>
            <p><?= $etapas->links('vendor.pagination.bootstrap-4') ?></p>
            <?php else: ?>
            <p>No hay trámites pendientes en su bandeja de entrada.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal hide in" id="modal"></div>

@endsection

@push('script')
    <script>
        function descargarDocumentos(tramiteId) {
            $("#modal").load("/etapas/descargar/" + tramiteId);
            $("#modal").modal();
            $("#modal").css('display', 'block');

            $(".closeModal").click(function () {
                closeModal();
            });

            $(".modal-backdrop").click(function () {
                closeModal();
            });

            $(".modal-backdrop").click(function () {
                closeModal();
            });

            return false;
        }

        $(document).ready(function () {
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

            $('#mainTable').DataTable({
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
                            columns: [ 1, 2, 3, 4, 5 ]
                        }
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5 ]
                        }
                    } ),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5 ]
                        }
                    } )
                ]
            });
        });

        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
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

        function closeModal() {
            $("#modal").removeClass("in");
            $(".modal-backdrop").remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
            $("#modal").hide();
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
                console.log("descargarSeleccionados.modal");
                return false;
            }
        }

        function eliminarTramite(tramiteId) {
            $("#modal").load("/tramites/eliminar/" + tramiteId);
            $("#modal").modal();
            $("#modal").css('display', 'block');

            $(".closeModal").click(function () {
                closeModal();
                console.log("test1");
            });

            $(".modal-backdrop").click(function () {
                closeModal();
                console.log("test2");
            });

            $(".modal-backdrop").click(function () {
                closeModal();
                console.log("test3");
            });

            return false;
        }
    </script>
@endpush

