<?php

require_once 'widget.php';

use App\Helpers\Doctrine;

class WidgetEstadoTramites extends Widget
{
    private $javascript;

    public function display()
    {
        if (!$this->anomin) {
            $display = '<p>Widget requiere configuración</p>';
            return $display;
        }

        $anoterm = $this->anomin;

        $stmn = Doctrine_Manager::getInstance()->connection();
        $tmp = $stmn->execute('
SELECT CONCAT(estados_tramites.fase,"/",estados_tramites.estado) as fase_estado,count(*) as cantidad
FROM tarea,estados_tramites,
(SELECT tarea_id FROM etapa,
(SELECT MAX(created_at) AS created_at FROM etapa GROUP BY tramite_id ORDER BY created_at DESC) AS ultima_etapa
WHERE etapa.created_at = ultima_etapa.created_at
AND YEAR(etapa.created_at) = ?) AS etapa
WHERE tarea.id = etapa.tarea_id
AND estados_tramites.nombre = tarea.nombre
GROUP BY estados_tramites.estado
ORDER BY cantidad desc;
', array( substr($anoterm, 0, 4)))
            ->fetchAll();

        $datos = array();
        foreach ($tmp as $t){
            $datos[] = array($t['fase_estado'], (float)$t['cantidad']);
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
                        text: "Estado de trámites (' . substr($anoterm, 0, 4) . ')"
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
        $anominbd = $this->anomin;

        $procesos = Doctrine_Query::create()
            ->from('Proceso p, p.Tareas t')
            ->where('p.activo=1 AND p.cuenta_id = ?', $this->Cuenta->id)
            ->andWhere('t.acceso_modo = ?', 'grupos_usuarios')
            ->execute();

        if (!$procesos->count())
            return '<p>No se puede utilizar este widget ya que no tiene tareas asignadas a grupos de usuarios.</p>';

        // date filter
        $display ='<div class="container-fluid"><div class="row">';

        $display .= '<div class="form-group col-md-12">
            <label>Seleccione año de término de trámite:</label>
            <select class="form-control" name="anomin" id="anomin">
                <option value="2000">Seleccione año</option>';

        $start_year = 2018;
        $end_year = 2038;

        while ($start_year <= $end_year) {
            $formattedsy = strval($start_year) . '-01-01';
            $display .= "<option value='" . $start_year . "-01-01' " . ($anominbd == $formattedsy ? 'selected' : '') . ">" . $start_year . "</option>";
            $start_year++;
        }
        $display .= "</select></div></div></div>";

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

