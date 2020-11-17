<?php

require_once 'widget.php';

use App\Helpers\Doctrine;

class WidgetEstadoTramitesComuna extends Widget
{
    private $javascript;

    public function display()
    {
        if (!$this->comuna) {
            $display = '<p>Widget requiere configuración</p>';
            return $display;
        }

        $comuna = $this->comuna;

        $stmn = Doctrine_Manager::getInstance()->connection();
	$tmp = $stmn->execute("
		SELECT CONCAT(estados_tramites.fase,'/',estados_tramites.estado) as estado,count(*) as cantidad
FROM tarea,estados_tramites,
(SELECT tarea_id FROM etapa,
(SELECT json_extract(valor, '$.comuna') as comuna,ultima_etapa.id, ultima_etapa.created_at
FROM (SELECT id, MAX(created_at) AS created_at FROM etapa GROUP BY tramite_id ORDER BY created_at DESC) as ultima_etapa, dato_seguimiento
WHERE dato_seguimiento.etapa_id = ultima_etapa.id
AND json_extract(valor, '$.comuna') != 'NULL'
AND nombre = 'comunasfact'
GROUP BY id) AS etapa_con_comuna
WHERE etapa.created_at = etapa_con_comuna.created_at
AND comuna = ?
AND YEAR(etapa.created_at) = 2020) AS etapa
WHERE tarea.id = etapa.tarea_id
AND estados_tramites.nombre = tarea.nombre
GROUP BY estados_tramites.estado
ORDER BY cantidad desc
		", array( $comuna ))
            ->fetchAll();

        $datos = array();
        foreach ($tmp as $t){
            $datos[] = array($t['estado'], (float)$t['cantidad']);
        }

        $datos = json_encode($datos);

        $display = '<div class="grafico"></div>';
        $this->javascript = '
        <script type="text/javascript">
            $(document).ready(function(){
                new Highcharts.Chart({
                    chart: {
                        renderTo: $(".widget[data-id=' . $this->id . '] .grafico")[0],
                        type: "pie"
                    },
                    title: {
                        text: "Estado de trámites de ' . $this->comuna . ' "
                    },
                    tooltip: {
                        pointFormat: "{point.y} trámites: <b>{point.percentage:.1f}%</b>"
                    },
                    series: [{
                            data: ' . $datos . '
                        }]
                });
            });
        </script>';

        echo $this->javascript;

        return $display;
    }

    public function displayForm()
    {
        $tarea_id = $this->config ? $this->config->tarea_id : null;
        $comunabd = $this->comuna;

        // date filter
        $display ='<div class="container-fluid"><div class="row">';

        $display .= '<div class="form-group col-md-12">
            <div class="campo control-group" data-id="10" data-dependiente-campo="dependiente" style="display: block;">
                <label class="control-label">Region/Comuna';

        if ($comunabd) {
            $display .= " (comuna actual: {$comunabd})";
        }

        $display .=        '</label>
                <div class="controls">
                    <select class="form-control" id="regiones_10" data-id="10" name="regiones_10" style="width:100%">
                        <option value="">Seleccione Regi&oacute;n</option>
                    </select><br />

                    <select class="form-control" id="comunas_10" data-id="10" name="comunas_10" style="width:100%">
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
                                    comunas_obj.append("';
        $display .=                     "<option data-id='-1' value=''>Seleccione Comuna</option>";
        $display .=                 '");

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
        </div>';

        $display .= "</div>";
        $display .= "</div>";

        return $display;
    }


    public function getJavascript()
    {
        return $this->javascript;
    }

    public function validateForm()
    {

/*        $request->validate(['config.tarea_id' => 'required']);*/
        $CI = &get_instance();
        $CI->form_validation->set_rules('config[tarea_id]', 'Tarea', 'required');
    }

}

