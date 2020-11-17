<?php

class TramiteTable extends Doctrine_Table {

    //
    //
    //
    //
    //
    //
    public function findAllTramites($cuenta='localhost', $limite=0, $inicio=0, $datos=0, $result=0){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u ')
                ->where('t.deleted_at is NULL')
                ->having('COUNT(t.id) > 0')  //Mostramos solo los que se han avanzado o tienen datos
                ->groupBy('t.id')
                ->orderBy('t.updated_at desc')
                ->limit($limite)
                ->offset($inicio);

        if($result)
            $query->whereIn('t.id',$datos);

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    public function findCompletados($usuario_id, $cuenta='localhost', $limite, $inicio, $datos, $result){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u ')
                //->from('DatoSeguimiento d, d.Etapa ex, ex.Tramite t, t.Etapas e, t.Proceso.Cuenta c, e.Usuario u')
                ->where('t.pendiente = 0 and u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->andWhere('t.deleted_at is NULL')
                ->having('COUNT(t.id) > 0')  //Mostramos solo los que se han avanzado o tienen datos
                ->groupBy('t.id')
                ->orderBy('t.updated_at desc')
                ->limit($limite)
                ->offset($inicio);

        if($result)
            $query->whereIn('t.id',$datos);

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    public function findCompletadosALL($usuario_id, $cuenta='localhost'){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u')
                ->where('t.pendiente = 0 and u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->limit(3000)
                ->andWhere('t.deleted_at is NULL')
                ->orderBy('t.updated_at desc');

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);
        return $query->execute();
    }

    public function findPendientes($usuario_id, $cuenta='localhost', $limite, $inicio, $datos, $result){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u ')
                //->from('DatoSeguimiento d, d.Etapa ex, ex.Tramite t, t.Etapas e, t.Proceso.Cuenta c, e.Usuario u')
                ->where('u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->andWhere('t.pendiente=1')
                ->andWhere('t.deleted_at is NULL')
                ->having('COUNT(t.id) > 0')  //Mostramos solo los que se han avanzado o tienen datos
                ->groupBy('t.id')
                ->orderBy('t.updated_at desc')
                ->limit($limite)
                ->offset($inicio);

        if($result)
            $query->whereIn('t.id',$datos);

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    public function findPendientesALL($usuario_id, $cuenta='localhost'){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u')
                ->where('u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->andWhere('t.pendiente=1')
                ->limit(3000)
                ->andWhere('t.deleted_at is NULL')
                ->orderBy('t.updated_at desc');

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);
        return $query->execute();
    }
    //
    //
    //
    //
    //

    //busca los tramites donde el $usuario_id ha participado
    public function findParticipados($usuario_id,$cuenta='localhost',$limite,$inicio,$datos,$result){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u ')
                //->from('DatoSeguimiento d, d.Etapa ex, ex.Tramite t, t.Etapas e, t.Proceso.Cuenta c, e.Usuario u')
                ->where('u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->andWhere('t.deleted_at is NULL')
                ->having('COUNT(t.id) > 0')  //Mostramos solo los que se han avanzado o tienen datos
                ->groupBy('t.id')
                ->orderBy('t.updated_at desc')
                ->limit($limite)
                ->offset($inicio);

        if($result)
            $query->whereIn('t.id',$datos);

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    public function findParticipadosALL($usuario_id, $cuenta='localhost'){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u')
                ->where('u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->limit(3000)
                ->andWhere('t.deleted_at is NULL')
                ->orderBy('t.updated_at desc');

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);
        return $query->execute();
    }

    public function findParticipadosMatched($usuario_id, $cuenta='localhost', $datos, $buscar){
        $query=Doctrine_Query::create()
                ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u ')
                //->from('DatoSeguimiento d, d.Etapa ex, ex.Tramite t, t.Etapas e, t.Proceso.Cuenta c, e.Usuario u')
                ->where('u.id = ?',$usuario_id)
                ->andWhere('e.pendiente=0')
                ->andWhere('t.deleted_at is NULL')
                ->having('COUNT(t.id) > 0')  //Mostramos solo los que se han avanzado o tienen datos
                ->groupBy('t.id')
                ->orderBy('t.updated_at desc');

        if($buscar)
            $query->whereIn('t.id',$datos);

        if($cuenta!='localhost')
            $query->andWhere('c.nombre = ?',$cuenta->nombre);

        return $query->execute();
    }

    public function tramitesPorUsuario($usuario_id){
        $query=Doctrine_Query::create()
            ->from('Tramite t, t.Proceso.Cuenta c, t.Etapas e, e.Usuario u')
            ->where('u.id = ?',$usuario_id)
            ->andWhere('t.deleted_at is NULL')
            ->orderBy('t.updated_at desc');

        return $query->execute();
    }

}

