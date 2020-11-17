<?php

class UsuarioTable extends Doctrine_Table {
    public function test(){
        return 'test';
    }

    public function findALL($cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        $query=Doctrine_Query::create()
                ->from('Usuario u')
                ->where('u.registrado = 1');

        return $query->execute();
    }

    public function findByGrupoUsuarioId($grupo_usuario_id, $cuenta='localhost', $orderby='updated_at',$direction='desc',$matches="0",$buscar="0"){
        // $sql = "select * from usuario as u, grupo_usuarios_has_usuario as guhu, grupo_usuarios as gu where u.id = guhu.usuario_id and guhu.grupo_usuarios_id = gu.id and gu.id=$grupo_usuario_id";

        $usuarios = DB::table('usuario')
            ->select('*')
            ->join('grupo_usuarios_has_usuario', 'usuario.id', '=', 'grupo_usuarios_has_usuario.usuario_id')
            ->join('grupo_usuarios', 'grupo_usuarios_has_usuario.grupo_usuarios_id', '=', 'grupo_usuarios.id')
            ->where('grupo_usuarios.id', $grupo_usuario_id)
            ->get();

        return $usuarios;
    }
}

?>
