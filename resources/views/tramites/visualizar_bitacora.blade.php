@section('content')
    <div class="row">
        <div class="col">
            <h1 class="title">Bitácora {{ ( \App\Helpers\Doctrine::getTable('Etapa')->makeIDRegionByRegion($tramite_id, \App\Helpers\Doctrine::getTable('Etapa')->idByRegion($tramite_id)) ) }}</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            @if(!is_null($bitacora))
                <div class="table-responsive">
                    <table id="mainTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Escritor</th>
                                <th>Bitácora</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($bitacora as $b => $v): ?>
                                <tr>
                                    <td><?= strftime('%d.%b.%Y', mysql_to_unix($v['fecha'])) . ', ' . strftime('%H:%M:%S', mysql_to_unix($v['fecha'])) ?></td>
                                    <td><?= $v['escritor'] ?></td>
                                    <td><?= $v['content'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
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
                "url": "/js/helpers/spanish_lang.json"
            },
            dom: 'Bfrtip',
            buttons: [
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
    });
</script>
@endpush

