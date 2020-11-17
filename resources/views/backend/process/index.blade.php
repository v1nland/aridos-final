@extends('layouts.backend')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Listado de Procesos</li>
                    </ol>
                </nav>
            </div>
        </div>

        <a class="btn btn-success" href="{{route('backend.procesos.create')}}">
            <i class="material-icons">add</i> Nuevo
        </a>

        <a class="btn btn-light" href="#modalImportar" data-toggle="modal">
            <i class="material-icons">file_upload</i> Importar
        </a>

        <br><br>

        <table class="table">
            <thead>
            <tr>
                <th>Proceso</th>
                <th>Acciones
                    <a href="/ayuda/simple/backend/export-import.html" target="_blank">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </a>
                </th>
                <th>Usuario creador</th>
                <th>Fecha creación</th>
            </tr>
            </thead>
            <tbody>
            @foreach($procesos as $p)
                <tr>
                    <td>{{$p->nombre}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{route('backend.procesos.edit', [$p->id])}}">
                                <i class="material-icons">edit</i> Editar</a>
                        <a class="btn btn-light" href="{{route('backend.procesos.export', [$p->id])}}">
                            <i class="material-icons">file_download</i> Exportar</a>
                        <a class="btn btn-danger" href="#" onclick="return eliminarProceso({{$p->id}});">
                            <i class="material-icons">delete</i> Eliminar
                        </a>
                        
                    </td>
                    @if(!is_null($p->usuario_id))
                    <?php
                        $usuario_backend = App\Models\UsuarioBackend::find($p->usuario_id);
                    ?>
                    <td>{{$usuario_backend->nombre." ".$usuario_backend->apellidos}}</td>
                    @if(is_null($p->created_at))
                        <td></td>
                    @else
                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$p->created_at)->format('d-m-Y H:i:s')}}</td>
                    @endif
                    @else
                    <td colspan="2"></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (sizeof($procesos_eliminados) > 0)
            <a href="#" id="link_eliminados" onclick="return mostrarEliminados();">Mostrar Eliminados »</a>
            <div class="procesos_eliminados">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Procesos Eliminados</th>
                        <th>Acciones</th>
                        <th>Usuario creador</th>
                        <th>Fecha creación</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($procesos_eliminados as $pe)
                        <tr>
                            <td><?=$pe->nombre?></td>
                            <td>
                                <a class="btn btn-primary" href="#" onclick="return activarProceso(<?=$pe->id?>);">
                                    <i class="material-icons">share</i> Activar
                                </a>
                            </td>
                            @if(!is_null($pe->usuario_id))
                            <?php
                                $usuario_backend = App\Models\UsuarioBackend::find($pe->usuario_id);
                            ?>
                            <td>{{$usuario_backend->nombre." ".$usuario_backend->apellidos}}</td>
                            @if(is_null($pe->created_at))
                                <td></td>
                            @else
                                <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$pe->created_at)->format('d-m-Y H:i:s')}}</td>
                            @endif
                            @else
                            <td colspan="2"></td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal Importar -->
    <div class="modal fade" id="modalImportar" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" enctype="multipart/form-data" action="{{route('backend.procesos.import')}}">
                {{csrf_field()}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Importar Proceso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Cargue a continuación el archivo .simple donde exportó su proceso.</p>
                        <input type="file" class="form-control" name="archivo"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Importar -->
    <div id="modal" class="modal" tabindex="-1" role="dialog"></div>

@endsection
@section('script')
    <script>
        function eliminarProceso(procesoId) {
            $("#modal").load("/backend/procesos/ajax_auditar_eliminar_proceso/" + procesoId);
            $("#modal").modal();
            return false;
        }

        function activarProceso(procesoId) {
            $("#modal").load("/backend/procesos/ajax_auditar_activar_proceso/" + procesoId);
            $("#modal").modal();
            return false;
        }

        function mostrarEliminados() {
            $(".procesos_eliminados").slideToggle('slow', callbackEliminadosFn);
            return false;
        }

        function callbackEliminadosFn() {
            var $link = $("#link_eliminados");
            $(this).is(":visible") ? $link.text("Ocultar Eliminados «") : $link.text("Mostrar Eliminados »");
        }

        function publicarProceso(procesoId) {
            $("#modal").load("/backend/procesos/ajax_publicar_proceso/" + procesoId);
            $("#modal").modal();
            return false;
        }

        function editarProceso(procesoId) {
            $("#modal").load("/backend/procesos/ajax_editar_proceso/" + procesoId);
            $("#modal").modal();
            return false;
        }
    </script>
@endsection