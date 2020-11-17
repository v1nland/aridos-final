@extends('layouts.procedure')
@section('content')
<script>
geomenable = false;
</script>
   <form id="ronly" class="form-horizontal dynaForm" onsubmit="return false;">
        <fieldset>
            <div class="validacion"></div>
            <legend>
                <?php $t = $etapa->Tramite; ?>
                <!-- <?= $paso->Formulario->nombre ?> -->
                <?= $t ? \App\Helpers\Doctrine::getTable('Etapa')->makeIDRegionByRegion($t->id, \App\Helpers\Doctrine::getTable('Etapa')->idByRegion($t->id)) : '' ?>
                <?= $etapa->Tramite ? '<span class="badge badge-pill badge-' . $etapa->Tramite->getBadgeColor($etapa->Tramite->getUltimaEtapaReal()) . '">' . $etapa->Tramite->getUltimaEtapaReal() . '</span>' : '' ?>
            </legend>

            <?php
                $form = "
                    <form action='/bitacoras/agregar/{$etapa->Tramite->id}/participados' method='POST'>
                        " . csrf_field() . "
                        <div class='form-group'>
                            <textarea class='form-control' name='contenido' id='{{$etapa->Tramite->id}}' placeholder='Ingresa la bitácora' rows='5'></textarea>
                            <input class='form-control' type='hidden' name='escritor' id='escritor' value='" . Auth::user()->nombres . "'></input>
                        </div>
                        <center><button type='submit' class='btn btn-primary'>Agregar</button></center>
                    </form>
                ";
            ?>

            <!-- Agregar IF para que sea solo en etapa inicial -->
            @if(!Auth::user()->belongsToGroup("Usuario Municipal"))
                <a href="#" data-toggle="popover" title="Agregar bitácora" data-html="true" data-content="{{$form}}" >
                    Agregar Bitácora
                </a>

                <br>
            @endif

            @foreach ($paso->Formulario->Campos as $c)
                <?php $condicion_final = ""; ?>
                @if($c->condiciones_extra_visible)
                    @foreach($c->condiciones_extra_visible as $condicion)
                        <?php
                            $condicion_final .= $condicion->campo.";".$condicion->igualdad.";".$condicion->valor.";".$condicion->tipo."&&";
                        ?>
                    @endforeach
                @endif
                <?php
                    if(!is_null($c->dependiente_campo) && !is_null($c->dependiente_valor)){
                        $condicion_final = $c->dependiente_campo.";".$c->dependiente_relacion.";".$c->dependiente_valor.";".$c->dependiente_tipo."&&".$condicion_final;
                    }
                    $condicion_final = substr($condicion_final,0,-2);
                ?>
                <div class="campo control-group" data-id="<?=$c->id?>"
                     <?= $c->dependiente_campo ? 'data-dependiente-campo="' . $c->dependiente_campo . '" data-dependiente-valor="' . $c->dependiente_valor . '" data-dependiente-tipo="' . $c->dependiente_tipo . '" data-dependiente-relacion="' . $c->dependiente_relacion . '"' : 'data-dependiente-campo="dependiente"' ?> style="display: <?= $c->isCurrentlyVisible($etapa->id) ? 'block' : 'none'?>;"
                     data-readonly="{{$paso->modo == 'visualizacion' || $c->readonly}}" <?=$c->condiciones_extra_visible ? 'data-condicion="' . $condicion_final . '"' : 'data-condicion="no-condition"'  ?> >
                    <?=$c->displayConDatoSeguimiento($etapa->id, $paso->modo)?>
                </div>
            @endforeach
	    <div class="form-actions">
		
                @if ($secuencia > 0)
                    <a class="btn btn-light" href="<?= url('etapas/ver/' . $etapa->id . '/' . ($secuencia - 1)) ?>">
                        <i class="material-icons align-middle">chevron_left</i> Volver
                    </a>
		@endif
		    <a class="btn btn-light" href="<?= url('/')?>">
                        Inicio
                    </a>
                @if ($secuencia + 1 < count($etapa->getPasosEjecutables()))
                    <a class="btn btn-primary" href="<?= url('etapas/ver/' . $etapa->id . '/' . ($secuencia + 1)) ?>">
                        Siguiente
                    </a>
                @endif
		@if ($secuencia + 1 == count($etapa->getPasosEjecutables()))
                    <a class="btn btn-primary" href="<?= url('/') ?>">
                        Terminar
                    </a>
                @endif
	    </div>
        </fieldset>
    </form>
@endsection
@push('script')
    <script src="<?= asset('/calendar/js/moment-2.2.1.js') ?>"></script>
    <script>
        $(document).ready(function(){
            $("#ronly :input").prop("disabled", true);
        });

        $(function () {
            moment.lang('es');
            $.each($('.js-data-cita'), function () {
                if ($(this).is('[readonly]')) {
                    var id = $(this).attr('id');
                    var arrdat = $(this).val().split('_');
                    var d = new Date(arrdat[1]);
                    var h = '';
                    if (d.getHours() <= 9) {
                        h = '0' + d.getHours();
                    } else {
                        h = d.getHours();
                    }
                    var m = '';
                    if (d.getMinutes() <= 9) {
                        m = '0' + d.getMinutes();
                    } else {
                        m = d.getMinutes();
                    }
                    var fecha = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + h + ':' + m;

                    var lab = moment(d.getFullYear() + '/' + (d.getMonth() + 1) + '/' + d.getDate()).format("LL");
                    $('#txtresult' + id).html(lab + ' a las ' + h + ':' + m + " horas");
                }
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
    </script>
    <script src="{{asset('js/helpers/common.js')}}"></script>
@endpush

