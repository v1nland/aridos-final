<?php

class EtapaTable extends Doctrine_Table {

    //busca las etapas que no han sido asignadas y que usuario_id se podria asignar
    public function findSinAsignar($usuario_id, $cuenta='localhost',$matches="0",$query="0",$limite=2000, $inicio=0){
        $usuario = \App\Helpers\Doctrine::getTable('Usuario')->find($usuario_id);
        if(!$usuario->open_id){
            $grupos =  DB::table('grupo_usuarios_has_usuario')
                ->select('grupo_usuarios_id')
                ->where('usuario_id',$usuario->id)
                ->get()
                ->toArray();
            $grupos = json_decode(json_encode($grupos), true);

            if($grupos){
                $tareas = DB::table('etapa')

                ->select('etapa.id as etapa_id','tarea.acceso_modo as acceso_modo','grupos_usuarios','tramite.id',
                'previsualizacion','proceso.nombre as p_nombre','tarea.nombre as t_nombre','etapa.updated_at','etapa.vencimiento_at')
                ->leftJoin('tarea', 'etapa.tarea_id', '=', 'tarea.id')
                ->leftJoin('tramite', 'etapa.tramite_id', '=', 'tramite.id')
                ->leftJoin('proceso', 'tramite.proceso_id', '=', 'proceso.id')
                ->leftJoin('cuenta', 'proceso.cuenta_id', '=', 'cuenta.id')
                ->where('cuenta.nombre',$cuenta->nombre)
                ->whereIn('tarea.grupos_usuarios',$grupos)
               /* ->where(function($query) use($grupos){
                    foreach ($grupos as $grupo){
                        $query->orWhere('tarea.grupos_usuarios', $grupo['grupo_usuarios_id']);
                    }
                })*/
                ->whereNull('etapa.usuario_id')
                ->limit($limite)
                ->offset($inicio)
                ->orderBy('etapa.tarea_id', 'ASC')
                ->get()->toArray();

                //se buscan etapas cuyas tareas que por nivel de acceso esten configuradas por nombre de grupo como variables @@
                $tareas_aa = DB::table('etapa')
                    ->select('etapa.id as etapa_id','tarea.acceso_modo as acceso_modo','grupos_usuarios','tramite.id',
                        'previsualizacion','proceso.nombre as p_nombre','tarea.nombre as t_nombre','etapa.updated_at','etapa.vencimiento_at')
                    ->leftJoin('tarea', 'etapa.tarea_id', '=', 'tarea.id')
                    ->leftJoin('tramite', 'etapa.tramite_id', '=', 'tramite.id')
                    ->leftJoin('proceso', 'tramite.proceso_id', '=', 'proceso.id')
                    ->leftJoin('cuenta', 'proceso.cuenta_id', '=', 'cuenta.id')
                    ->where('cuenta.nombre',$cuenta->nombre)
                    ->where('tarea.grupos_usuarios','LIKE','%@@%')
                    ->whereNull('etapa.usuario_id')
                    ->limit($limite)
                    ->offset($inicio)
                    ->orderBy('etapa.tarea_id', 'ASC')
                    ->get()->toArray();
                if(count($tareas_aa)){
                    foreach($tareas_aa as $key=>$t)
                        if(!$this->canUsuarioAsignarsela($usuario_id,$t->acceso_modo,$t->grupos_usuarios,$t->etapa_id))
                            unset($tareas_aa[$key]);

                    //se agregan al listado original de etapas solo las que cumplen los nombres de grupo como variables @@
                    foreach($tareas_aa as $tarea)
                        array_push($tareas,$tarea);
                }
            }
            else{
                $tareas = array();
            }
        }else{
            $tareas = array();
        }


        return $tareas;
    }

