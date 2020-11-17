<?php

use App\Helpers\Doctrine;

class Tramite extends Doctrine_Record
{

    function setTableDefinition()
    {
        $this->hasColumn('id');
        $this->hasColumn('pendiente');
        $this->hasColumn('proceso_id');
        $this->hasColumn('created_at');
        $this->hasColumn('updated_at');
        $this->hasColumn('ended_at');
        $this->hasColumn('tramite_proc_cont');
        if(\Schema::hasColumn('tramite', 'deleted_at')){
            $this->hasColumn('deleted_at');
        }
    }

    function setUp()
    {
        parent::setUp();

        $this->actAs('Timestampable');

        $this->hasOne('Proceso', array(
            'local' => 'proceso_id',
            'foreign' => 'id'
        ));

        $this->hasMany('Etapa as Etapas', array(
            'local' => 'id',
            'foreign' => 'tramite_id'
        ));

        $this->hasMany('File as Files', array(
            'local' => 'id',
            'foreign' => 'tramite_id'
        ));
    }

    public function iniciar($proceso_id, $bodyContent=null)
    {
        // Aumentar el contador de Proceso
        Doctrine_Query::create()
            ->update('Proceso p')
            ->set("proc_cont", "proc_cont + 1")
            ->where("p.id = ?", $proceso_id)
            ->execute();

        $proceso = Doctrine::getTable('Proceso')->find($proceso_id);

        if (isset($proceso) && strlen($proceso->id) > 0) {
            $this->proceso_id = $proceso->id;
            $this->pendiente = 1;
            $this->tramite_proc_cont = $proceso->proc_cont;

            $etapa = new Etapa();

            $etapa->tarea_id = $proceso->getTareaInicial(UsuarioSesion::usuario()->id)->id;
            $etapa->pendiente = 1;

            $this->Etapas[] = $etapa;

            $this->save();
            $etapa->asignar(UsuarioSesion::usuario()->id);

            if(!is_null($bodyContent))
                $this->save_data($etapa->id, $bodyContent);

        } else {
            throw new ApiException('Proceso no existe', 404);
        }
    }

    public function getEtapasParticipadas($usuario_id)
    {
        return Doctrine_Query::create()
            ->from('Etapa e, e.Tramite t')
            ->where('t.id = ? AND e.usuario_id=?', array($this->id, $usuario_id))
            ->andWhere('e.pendiente=0')
            ->execute();
    }

    public function getEtapasActuales()
    {
        return Doctrine_Query::create()
            ->from('Etapa e, e.Tramite t')
            ->where('t.id = ? AND e.pendiente=1', $this->id)
            ->execute();
    }

    public function getAllEtapas()
    {
        return Doctrine_Query::create()
            ->from('Etapa e, e.Tramite t')
            ->where('t.id = ?', $this->id)
            ->execute();
    }

    public function getUltimaEtapa()
    {
        return Doctrine_Query::create()
            ->from('Etapa e, e.Tramite t')
            ->where('t.id = ?', $this->id)
            ->orderBy('e.id DESC')
            ->fetchOne();
    }

    public function getVolumen(){
        $volumen = DB::table('etapa')
            ->join('tramite', 'etapa.tramite_id', '=', 'tramite.id')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->select('dato_seguimiento.valor')
            ->where('tramite.id', $this->id)
            ->where('dato_seguimiento.nombre', 'volu')
            ->get();

        $json = json_decode($volumen, true);
        $arr = array_column( $json, 'valor' );

        return str_replace('"', '', implode('', $arr) );
    }

    public function getCauce(){
        $volumen = DB::table('etapa')
            ->join('tramite', 'etapa.tramite_id', '=', 'tramite.id')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->select('dato_seguimiento.valor')
            ->where('tramite.id', $this->id)
            ->where('dato_seguimiento.nombre', 'cauce')
            ->get();

        $json = json_decode($volumen, true);
        $arr = array_column( $json, 'valor' );

        return str_replace('"', '', implode('', $arr) );
    }

