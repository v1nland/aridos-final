<div class="row">
    <div class="col-xs-12 col-md-12">
        <h1 class="title">Solicitudes asignadas a revisores</h1>
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
            <?php foreach ($tramites as $t): ?>
            <?php if ($t->pendiente): ?>
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
                    <div class="checkbox"><label><input type="checkbox" class="checkbox1" name="select[]" value="<?=$t->id?>"></label></div>
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

                <!-- region/comuna -->
                @php
                    $fetchrc = \App\Helpers\Doctrine::getTable('Etapa')->getRegionComuna($t->id, Cuenta::cuentaSegunDominio());
                @endphp
                <td>
                    <?php echo $fetchrc; ?>
                </td>
                <!-- region/comuna -->

                <!-- Fecha de ingreso -->
                <td class="time">
                    <?= strftime('%d.%b.%Y', mysql_to_unix($t->created_at)) ?>
                    <br/>
                    <?= strftime('%H:%M:%S', mysql_to_unix($t->created_at)) ?>
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
                    @if(!Auth::user()->belongsToGroup("Usuario Municipal"))
                        <a href="#" data-toggle="popover" title="Agregar bitácora" data-html="true" data-content="{{$form}}" >
                            (+)Bitácora
                        </a>

                        <br>
                    @endif

                    <a href="<?= '/bitacoras/visualizar/' . $t->id?>">
                        Ver bitácora
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
                <!-- Acciones (historial, agregar bitácora) -->
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

@push('script')
    <script>
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
            }).on('click', function(e) {e.preventDefault(); return true;});
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

