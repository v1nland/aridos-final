<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.ga')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{  \Cuenta::seo_tags()->title }}</title>
    <meta name="description" content="{{ \Cuenta::seo_tags()->description }}">
    <meta name="keywords" content="{{ \Cuenta::seo_tags()->keywords }}">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.css"/>
    <link href="{{ asset('css/'.$estilo.'') }} " rel="stylesheet">
    <!-- <link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"> -->
    <!-- <link href="//cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet"> -->

    <meta name="google" content="notranslate"/>

    <link rel="shortcut icon" href="{{ asset(\Cuenta::getAccountFavicon()) }}">
    <link href="{{ asset('css/component-chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @yield('css')
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_KEY') ?>&libraries=places&language=ES"></script> -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
        var site_url = "";
        var base_url = "";

        var onloadCallback = function () {
            if ($('#form_captcha').length) {
                grecaptcha.render("form_captcha", {
                    sitekey: "{{env('RECAPTCHA_SITE_KEY')}}"
                });
            }
        };

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    </script>
     <style type="text/css">{{ $personalizacion }}</style>
</head>
<body class="h-100">
<div id="app" class="h-100 d-flex flex-column" >
    @include($dominio_header)

    <div class="main-container container pb-5">
        <div class="row">
            <div class="col-xs-12 col-md-3">

                <ul class="simple-list-menu list-group d-none d-sm-block">
                    @if(!Auth::user()->belongsToGroup("Coordinador Regional") && !Auth::user()->belongsToGroup("Analista DOH-DGA"))
                        <a class="list-group-item list-group-item-action  {{isset($sidebar) && $sidebar == 'disponibles' ? 'active' : ''}}"
                           href="{{route('home')}}">
                            <i class="material-icons">insert_drive_file</i> Menú tramites
                        </a>
                    @endif

                    @if(Auth::user()->registrado)
                        @php
                            $npendientes = \App\Helpers\Doctrine::getTable('Etapa')->findPendientes(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
                            $ntramitespendientes = \App\Helpers\Doctrine::getTable('Tramite')->findPendientesALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
                            $nsinasignar = \App\Helpers\Doctrine::getTable('Etapa')->countPendingTramitesByRegion(array_map('strtoupper', Auth::user()->arr_grupos_usuario()), Auth::user()->id, Cuenta::cuentaSegunDominio());
                            $nparticipados = \App\Helpers\Doctrine::getTable('Tramite')->findParticipadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
                            $nhistorial = \App\Helpers\Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio())->count();
			    $ncompletados = \App\Helpers\Doctrine::getTable('Tramite')->findCompletadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
				$nparticipados = 0;				