   public function findSinAsignarMatch($usuario_id, $cuenta='localhost',$matches="0",$query="0"){
       $usuario = \App\Helpers\Doctrine::getTable('Usuario')->find($usuario_id);
       if(!$usuario->open_id){
            $grupos =  DB::table('grupo_usuarios_has_usuario')
                        ->select('grupo_usuarios_id')
                        ->where('usuario_id',$usuario->id)
                        ->get()
                        ->toArray();
            $grupos = json_decode(json_encode($grupos), true);

            if($grupos){
                $tareas = DB::table('etapa')
                ->select('etapa.id as etapa_id','tarea.acceso_modo as acceso_modo','grupos_usuarios','tramite.id',
                'previsualizacion','proceso.nombre as p_nombre','tarea.nombre as t_nombre','etapa.updated_at','etapa.vencimiento_at')
                ->leftJoin('tarea', 'etapa.tarea_id', '=', 'tarea.id')
                ->leftJoin('tramite', 'etapa.tramite_id', '=', 'tramite.id')
                ->leftJoin('proceso', 'tramite.proceso_id', '=', 'proceso.id')
                ->leftJoin('cuenta', 'proceso.cuenta_id', '=', 'cuenta.id')
                ->where('cuenta.nombre',$cuenta->nombre)
                ->whereIn('tarea.grupos_usuarios',[$grupos])
                ->whereIn('tramite.id',[$matches])
                ->whereNull('etapa.usuario_id')
                ->orderBy('etapa.tarea_id', 'ASC')
                ->get()->toArray();
            }
            else{
                $tareas = array();
            }
        }else{
            $tareas = array();
        }
        return $tareas;
    }