    public function getUltimaEtapaReal()
    {
        $etapa = Doctrine_Query::create()
            ->from('Etapa e, e.Tramite t')
            ->where('t.id = ?', $this->id)
            ->orderBy('e.id DESC')
            ->fetchOne()
            ->Tarea
            ->nombre;
	$fact_revi = array("Solicitud de permiso para extracción de áridos", "Asignación de tareas", "Análisis factibilidad técnica DOH I", "Informe a municipio sobre la no factibilidad técnica de la solicitud con observaciones");
	$fact_vist = array("Solicitud de ingreso de antecedentes técnicos (proyecto) I");
	$fact_rech = array("Rechazo municipio", "No factibilidad técnica de la solicitud", "Análisis factibilidad técnica DOH II");
	$proy_revi = array("Análisis de proyecto I");
	$proy_obs = array("Solicitud de ingreso de antecedentes técnicos (proyecto) II", "Solicitud de ingreso de antecedentes técnicos (proyecto) III", "Análisis de proyecto II", "Análisis de proyecto III");
	$proy_rech = array("Informe sobre rechazo de solicitud sin observaciones", "Informe sobre rechazo de solicitud con observaciones");
	$proy_vist = array("Informe sobre aprobación de solicitud");

	if ( in_array($etapa, $fact_revi) ) {
		    return "FACTIBILIDAD/EN REVISION";
	}else if ( in_array($etapa, $fact_vist) ) {
		    return "FACTIBILIDAD/CON VISTO BUENO";
	}else if ( in_array($etapa, $fact_rech) ) {
		    return "FACTIBILIDAD/RECHAZADO";
	}else if ( in_array($etapa, $proy_revi ) ) {
		    return "PROYECTO/EN REVISION";
	}else if ( in_array($etapa,$proy_obs) ) {
		    return "PROYECTO/CON OBSERVACIONES";
	}else if ( in_array($etapa, $proy_rech) ) {
		    return "PROYECTO/RECHAZADO";
	}else if ( in_array($etapa, $proy_vist) ) {
		    return "PROYECTO/CON VISTO BUENO";
	}else{
		    return "NA/NA";
	}
    }

    public function getColor( $etapa ){
	   if ( $etapa == "FACTIBILIDAD" ) {
		      return "<font color='gold'><b>" . $etapa . "</b></font>";
	   }else if ( $etapa == "PROYECTO" ) {
		      return "<font color='blue'><b>" . $etapa . "</b></font>";
	   }else if ( $etapa == "EN REVISION" ) {
		      return "<font color='gray'><b>" . $etapa . "</b></font>";
	   }else if ( $etapa == "CON OBSERVACIONES" ) {
		      return "<font color='aqua'><b>" . $etapa . "</b></font>";
	   }else if ( $etapa == "RECHAZADO" ) {
		      return "<font color='red'><b>" . $etapa . "</b></font>";
	   }else if ( $etapa == "CON VISTO BUENO" ) {
		      return "<font color='green'><b>" . $etapa . "</b></font>";
	   }else{
		      return $etapa;
	   }
    }

    public function getUltimaEtapaRealColor( $etapa ){
        $etapas = explode("/", $etapa);

        return $this->getColor($etapas[0]) . '/' . $this->getColor($etapas[1]);
    }

    public function getBadgeColor( $etapa ){
        $etapas = explode("/", $etapa);
	
	if ( $etapas[0] == "CON VISTO BUENO" ) {
		    return "success";
	}else if ( $etapas[0] == "FACTIBILIDAD" ) {
		    return "warning";
	}else if ( $etapas[0] == "PROYECTO" ) {
		    return "primary";
	}else if ( $etapas[0] == "RECHAZADO" ) {
		    return "danger";
	}else if ( $etapas[0] == "EN REVISION" ) {
		    return "secondary";
	}else if ( $etapas[0] == "CON OBSERVACIONES" ) {
		    return "info";
	}
    }

    public function getTareasActuales()
    {
        return Doctrine_Query::create()
            ->from('Tarea tar, tar.Etapas e, e.Tramite t')
            ->where('t.id = ? AND e.pendiente=1', $this->id)
            ->execute();
    }

    public function getTareasCompletadas()
    {
        return Doctrine_Query::create()
            ->from('Tarea tar, tar.Etapas e, e.Tramite t')
            ->where('t.id = ? AND e.pendiente=0', $this->id)
            ->execute();
    }

    public function getTareasVencidas()
    {

        return Doctrine_Query::create()
            ->from('Tarea tar, tar.Etapas e, e.Tramite t')
            ->where('t.id = ? AND e.pendiente=1 AND DATEDIFF(e.vencimiento_at,NOW()) < 0', $this->id)
            ->execute();
    }

    public function getTareasVencenHoy()
    {
        return Doctrine_Query::create()
            ->from('Tarea tar, tar.Etapas e, e.Tramite t')
            ->where('t.id = ? AND e.pendiente=1 AND DATEDIFF(e.vencimiento_at,NOW()) = 0', $this->id)
            ->execute();

    }

