<?php

require_once 'widget.php';

use App\Helpers\Doctrine;

class WidgetEtapaGrupoUsuarios extends Widget
{
    private $javascript;

    public function display()
    {
        if (!$this->config) {
            $display = '<p>Widget requiere configuración</p>';
            return $display;
        }

        $tarea = Doctrine::getTable('Tarea')->find($this->config->tarea_id);

        $stmn = Doctrine_Manager::getInstance()->connection();
        $tmp = $stmn->execute('
    		select g.*, sum(u.cantidad) as cantidad
    		from grupo_usuarios g
    		inner join grupo_usuarios_has_usuario ghas ON ghas.grupo_usuarios_id = g.id
    		inner join (
    				select u.*, count(u.id) as cantidad
    				from usuario u
    				inner join (
    					select e.*
    					from etapa e
    					where e.tarea_id = ? AND e.pendiente=1 AND e.created_at BETWEEN ? AND ?
    				) e ON u.id = e.usuario_id
    				where u.cuenta_id = ?
				group by u.id
    		) u ON ghas.usuario_id = u.id
    		group by g.id;
    		', array($tarea->id, $this->anomin, $this->anomax, $this->cuenta_id))
            ->fetchAll();


        $datos = array();
        foreach ($tmp as $t)
            $datos[] = array($t['nombre'], (float)$t['cantidad']);

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
                        text: "' . $tarea->nombre . '"
                    },
                    tooltip: {
                        pointFormat: "{point.y} trámites pendientes: <b>{point.percentage:.1f}%</b>"
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
        $anomaxbd = $this->anomax;

        $procesos = Doctrine_Query::create()
            ->from('Proceso p, p.Tareas t')
            ->where('p.activo=1 AND p.cuenta_id = ?', $this->Cuenta->id)
            ->andWhere('t.acceso_modo = ?', 'grupos_usuarios')
            ->execute();

        if (!$procesos->count())
            return '<p>No se puede utilizar este widget ya que no tiene tareas asignadas a grupos de usuarios.</p>';

        $display = '<label>Tareas</label>';
        $display .= '<select name="config[tarea_id]" class="form-control">';
        foreach ($procesos as $p) {
            $display .= '<optgroup label="' . $p->nombre . '">';
            foreach ($p->Tareas as $t)
                $display .= '<option value="' . $t->id . '" ' . ($tarea_id == $t->id ? 'selected' : '') . '>' . $t->nombre . '</option>';
            $display .= '</optgroup>';
        }
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

