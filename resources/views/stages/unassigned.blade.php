<div class="row">
    <div class="col-xs-12 col-md-12">
        <h1 class="title">Etapas sin asignar</h1>
        <hr>
    </div>
</div>

<div class="col-xs-12 col-md-12">
    <?php if (count($etapas) > 0): ?>
        <div class="table-responsive">
    <table id="mainTable" class="table">
        <thead>
            <tr>
                <th></th>
                <th>Código</th>
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
            <?php $t = \App\Helpers\Doctrine::getTable('Tramite')->find($e->id); ?>

            <?php $reg_proy = \App\Helpers\Doctrine::getTable('Etapa')->getRegion($t->id); ?>
            <?php $asignador_array = array_map('strtoupper', Auth::user()->arr_grupos_usuario()); ?>

            <!-- Este IF permite solo desplegar los correspondientes a la región del asignador -->
            <?php if ( in_array( strtoupper($reg_proy), $asignador_array ) ): ?>

            <?php
            $file = false;
            if (\App\Helpers\Doctrine::getTable('File')->findByTramiteId($e->id)->count() > 0) {
                $file = true;
                $registros = true;
            }
            ?>
            <?php
            $previsualizacion = '';
            if ( ! empty($e->previsualizacion)){
                $r = new Regla($e->previsualizacion);
                $previsualizacion = $r->getExpresionParaOutput($e->etapa_id);
            }

            ?>
            <tr <?=$previsualizacion ? 'data-toggle="popover" data-html="true" data-title="<h4>Previsualización</h4>" data-content="' . htmlspecialchars($previsualizacion) . '" data-trigger="hover" data-placement="bottom"' : ''?>>
                <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
                <?php if ($file): ?>
                <td>
                    <div class="checkbox"><label><input type="checkbox" class="checkbox1" name="select[]" value="<?=$e->id?>"></label></div>
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
                    <!-- sistema de autoasignacion -->
		    <a href="<?=url('etapas/asignar/' . $e->etapa_id)?>" class="btn btn-sm btn-primary preventDoubleRequest"><i class="icon-check icon-white"></i><?= Auth::user()->belongsToGroup("Coordinador Regional")?"Asignar":"Realizar" ?></a>
                    <!-- para asignar a alguien especifico ===> etapas/asignar/etapa_id/usuario_id  -->

                    <?php if (Cuenta::cuentaSegunDominio()->descarga_masiva): ?>
                    <?php if ($file): ?>
                    <a href="#" onclick="return descargarDocumentos(<?=$e->id?>);" class="btn btn btn-sm btn-success"><i class="icon-download icon-white"></i> Descargar</a>
                    <?php endif; ?>
                    <?php endif; ?>
                </td>
                <!-- Acciones -->
            </tr>
            <?php endif; ?>
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
                <a href="#" onclick="return descargarSeleccionados();" class="btn btn-success preventDoubleRequest">
                    <i class="icon-download icon-white"></i> Descargar seleccionados
                </a>
            </label>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php else: ?>
    <p>No hay trámites para ser asignados.</p>
    <?php endif; ?>
</div>
<div class="modal hide" id="modal"></div>

@push('script')
    <script>
        function descargarDocumentos(tramiteId) {
            $("#modal").load("/etapas/descargar/" + tramiteId);
            $("#modal").modal();
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
                    "url": "/js/helpers/spanish_lang.json"
                },
                dom: 'Bfrtip',
                buttons: [
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
                $("#modal").load("/etapas/descargar/" + vtramites);
                $("#modal").modal();
                return false;
            }
        }
    </script>
@endpush

