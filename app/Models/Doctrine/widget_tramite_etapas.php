<?php
require_once 'widget.php';

use App\Helpers\Doctrine;
use Illuminate\Support\Facades\View;

class WidgetTramiteEtapas extends Widget
{
    public $javascript;

    public function display()
    {
        if (!$this->config) {
            $display = '<p>Widget requiere configuración</p>';
            return $display;
        }

        $proceso = Doctrine::getTable('Proceso')->find($this->config->proceso_id);
        if (!$proceso) {
            $display = '<p>Widget requiere configuración</p>';
            return $display;
        }

        $tmp = Doctrine_Query::create()
            ->select('tar.id, tar.nombre, COUNT(tar.id) as cantidad')
            ->from('Tarea tar, tar.Etapas e, e.Tramite t, t.Proceso p, p.Cuenta c')
            ->where('p.activo=1 AND p.id = ? AND c.id = ?', array($proceso->id, $this->cuenta_id))
            ->andWhere('e.pendiente = 1')
            ->andWhere('t.deleted_at is NULL')
            ->andWhere('t.created_at BETWEEN ? and ?', array($this->anomin, $this->anomax) )
            //->having('COUNT(d.id) > 0 OR COUNT(e.id) > 1')  //Mostramos solo los que se han avanzado o tienen datos
            ->groupBy('tar.id')
            ->execute();

        // echo $tmp;

        $datos = array();
        foreach ($tmp as $t)
            $datos[] = array($t->nombre, (float)$t->cantidad);

        $datos = json_encode($datos);

        // echo $datos;

        $display = '<div class="grafico"></div>';

        // echo $display;

        $this->javascript = '
        <script type="text/javascript">
            $(document).ready(function(){
                new Highcharts.Chart({
                    chart: {
                        renderTo: $(".widget[data-id=' . $this->id . '] .grafico")[0],
                        type: "pie"
                    },
                    title: {
                        text: "' . $proceso->nombre . '"
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

    public function getJavascript()
    {
        return $this->javascript;
    }

    public function displayForm()
    {
        $proceso_id = $this->config ? $this->config->proceso_id : null;
        $anominbd = $this->anomin;
        $anomaxbd = $this->anomax;


        $display = '<label>Proceso</label>';
        $procesos = $this->Cuenta->getProcesosActivos();//Procesos;

        $display .= '<select name="config[proceso_id]" class="form-control">';
        foreach ($procesos as $p)
            $display .= '<option value="' . $p->id . '" ' . ($proceso_id == $p->id ? 'selected' : '') . '>' . $p->nombre . '</option>';
        $display .= '</select>';

        // date filter
        $display .='<div class="container-fluid"><div class="row">';

        $display .= '<div class="form-group col-md-6">
            <label>Desde:</label>
            <select class="form-control" name="anomin" id="anomin">
                <option value="2000">Seleccione inicio</option>';

        $start_year = 2018;
        $end_year = 2038;

        while ($start_year <= $end_year) {
            $formattedsy = strval($start_year) . '-01-01';
            $display .= "<option value='" . $start_year . "-01-01' " . ($anominbd == $formattedsy ? 'selected' : '') . ">" . $start_year . "</option>";
            $start_year++;
        }
        $display .= "</select></div>";

        $display .= '<div class="form-group col-md-6">
            <label>Hasta:</label>
            <select class="form-control" name="anomax" id="anomax">
                <option value="5000">Seleccione fin</option>';

        $start_year = 2019;
        $end_year = 2038;

        while ($start_year <= $end_year) {
            $formattedsy = strval($start_year) . '-01-01';
            $display .= "<option value='" . $start_year . "-01-01' " . ($anomaxbd == $formattedsy ? 'selected' : '') . ">" . $start_year . "</option>";
            $start_year++;
        }

        $display .= "</select></div></div></div>";

        return $display;
    }

    public function validateForm()
    {
        $CI =& get_instance();
        $CI->form_validation->set_rules('config[proceso_id]', 'Proceso', 'required');
    }
}

