<?php
require_once('campo.php');

use App\Helpers\Doctrine;
use Illuminate\Http\Request;

class CampoHidden extends Campo
{
    public $requiere_datos = false;

    protected function display($modo, $dato, $etapa_id = false)
    {
	if($this->nombre == 'view_factibilidad'){
		$val = Auth::user()->belongsToGroup("Usuario DOH")?'0':'1';
		$display = '<div class="form-group">';
		$display .= '<input id="' . $this->id . '" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '" value="' . $val . '" data-modo="' . $modo . '_user" />';
		$display .= '</div>';

		return $display;
	}else if($this->nombre == 'responsable'){
		$val = Auth::user()->nombres . ' ' . Auth::user()->str_grupo_usuarios();
		$display = '<div class="form-group">';
		$display .= '<input id="' . $this->id . '" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '" value="' . $val . '" data-modo="' . $modo . '_user" />';
		$display .= '</div>';

		return $display;
		//$display .= '<input id="' . $this->id . '_user" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '_nombre_usuario" value="' . Auth::user()->nombres . '" data-modo="' . $modo . '_nombre_usuario" />';
		//$display .= '<input id="' . $this->id . '_user" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '_grupos_usuario" value="' . Auth::user()->str_grupo_usuarios() . '" data-modo="' . $modo . '_grupos_usuario" />';
	}else{
        	if ($etapa_id) {
            		$etapa = Doctrine::getTable('Etapa')->find($etapa_id);
            		$regla = new Regla($this->valor_default);
	    		$valor_default = $regla->getExpresionParaOutput($etapa->id);
	    		$t = Doctrine::getTable('Etapa')->find($etapa_id)->Tramite;
	    		$dato = $t ? \App\Helpers\Doctrine::getTable('Etapa')->makeIDRegionByRegion($t->id, \App\Helpers\Doctrine::getTable('Etapa')->idByRegion($t->id)) : '';
        	} else {
            		$valor_default = $this->valor_default;
        	}

		$display = '<div class="form-group">';
        	$display .= '<input id="' . $this->id . '" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '" value="' . ($dato ? htmlspecialchars($dato) : htmlspecialchars($valor_default)) . '" data-modo="' . $modo . '" />';
		$display .= '<input id="' . $this->id . '_user" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '_user" value="' . Auth::user()->belongsToGroup("Usuario DOH") . '" data-modo="' . $modo . '_user" />';
$display .= '<input id="' . $this->id . '_user" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '_nombre_usuario" value="' . Auth::user()->nombres . '" data-modo="' . $modo . '_nombre_usuario" />';
		$display .= '<input id="' . $this->id . '_user" ' . ($modo == 'visualizacion' ? 'readonly' : '') . ' type="hidden" class="form-control has-error" name="' . $this->nombre . '_grupos_usuario" value="' . Auth::user()->str_grupo_usuarios() . '" data-modo="' . $modo . '_grupos_usuario" />';
		$display .= '</div>';

		return $display;
	}
    }

}