//				$tramites = \App\Helpers\Doctrine::getTable('Tramite')->findParticipados(Auth::user()->id, Cuenta::cuentaSegunDominio(), 0, 0, '0', ''); 
//				$tramites = \App\Helpers\Doctrine::getTable('Tramite')->findParticipadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio());
				$tramites = \App\Helpers\Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), 0, 0, '0', "");
				$grp_array = array_map('strtoupper', Auth::user()->arr_grupos_usuario());

				foreach ($tramites as $t){
					$reg_proy = \App\Helpers\Doctrine::getTable('Etapa')->getRegion($t->id);
					if ( in_array( strtoupper($reg_proy), $grp_array ) ){
						$nparticipados = $nparticipados + 1;
					}
				}
                        @endphp

                        <!-- ejemplo solo de municipio -->
                        @if(Auth::user()->belongsToGroup("Municipio"))

                        @endif
                        <!-- end ejemplo solo de municipio -->

			@if(!Auth::user()->belongsToGroup("Analista DOH-DGA") && !Auth::user()->belongsToGroup("Coordinador Regional"))
        	                    @if ($nsinasignar)
                	                <a class="list-group-item list-group-item-action {{ isset($sidebar) && $sidebar == 'sinasignar' ? 'active' : '' }}"
                        	           href="{{route('stage.unassigned')}}">
                                	    <i class="material-icons">assignment</i> Solicitudes en trámite ({{$nsinasignar}})
                               		 </a>
                           	 @endif
                        @endif

			@if(Auth::user()->belongsToGroup("Coordinador Regional"))
                            @if ($nsinasignar)
                                <a class="list-group-item list-group-item-action {{ isset($sidebar) && $sidebar == 'sinasignar' ? 'active' : '' }}"
                                   href="{{route('stage.unassigned')}}">
                                    <i class="material-icons">assignment</i> Solicitudes en trámite ({{$nsinasignar}})
                                </a>
                            @endif
                        @endif

                        <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'inbox' ? 'active' : ''}}"
                           href="{{route('stage.inbox')}}">
                            @if(Auth::user()->belongsToGroup("Coordinador Regional"))
                                <i class="material-icons">assignment_ind</i> Solicitudes en proceso de asignación ({{$npendientes}})
                            @else
                                <i class="material-icons">assignment_ind</i> Solicitudes pendientes ({{$npendientes}})
                            @endif
                        </a>

                        @if(Auth::user()->belongsToGroup("Coordinador Regional"))
                            <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'pendientes' ? 'active' : ''}}"
                               href="{{route('tramites.pendientes')}}">
                                <i class="material-icons">assignment_turned_in</i> Solicitudes asignadas a revisores ({{$ntramitespendientes}})
			    </a>
			@endif
			
                            <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'completados' ? 'active' : ''}}"
                               href="{{route('tramites.completados')}}">
                                <i class="material-icons">assignment_turned_in</i> Solicitudes terminadas ({{$ncompletados}})
                            </a>

                        <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'estadisticas' ? 'active' : ''}}"
                           href="{{route('tramites.estadisticas')}}">
                            <i class="material-icons">insert_chart</i> Estadísticas
                        </a>


                        <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'buscador' ? 'active' : ''}}"
                           href="{{route('tramites.buscador')}}">
                            <i class="material-icons">search</i> Buscar
                        </a>

                        <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'buscador_mapa' ? 'active' : ''}}"
                           href="{{route('tramites.buscador_mapa')}}">
                            <i class="material-icons">search</i> Buscar por mapa
                        </a>

                        <a class="list-group-item list-group-item-action {{isset($sidebar) && $sidebar == 'participados' ? 'active' : ''}}"
                           href="{{route('tramites.participados')}}">
                            <i class="material-icons">history</i> Estado de solicitudes ({{$nparticipados}})
                        </a>

                        <!-- <a class="list-group-item list-group-item-action {{isset($sidebar) && strstr($sidebar, 'miagenda') ? 'active' : ''}}"
                           href="{{route('agenda.miagenda')}}">
                            <i class="material-icons">date_range</i> Mi Agenda
                        </a> -->
                    @endif
                </ul>
            </div>

            <div class="col-xs-12 col-md-9">
                @include('components.messages')
                @yield('content')
                {!! isset($content) ? $content : '' !!}
            </div>

        </div>
    </div>
    @include($dominio_footer, ['metadata' => $metadata_footer])
</div>

@stack('script')

<!-- Scripts -->
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=es"></script>
<script src="{{ asset('js/helpers/grilla_datos_externos.js') }}"></script>
<script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/datatables.min.js"></script>

<!-- Charts scripts -->
<script>
    $(document).ready(function () {
        $("#dashboard").sortable({
            items: ".widget",
            handle: ".cabecera",
            revert: true,
            stop: widgetChangePositions
        });
    });

    function widgetChangePositions() {
        var widgets = new Array();
        $("#dashboard .widget").each(function (i, e) {
            widgets.push($(e).data('id'));
        });
        var json = JSON.stringify(widgets);

        $.post("/backend/gestion/widget_change_positions/", "posiciones=" + json);
    }

    function widgetConfig(button) {
        var widget = $(button).closest(".widget");
        $(widget).addClass('flip');
        return false;
    }

    function widgetConfigOk(form) {
        var widget = $(form).closest(".widget");
        var widgetId = $(widget).data("id");
        $(widget).removeClass('flip');

        //Damos tiempo para que termine la animacion
        setTimeout(function () {
            $(widget).load("/backend/gestion/widget_load/" + widgetId)
        }, 1000);
    }
</script>

<script>
    $(function () {
        $(document).ready(function(){
            $('#cierreSesion').click(function (){
                $.ajax({ url: 'https://api.claveunica.gob.cl/api/v1/accounts/app/logout', dataType: 'script' }) .always(function() {
                    window.location.href = '/logout';
                });
            });
        });
    });
</script>
</body>
</html>

