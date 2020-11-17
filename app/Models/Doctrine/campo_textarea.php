<?php
require_once('campo.php');

use App\Helpers\Doctrine;

class CampoTextArea extends Campo
{
    public $requiere_datos = false;

    protected function display($modo, $dato, $etapa_id = false)
    {
        if ($etapa_id) {
            $etapa = Doctrine::getTable('Etapa')->find($etapa_id);
            $regla = new Regla($this->valor_default);
            $valor_default = $regla->getExpresionParaOutput($etapa->id);
        } else {
            $valor_default = $this->valor_default;
        }

        $display = '<div class="form-group">';
        $display .= '<label class="control-label" for="' . $this->id . '">' . $this->etiqueta . (!in_array('required', $this->validacion) ? ' (Opcional)' : '') . '</label>';
        if ($this->ayuda)
            $display .= '<span class="help-block"> (' . $this->ayuda . ')</span>';
        $display .= '<textarea id="' . $this->id . '" rows="5" class="form-control" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' name="' . $this->nombre . '">' . ($dato ? htmlspecialchars($dato->valor) : htmlspecialchars($valor_default)) . '</textarea>';
        $display .= '</div>';

        $searchword = 'max';
        $matches = array_filter($this->validacion, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });
        if(count($matches)){
            $indice = max(array_keys($matches));
            $limite = str_replace("max:","",$matches[$indice]);
            $display .= '
            <script>
                $(document).ready(function(){
                    $("#' . $this->id . '").EnsureMaxLength({
                        limit: '.$limite.'
                    });
                });
            </script>';
        }

        return $display;
    }

}