    //busca las etapas donde esta pendiente una accion de $usuario_id
    public function findPendientes($usuario_id,$cuenta='localhost',$orderby='updated_at',$direction='desc',$matches="0",$buscar="0", $limite=0, $inicio=0){
        $query=Doctrine_Query::create()
                ->from('Etapa e, e.Tarea tar, e.Usuario u, e.Tramite t, t.Etapas hermanas, t.Proceso p, p.Cuenta c')
                ->select('e.*,COUNT(hermanas.id) as netapas, p.nombre as proceso_nombre, tar.nombre as tarea_nombre')
                ->groupBy('e.id')
                //Si la etapa se encuentra pendiente y asignada al usuario
                ->where('e.pendiente = 1 and u.id = ?',$usuario_id)
                //Si la tarea se encuentra activa
                ->andWhere('1!=(tar.activacion="no" OR ( tar.activacion="entre_fechas" AND ((tar.activacion_inicio IS NOT NULL AND tar.activacion_inicio>NOW()) OR (tar.activacion_fin IS NOT NULL AND NOW()>tar.activacion_fin) )))')
                ->andWhere('t.deleted_at is NULL')
                ->limit($limite)
                ->offset($inicio)
                ->orderBy($orderby.' '.$direction);

        if($buscar){
            $query->whereIn('t.id',$matches);
        }

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    //busca las etapas donde esta pendiente una accion de $usuario_id
    public function findCompletados($usuario_id,$cuenta='localhost',$orderby='updated_at',$direction='desc',$matches="0",$buscar="0", $limite=0, $inicio=0){
        $query=Doctrine_Query::create()
                ->from('Etapa e, e.Tarea tar, e.Usuario u, e.Tramite t, t.Etapas hermanas, t.Proceso p, p.Cuenta c')
                ->select('e.*,COUNT(hermanas.id) as netapas, p.nombre as proceso_nombre, tar.nombre as tarea_nombre')
                ->groupBy('e.id')
                //Si la etapa se encuentra completada y asignada al usuario
                ->where('e.pendiente = 0 and u.id = ?', $usuario_id)
                //Si la tarea se encuentra activa
                ->andWhere('1!=(tar.activacion="no" OR ( tar.activacion="entre_fechas" AND ((tar.activacion_inicio IS NOT NULL AND tar.activacion_inicio>NOW()) OR (tar.activacion_fin IS NOT NULL AND NOW()>tar.activacion_fin) )))')
                ->andWhere('t.deleted_at is NULL')
                ->limit($limite)
                ->offset($inicio)
                ->orderBy($orderby.' '.$direction);

        if($buscar){
            $query->whereIn('t.id',$matches);
        }

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    public function verBitacora($tramite_id, $cuenta='localhost', $orderby='id',$direction='desc',$matches="0",$buscar="0"){
        $bitacora = DB::table('bitacora')
            ->select('content', 'fecha', 'escritor')
            ->where('bitacora.tramite_id', $tramite_id)
            ->limit(10)
            ->orderBy('id', 'desc')
            ->get();

        return $bitacora;
    }

    public function hasFileForBadge($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $comentarios = DB::table('tramite')
            ->select('tramite.id', 'dato_seguimiento.nombre', 'valor')
            ->join('etapa', 'etapa.tramite_id', '=', 'tramite.id')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('dato_seguimiento.nombre', 'carga_documentos')
            ->where('tramite.id', $tramite_id)
            ->get();

        $dato = implode(', ', array_column(json_decode($comentarios, true), 'valor'));

        return $dato;
    }

    public function makeIDRegion($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = DB::table('etapa')
            ->select('valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('nombre', "comunasfact")
            ->where('etapa.tramite_id', $tramite_id)
            ->get();

        if ( $reg == "[]" ) {
            return "ARIDO-NA-" . $tramite_id;
        }

        $json = json_decode($reg, true);
        $arr = array_column( $json, 'valor' );
        $finaljson = json_decode( $arr[0], true );

        $roman_reg = $finaljson['cstateCode'];

        if($roman_reg == "01"){
            $roman_reg = "I";
        }else if($roman_reg == "02"){
            $roman_reg = "II";
        }else if($roman_reg == "03"){
            $roman_reg = "III";
        }else if($roman_reg == "04"){
            $roman_reg = "IV";
        }else if($roman_reg == "05"){
            $roman_reg = "V";
        }else if($roman_reg == "06"){
            $roman_reg = "VI";
        }else if($roman_reg == "07"){
            $roman_reg = "VII";
        }else if($roman_reg == "08"){
            $roman_reg = "VIII";
        }else if($roman_reg == "09"){
            $roman_reg = "IX";
        }else if($roman_reg == "10"){
            $roman_reg = "X";
        }else if($roman_reg == "11"){
            $roman_reg = "XI";
        }else if($roman_reg == "12"){
            $roman_reg = "XII";
        }else if($roman_reg == "13"){
            $roman_reg = "RM";
        }else if($roman_reg == "14"){
            $roman_reg = "XIV";
        }else if($roman_reg == "15"){
            $roman_reg = "XV";
        }else if($roman_reg == "16"){
            $roman_reg = "XVI";
        }

        return "ARIDO-" . $roman_reg . "-" . $tramite_id;
    }

    public function makeIDRegionByRegion($tramite_id, $region_tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = DB::table('etapa')
            ->select('valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('nombre', "comunasfact")
            ->where('etapa.tramite_id', $tramite_id)
            ->get();

        if ( $reg == "[]" ) {
            return "ARIDO-NA-" . $tramite_id;
        }

        $json = json_decode($reg, true);
        $arr = array_column( $json, 'valor' );
        $finaljson = json_decode( $arr[0], true );

        $roman_reg = $finaljson['cstateCode'];

        if($roman_reg == "01"){
            $roman_reg = "I";
        }else if($roman_reg == "02"){
            $roman_reg = "II";
        }else if($roman_reg == "03"){
            $roman_reg = "III";
        }else if($roman_reg == "04"){
            $roman_reg = "IV";
        }else if($roman_reg == "05"){
            $roman_reg = "V";
        }else if($roman_reg == "06"){
            $roman_reg = "VI";
        }else if($roman_reg == "07"){
            $roman_reg = "VII";
        }else if($roman_reg == "08"){
            $roman_reg = "VIII";
        }else if($roman_reg == "09"){
            $roman_reg = "IX";
        }else if($roman_reg == "10"){
            $roman_reg = "X";
        }else if($roman_reg == "11"){
            $roman_reg = "XI";
        }else if($roman_reg == "12"){
            $roman_reg = "XII";
        }else if($roman_reg == "13"){
            $roman_reg = "RM";
        }else if($roman_reg == "14"){
            $roman_reg = "XIV";
        }else if($roman_reg == "15"){
            $roman_reg = "XV";
        }else if($roman_reg == "16"){
            $roman_reg = "XVI";
        }

        return "ARIDO-" . $roman_reg . "-" . $region_tramite_id;
    }

    public function idByRegion($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = $this->getRegion($tramite_id);

        $date = DB::table('tramite')
            ->select('created_at')
            ->where('tramite.id', $tramite_id)
            ->get();

        $json = json_decode($date, true);
        $arr = array_column( $json, 'created_at' );
        $date_tramite = str_replace('"', '', implode('', $arr) );

        $cnt = DB::table('tramite')
            ->where('created_at', '<=', $date_tramite)
            // y que ademas sean de la region $reg
            ->get();

        $json = json_decode( $cnt, true );
        $arr = array_column( $json, 'id' );

        $count = 0;
        for ($i=0; $i < count( $arr ); $i++) {
            if ( $this->getRegion( $arr[$i] ) == $reg ) {
                $count++;
            }
        }

        return $count;
    }

    public function getRegionComuna($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = DB::table('etapa')
            ->select('valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('nombre', "comunasfact")
            ->where('etapa.tramite_id', $tramite_id)
            ->get();

        if ( $reg == "[]" ) {
            return "Sin región/comuna asignada";
        }

        $json = json_decode($reg, true);
        $arr = array_column( $json, 'valor' );
        $finaljson = json_decode( $arr[0], true );

        return $finaljson['region'] . ': ' . $finaljson['comuna'];
    }

    public function getRegion($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = DB::table('etapa')
            ->select('valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('nombre', "comunasfact")
            ->where('etapa.tramite_id', $tramite_id)
            ->get();

        if ( $reg == "[]" ) {
            return "Sin región asignada";
        }

        $json = json_decode($reg, true);
        $arr = array_column( $json, 'valor' );
        $finaljson = json_decode( $arr[0], true );

        return $finaljson['region'];
    }

    public function getComuna($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = DB::table('etapa')
            ->select('valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('nombre', "comunasfact")
            ->where('etapa.tramite_id', $tramite_id)
            ->get();

        if ( $reg == "[]" ) {
            return "Sin comuna asignada";
        }

        $json = json_decode($reg, true);
        $arr = array_column( $json, 'valor' );
        $finaljson = json_decode( $arr[0], true );

        return $finaljson['comuna'];
    }

    public function getSolicitante($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $reg = DB::table('etapa')
            ->select('valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('nombre', "nombre_persona")
            ->where('etapa.tramite_id', $tramite_id)
            ->get();

        if ( $reg == "[]" ) {
            return "Sin solicitante";
        }

        $json = json_decode($reg, true);
        $arr = array_column( $json, 'valor' );

        return strtoupper(json_decode($arr[0], JSON_HEX_APOS) );
    }

    public function verComentarios($tramite_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        // query del badge from tramite id
        // $sql = " select tramite.id, dato_seguimiento.nombre, valor from tramite, etapa, dato_seguimiento where etapa.id = dato_seguimiento.etapa_id and etapa.tramite_id = tramite.id and dato_seguimiento.nombre='carga_documentos' ";

        // query del badge from etapa
        // $sql = " select etapa.id, dato_seguimiento.nombre,valor from etapa, dato_seguimiento where etapa.id = dato_seguimiento.etapa_id and nombre='carga_documentos' "

        // any query example
        // usar esta query para hacer lo del badge (chequear si se subio el archivo)
        $comentarios = DB::table('etapa')
            ->select('tramite_id', 'valor')
            ->join('dato_seguimiento', 'etapa.id', '=', 'dato_seguimiento.etapa_id')
            ->where('etapa.tramite_id', $tramite_id)
            ->get();
            // ->toArray();

        return $comentarios;
    }

    public function countPendingTramitesByRegion($asignador_array, $usr_id, $cuenta){
        $tramite = $this->findSinAsignar( $usr_id, $cuenta );

        $cnt = 0;
        foreach ($tramite as $t) {
            $reg_proy = $this->getRegion($t->id);

            if ( in_array( strtoupper($reg_proy), $asignador_array ) ){
                $cnt++;
            }
        }

        return $cnt;
    }

    public function findPendientesALL($usuario_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u')
                ->where('u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=1')
                ->limit(3000)
                ->andWhere('t.deleted_at is NULL')
                ->orderBy('t.updated_at desc');

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);
        return $query->execute();
    }

    public function canUsuarioAsignarsela($usuario_id, $acceso_modo, $grupos_usuarios, $etapa_id){
        static $usuario;

        if (!$usuario || ($usuario->id != $usuario_id)) {
            $usuario = \App\Helpers\Doctrine::getTable('Usuario')->find($usuario_id);
        }

        if ($acceso_modo == 'publico' || $acceso_modo == 'anonimo')
            return true;

        if ($acceso_modo == 'claveunica' && $usuario->open_id)
            return true;

        if ($acceso_modo == 'registrados' && $usuario->registrado)
            return true;

        if ($acceso_modo == 'grupos_usuarios') {
            $r = new Regla($grupos_usuarios);
            $grupos_arr = explode(',', $r->getExpresionParaOutput($etapa_id));
            foreach ($usuario->GruposUsuarios as $g)
                if (in_array($g->id, $grupos_arr))
                    return true;
        }

        return false;
    }

}