    public function getValorDatoSeguimiento()
    {
        return Doctrine_Query::create()
            ->from("DatoSeguimiento d, d.Etapa e, e.Tramite t")
            ->where("t.id = ?   AND e.pendiente=0  ", $this->id)
            ->execute();
    }
    /*
      public function getTareaProxima() {
      $tarea_actual = $this->getEtapaActual()->Tarea;

      if ($tarea_actual->final)
      return NULL;

      $conexiones = $tarea_actual->ConexionesOrigen;

      foreach ($conexiones as $c) {
      if ($c->evaluarRegla($this->id))
      return $c->TareaDestino;
      }

      return NULL;
      }
     *
     */

    //Chequea si el usuario_id ha tenido participacion en este tramite.
    public function usuarioHaParticipado($usuario_id)
    {

        $tramite = Doctrine_Query::create()
            ->from('Tramite t, t.Etapas e, e.Usuario u')
            ->where('t.id = ? AND u.id = ?', array($this->id, $usuario_id))
            ->fetchOne();

        if ($tramite)
            return TRUE;

        return FALSE;
    }

    public function usuarioClaveUnica($usuario_id)
    {
        $tramite = Doctrine_Query::create()
            ->from('Tramite t, t.Etapas e, e.Usuario u')
            ->where('t.id = ? AND u.id = ?', array($this->id, $usuario_id))
            ->andWhere('u.open_id = 1')
            ->andWhere('t.pendiente = 0')
            ->fetchOne();

        if ($tramite)
            return TRUE;

        return FALSE;
    }

    public function cerrar($ejecutar_eventos = TRUE)
    {
        Doctrine_Manager::connection()->beginTransaction();

        foreach ($this->Etapas as $e) {
            $e->cerrar($ejecutar_eventos);
        }
        $this->pendiente = 0;
        $this->ended_at = date('Y-m-d H:i:s');
        $this->save();

        Doctrine_Manager::connection()->commit();
    }

    //Retorna el tramite convertido en array, solamente con los campos visibles al publico a traves de la API.
    public function toPublicArray()
    {
        $etapas = null;
        $etapas_obj = Doctrine_Query::create()->from('Etapa e')->where('e.tramite_id = ?', $this->id)->orderBy('id desc')->execute();
        foreach ($etapas_obj as $e)
            $etapas[] = $e->toPublicArray();

        $datos = null;
        $datos_obj = Doctrine::getTable('DatoSeguimiento')->findByTramite($this->id);
        foreach ($datos_obj as $d)
            $datos[] = $d->toPublicArray();

        $publicArray = array(
            'id' => (int)$this->id,
            'estado' => $this->pendiente ? 'pendiente' : 'completado',
            'proceso_id' => (int)$this->proceso_id,
            'fecha_inicio' => $this->created_at,
            'fecha_modificacion' => $this->updated_at,
            'fecha_termino' => $this->ended_at,
            'etapas' => $etapas,
            'datos' => $datos
        );

        return $publicArray;

    }

    static public function getReporteHeaders()
    {

        return array(
            'pendiente - Estado del Trámite',
            'proceso_id - Proceso ID',
            'created_at - Fecha de Inicio del Trámite',
            'updated_at - Fecha de Modificación del Trámite',
            'ended_at - Fecha de Finalización del Trámite',
            'etapa_actual - Etapas actuales del Trámite',
            'vencimiento_at - Vencimiento de etapas actuales del Trámite',
            'dias_vencidos - Dias vencidos de etapas actuales del Trámite'
        );
    }

    public function getValorDatoSeguimientoAll()
    {
        return Doctrine_Query::create()
            ->from("DatoSeguimiento d, d.Etapa e, e.Tramite t")
            ->where("t.id = ?   AND e.pendiente IN (0,1)", $this->id)
            ->execute();
    }

    private function save_data($etapa_id, $bodyContent){
        foreach ($bodyContent as $key => $value){
            $dato = Doctrine::getTable('DatoSeguimiento')->findOneByNombreAndEtapaId($key, $etapa_id);
            if (!$dato)
                $dato = new DatoSeguimiento();
            $key = str_replace("-", "_", $key);
            $key = str_replace(" ", "_", $key);
            $dato->nombre = $key;
            $dato->valor = $value;
            if (!is_object($dato->valor) && !is_array($dato->valor)) {
                if (preg_match('/^\d{4}[\/\-]\d{2}[\/\-]\d{2}$/', $dato->valor)) {
                    $dato->valor = preg_replace("/^(\d{4})[\/\-](\d{2})[\/\-](\d{2})/i", "$3-$2-$1", $dato->valor);
                }
            }
            $dato->etapa_id = $etapa_id;
            $dato->save();
        }
    }
}

