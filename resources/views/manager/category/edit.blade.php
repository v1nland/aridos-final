<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('manager/categorias') ?>">Categorias</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
    </ol>
</nav>

<form class="ajaxForm" method="post" action="<?= url('manager/categorias/editar_form/' . $categoria->id) ?>">
    {{csrf_field()}}
    <fieldset>
        <legend><?= $title ?></legend>
        <div class="validacion"></div>

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= $categoria->nombre ?>" class="form-control col-3"/>

        <label>Descripción</label>
        <input type="text" name="descripcion" value="<?= $categoria->descripcion ?>" class="form-control col-3"/>

        <label>Icono</label>
        <div id="file-uploader"></div>
        <?php if($categoria->icon_ref):?>
        <input type="hidden" name="logo" value="<?= $categoria->icon_ref ?>"/>
        <img class="logo" src="<?= asset('uploads/logos/' . $categoria->icon_ref)?>" alt="logo" style="max-width: 10%"/>
        <?php else:?>
        <input type="hidden" name="logo" value="nologo.png"/>
        <i class="icon-archivo"></i>
        <?php endif ?>

    </fieldset>

    <script src="{{asset('js/helpers/fileuploader.js')}}"></script>
    <script>
        var uploader = new qq.FileUploader({
            params: {_token: '{{csrf_token()}}'},
            element: document.getElementById('file-uploader'),
            action: '{{route('manager.uploader.logo')}}',
            onComplete: function (id, filename, respuesta) {
                $("input[name=logo]").val(respuesta.file_name);
                $("img.logo").attr("src", "/uploads/logos/" + respuesta.file_name);
            }
        });
    </script>
    </br></br>
    <div class="form-actions">
        <button class="btn btn-primary" type="submit">Guardar</button>
        <a class="btn btn-light" href="<?= url('manager/categorias') ?>">Cancelar</a>
    </div>
</form>