<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Doctrine;
use App\Models\Cuenta;
use Illuminate\Support\Facades\Auth;
use App\Models\GrupoUsuarios;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\User;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	//	$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|max:255|unique:usuario',
			'password' => 'required|string|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	public function create(Request $request,$edit=false)
	{
		$usuario = $request->input('usuario');
		$pass= $request->input('password');

		$body = '<?xml version="1.0" encoding="utf-8"?><soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope"><soap12:Body><ValidaUsuarioAD xmlns="http://www.mop.cl/">
   <usuario>'.$usuario.'</usuario>
   <pass>'.$pass.'</pass>
</ValidaUsuarioAD></soap12:Body></soap12:Envelope>';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://wscorporativo.moptt.gov.cl/Funcionarios/ActiveDirectory/AccesoActiveDirectory.asmx");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     $body ); 
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml')); 

		$remote_server_output = curl_exec ($ch);
		curl_close ($ch);
		$pos = strpos($remote_server_output, "SI");
		if ($pos === false) {
	echo '<script languajge="javascript">';
	echo "alert('Usario MOP no invalido, revise usario y/o contraseña');";
	echo 'window.location.href = "login";';
	echo '</script>';

	redirect("/login");
}else{	
		$user = new User();
	if ($request->has('password') && !empty($request->input('password'))) {
            $this->validate($request, ['password' => 'required|confirmed|min:6']);

            $user->password = Hash::make($request->input('password'));
        }

            $this->validate($request, ['usuario' => 'required|unique:usuario']);

            $user->usuario = $request->input('usuario');

        $user->nombres = $request->input('nombres');
        $user->apellido_paterno = $request->input('apellido_paterno');
        $user->apellido_materno = $request->input('apellido_materno');
        $user->vacaciones = $request->has('vacaciones') ? 1 : 0;
        $user->email = $request->input('email');
        $user->cuenta_id = Auth::user()->cuenta_id;
        $user->cuenta_id = 1;
        $user->salt = '';
        $user->save();

        //Insertamos las nuevas relaciones
return      redirect("login"); 
}
	}
}
