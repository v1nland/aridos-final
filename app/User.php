<?php

namespace App;

use App\Notifications\UserFrontResetPasswordNotification;
use App\Models\GrupoUsuarios;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'usuario';

    public $user_type = 'frontend';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario', 'salt', 'nombres', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    // devuelve todos los datos de
    // los grupos a los que pertenece el usuario
    public function grupo_usuarios()
    {
        return $this->belongsToMany('App\Models\GrupoUsuarios', 'grupo_usuarios_has_usuario', 'usuario_id', 'grupo_usuarios_id');
    }

    // devuelve el nombre de todos los grupos de usuario existentes
    // indexado por ID.
    public function all_grupo_usuarios(){
        $group_users = json_decode(GrupoUsuarios::orderBy('id', 'asc')->get(), true);

        return array_column($group_users, 'nombre', 'id');
    }

    // devuelve array con todos los nombres
    // de los grupos en los que esta el usuario
    public function arr_grupos_usuario()
    {
        // todos los grupos que existen [id, nombre]
        $grupos_existentes = $this->all_grupo_usuarios();
        // todos los datos del grupo del usuario
        $data_grupos_usuario = json_decode($this->grupo_usuarios()->getResults(), true);
        // extraemos el ID de los grupos del usuario
        $pivot_grupos_usuario = array_column($data_grupos_usuario, 'pivot');
        $id_grupos_usuario = array_column($pivot_grupos_usuario, 'grupo_usuarios_id');

        // todos los
        $array_grupos_usuario = [];

        for ($i=0; $i < count($id_grupos_usuario); $i++) {
            array_push( $array_grupos_usuario, $grupos_existentes[ $id_grupos_usuario[$i] ] );
        }

        return $array_grupos_usuario;
    }

    // devuelve los nombres de los grupos de usuario
    // a los que pertenece el usuario en formato string
    public function str_grupo_usuarios()
    {
        $arr_grupos = $this->arr_grupos_usuario();

        if ( count($arr_grupos) == 0 ) {
            return "Sin grupo";
        }else{
            return implode(", ", $arr_grupos);
        }
    }


    // Especiales para el coordinador //
    public function arr_grupos_usuario_sin_coordinador()
    {
        // todos los grupos que existen [id, nombre]
        $grupos_existentes = $this->all_grupo_usuarios();
        // todos los datos del grupo del usuario
        $data_grupos_usuario = json_decode($this->grupo_usuarios()->getResults(), true);
        // extraemos el ID de los grupos del usuario
        $pivot_grupos_usuario = array_column($data_grupos_usuario, 'pivot');
        $id_grupos_usuario = array_column($pivot_grupos_usuario, 'grupo_usuarios_id');

        // todos los
        $array_grupos_usuario = [];

        for ($i=0; $i < count($id_grupos_usuario); $i++) {
            if ( $grupos_existentes[ $id_grupos_usuario[$i] ] != "Coordinador Regional" ) {
                array_push( $array_grupos_usuario, $grupos_existentes[ $id_grupos_usuario[$i] ] );
            }
        }

        return $array_grupos_usuario;
    }

    public function str_grupo_usuarios_sin_coordinador()
    {
        $arr_grupos = $this->arr_grupos_usuario_sin_coordinador();

        if ( count($arr_grupos) == 0 ) {
            return "Sin grupo";
        }else{
            return implode("", $arr_grupos);
        }
    }
    // Especiales para el coordinador //
    

    // funcion bool que permita saber si esta en un grupo
    public function belongsToGroup($group){
        $arr_grupos = $this->arr_grupos_usuario();

        for ($i=0; $i < count($arr_grupos); $i++) {
            if ( $arr_grupos[$i] == $group ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserFrontResetPasswordNotification($token));
    }
}

