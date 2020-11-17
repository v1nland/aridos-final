<?php
require_once 'widget.php';

use App\Helpers\Doctrine;

class WidgetTramitesCantidad extends Widget
{
    private $javascript;

    public function display()
    {
        if (!$this->config) {
            $display = '<p>Widget requiere configuración</p>';
            return $display;
        }

        $datos = array();

        foreach ($this->config->procesos as $proceso_id) {
            $p = Doctrine::getTable('Proceso')->find($proceso_id);
            if ($p) {
                $pid = (int) $p->id;
                $cid = (int) $this->cuenta_id;

                $conteo = DB::table('tramite')
                    ->select('tramite.id')
                    ->leftJoin('etapa', 'etapa.tramite_id', '=', 'tramite.id')
                    ->leftJoin('dato_seguimiento', 'dato_seguimiento.etapa_id', '=', 'etapa.id')
                    ->leftJoin('proceso', 'tramite.proceso_id', '=', 'proceso.id')
                    ->leftJoin('cuenta', 'proceso.cuenta_id', '=', 'cuenta.id')
                    ->whereBetween('tramite.created_at', array($this->anomin, $this->anomax) )
                    ->where('tramite.pendiente',1)
                    ->where('cuenta.id',$cid)
                    ->where('proceso.id',$pid)
                    ->where('proceso.activo',1)
                    ->whereNull('tramite.deleted_at')
                    ->havingRaw('COUNT(dato_seguimiento.id) > 0 OR COUNT(etapa.id) > 1')
                    ->groupBy('tramite.id')
                    ->get();

                $datos[$p->nombre]['pendientes'] = count($conteo);
            }
        }


        foreach ($this->config->procesos as $proceso_id) {
            $p = Doctrine::getTable('Proceso')->find($proceso_id);
            if ($p) {
                $pid = (int) $p->id;
                $cid = (int) $this->cuenta_id;

                $conteo = DB::table('tramite')
                    ->select('tramite.id')
                    ->leftJoin('etapa', 'etapa.tramite_id', '=', 'tramite.id')
                    ->leftJoin('dato_seguimiento', 'dato_seguimiento.etapa_id', '=', 'etapa.id')
                    ->leftJoin('proceso', 'tramite.proceso_id', '=', 'proceso.id')
                    ->leftJoin('cuenta', 'proceso.cuenta_id', '=', 'cuenta.id')
                    ->whereBetween('tramite.created_at', array($this->anomin, $this->anomax) )
                    ->where('tramite.pendiente',0)
                    ->where('cuenta.id',$cid)
                    ->where('proceso.id',$pid)
                    ->where('proceso.activo',1)
                    ->whereNull('tramite.deleted_at')
                    ->havingRaw('COUNT(dato_seguimiento.id) > 0 OR COUNT(etapa.id) > 1')
                    ->groupBy('tramite.id')
                    ->get();

                $datos[$p->nombre]['completados'] = count($conteo);
            }
        }


        $categories_arr = array();
        $pendientes_arr = array();
        $completados_arr = array();
        foreach ($datos as $key => $val) {
            $categories_arr[] = $key;
            $pendientes_arr[] = isset($val['pendientes']) ? (int)$val['pendientes'] : 0;
            $completados_arr[] = isset($val['completados']) ? (int)$val['completados'] : 0;
        }
        $categories = json_encode($categories_arr);
        $pendientes = json_encode($pendientes_arr);
        $completados = json_encode($completados_arr);

        $display = '<div class="grafico"></div>';
        $this->javascript = '
        <script type="text/javascript">
            $(document).ready(function(){
                new Highcharts.Chart({
                    chart: {
                        renderTo: $(".widget[data-id=' . $this->id . '] .grafico")[0],
                        type: "column"
                    },
                    title: null,
                    yAxis: {
                        title: {
                            text: "Nº de Trámites"
                        },
                    },
                    xAxis: {
                        categories: ' . $categories . '
                    },
                    plotOptions: {
                        series: {
                            minPointLength:3,
                        },
                    },
                    series: [{
                        name: "Pendientes",
                        data: ' . $pendientes . '
                        },
                        {
                        name: "Completados",
                        data: ' . $completados . '
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
        $procesos = $this->Cuenta->getProcesosActivos();//Procesos;
        $anominbd = $this->anomin;
        $anomaxbd = $this->anomax;

        $procesos_array = $this->config ? $this->config->procesos : array();

        $display = '<label>Procesos a desplegar</label>';
        foreach ($procesos as $p) {
            $display .= '
            <div class="form-check">
                <input class="form-check-input" id="' . $p->id . '" type="checkbox" name="config[procesos][]" value="' . $p->id . '" ' . (in_array($p->id, $procesos_array) ? 'checked' : '') . ' />
                <label for="' . $p->id . '" class="form-check-label">' . $p->nombre . '</label>
            </div>';
        }

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
        $CI = &get_instance();
        $CI->form_validation->set_rules('config[procesos]', 'Procesos', 'required');
    }

}

