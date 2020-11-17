<?php

class GrupoUsuarios extends Doctrine_Record {

    function setTableDefinition() {
        $this->hasColumn('id');
        $this->hasColumn('nombre');
        $this->hasColumn('cuenta_id');
        //$this->hasColumn('tipo');
    }

    function setUp() {
        parent::setUp();

        $this->hasOne('Cuenta',array(
            'local'=>'cuenta_id',
            'foreign'=>'id'
        ));

        $this->hasMany('Usuario as Usuarios',array(
            'local'=>'grupo_usuarios_id',
            'foreign'=>'usuario_id',
            'refClass' => 'GrupoUsuariosHasUsuario'
        ));

        $this->hasMany('Tarea as Tareas',array(
            'local'=>'grupo_usuarios_id',
            'foreign'=>'tarea_id',
            'refClass' => 'TareaHasGrupoUsuarios'
        ));

    }

    public function hasUsuario($usuario_id){
        return Doctrine_Query::create()->from('Usuario u, u.GruposUsuarios g')->where('g.id=? AND u.id=?',array($this->id,$usuario_id))->count();
    }

    public function setUsuariosFromArray($usuarios_id){
        foreach($this->Usuarios as $key=>$val)
            unset($this->Usuarios[$key]);

        if($usuarios_id)
            foreach($usuarios_id as $g)
                $this->Usuarios[]=Doctrine::getTable('Usuario')->find($g);
    }

    public function existeGrupo($id_cuenta){
        log_message("INFO", "Revisando grupo: ".$this->nombre." para cuenta: ".$id_cuenta, FALSE);
        $grupo = null;
        $grupo_encontrado = Doctrine_Query::create()->from('GrupoUsuarios g')
                                                    ->where('g.nombre=? AND g.cuenta_id=?',array($this->nombre, $id_cuenta))
                                                    ->execute();
        log_message("INFO", "registros encontrados: ".count($grupo_encontrado), FALSE);
        if(count($grupo_encontrado) > 0){
            return $grupo_encontrado[0];
        }
        return $grupo;
    }
}
