<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Helpers\Doctrine;
use Doctrine_Query;
use Doctrine_Manager;
use Cuenta;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Tramite;

class TramitesController extends Controller
{
    public function iniciar(Request $request, $proceso_id)
    {
        Log::info('Iniciando proceso ' . $proceso_id);

        $proceso = Doctrine::getTable('Proceso')->find($proceso_id);

        if (!$proceso->canUsuarioIniciarlo(Auth::user()->id)) {
            $url = $proceso->getTareaInicial()->acceso_modo == 'claveunica' ? route('login.claveunica').'?redirect='.route('tramites.iniciar', [$proceso->id]) : route('login').'?redirect='.route('tramites.iniciar', $proceso->id);
            return redirect()->away($url);
        }

        if($proceso->concurrente==1){
            $tramite = new \Tramite();
            $tramite->iniciar($proceso->id);

            if(session()->has('redirect_url')){
                return redirect()->away(session()->get('redirect_url'));
            }
        }else{
            //Vemos si es que usuario ya tiene un tramite de proceso_id ya iniciado, y que se encuentre en su primera etapa.
            //Si es asi, hacemos que lo continue. Si no, creamos uno nuevo
            $tramite = Doctrine_Query::create()
                ->from('Tramite t, t.Proceso p, t.Etapas e, e.Tramite.Etapas hermanas')
                ->where('t.pendiente=1 AND p.activo=1 AND p.id = ? AND e.usuario_id = ?', array($proceso_id, Auth::user()->id))
                ->andWhere('t.deleted_at is NULL')
                ->groupBy('t.id')
                ->having('COUNT(hermanas.id) = 1')
                ->fetchOne();

            if (!$tramite) {
                $tramite = new \Tramite();
                $tramite->iniciar($proceso->id);

                if(session()->has('redirect_url')){
                    return redirect()->away(session()->get('redirect_url'));
                }
            }
        }

        $qs = $request->getQueryString();

        return redirect('etapas/ejecutar/' . $tramite->getEtapasActuales()->get(0)->id . ($qs ? '?' . $qs : ''));
    }

    public function participados(Request $request, $offset = 0)
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $query = $request->input('query');
        $matches = "";
        $rowtramites = "";
        $contador = "0";
        $resultotal = "false";
        $perpage = 50;

        $page = $request->input('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $perpage) - $perpage;


        if ($query) {
            $result = Tramite::search($query)->get();
            $matches = array();
            foreach($result as $resultado){
                array_push($matches, $resultado->id);
            }
            if(count($result) > 0){
                $resultotal = "true";
            }else{
                $resultotal = "false";
            }
        }

	if ($resultotal == 'true') {
		$contador = Doctrine::getTable('Tramite')->findParticipadosMatched(Auth::user()->id, Cuenta::cuentaSegunDominio(), $matches, $query)->count();
		$rowtramites = Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), $perpage, $offset, $matches, $query);
	} else {
		$rowtramites = Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), $perpage, $offset, '0', $query);
	        $contador = Doctrine::getTable('Tramite')->findParticipadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
	}

        $config['base_url'] = url('tramites/participados');
        $config['total_rows'] = $contador;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['page_query_string'] = false;
        $config['query_string_segment'] = 'offset';
        $config['first_link'] = 'Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data = \Cuenta::configSegunDominio();

        $data['tramites'] = new LengthAwarePaginator(
            $rowtramites, // Only grab the items we need
            $contador, // Total items
            $perpage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
        $data['query'] = $query;
        $data['sidebar'] = 'participados';
        $data['content'] = view('tramites.participados', $data);
        $data['title'] = 'Bienvenido';

        return view('layouts.procedure', $data);
    }

    public function completados(Request $request, $offset = 0)
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $query = $request->input('query');
        $matches = "";
        $rowtramites = "";
        $contador = "0";
        $resultotal = "false";
        $perpage = 50;

        $page = $request->input('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $perpage) - $perpage;


        if ($query) {
            $result = Tramite::search($query)->get();
            $matches = array();
            foreach($result as $resultado){
                array_push($matches, $resultado->id);
            }
            if(count($result) > 0){
                $resultotal = "true";
            }else{
                $resultotal = "false";
            }
        }

        if ($resultotal == 'true') {
            $contador = Doctrine::getTable('Tramite')->findParticipadosMatched(Auth::user()->id, Cuenta::cuentaSegunDominio(), $matches, $query)->count();
            $rowtramites = Doctrine::getTable('Tramite')->findCompletadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
        } else {
            $rowtramites = Doctrine::getTable('Tramite')->findCompletados(Auth::user()->id, Cuenta::cuentaSegunDominio(), $perpage, $offset, '0', $query);
            $contador = Doctrine::getTable('Tramite')->findCompletadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
        }

        $config['base_url'] = url('tramites/completados');
        $config['total_rows'] = $contador;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['page_query_string'] = false;
        $config['query_string_segment'] = 'offset';
        $config['first_link'] = 'Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data = \Cuenta::configSegunDominio();

        $data['tramites'] = new LengthAwarePaginator(
            $rowtramites, // Only grab the items we need
            $contador, // Total items
            $perpage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
        $data['query'] = $query;
        $data['sidebar'] = 'completados';
        $data['content'] = view('tramites.completados', $data);
        $data['title'] = 'Bienvenido';

        return view('layouts.procedure', $data);
    }

    public function pendientes(Request $request, $offset = 0)
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $query = $request->input('query');
        $matches = "";
        $rowtramites = "";
        $contador = "0";
        $resultotal = "false";
        $perpage = 50;

        $page = $request->input('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $perpage) - $perpage;


        if ($query) {
            $result = Tramite::search($query)->get();
            $matches = array();
            foreach($result as $resultado){
                array_push($matches, $resultado->id);
            }
            if(count($result) > 0){
                $resultotal = "true";
            }else{
                $resultotal = "false";
            }
        }

        if ($resultotal == 'true') {
            $contador = Doctrine::getTable('Tramite')->findParticipadosMatched(Auth::user()->id, Cuenta::cuentaSegunDominio(), $matches, $query)->count();
            $rowtramites = Doctrine::getTable('Tramite')->findPendientesALL(Auth::user()->id, Cuenta::cuentaSegunDominio(), $perpage, $offset, $matches, $query);
        } else {
            $rowtramites = Doctrine::getTable('Tramite')->findPendientesALL(Auth::user()->id, Cuenta::cuentaSegunDominio(), $perpage, $offset, '0', $query);
            $contador = Doctrine::getTable('Tramite')->findPendientesALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
        }

        $config['base_url'] = url('tramites/pendientes');
        $config['total_rows'] = $contador;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['page_query_string'] = false;
        $config['query_string_segment'] = 'offset';
        $config['first_link'] = 'Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data = \Cuenta::configSegunDominio();

        $data['tramites'] = new LengthAwarePaginator(
            $rowtramites, // Only grab the items we need
            $contador, // Total items
            $perpage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
        $data['query'] = $query;
        $data['sidebar'] = 'pendientes';
        $data['content'] = view('tramites.pendientes', $data);
        $data['title'] = 'Bienvenido';

        return view('layouts.procedure', $data);
    }

    public function estadisticas()
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $data = \Cuenta::configSegunDominio();

        $data['widgets'] = Doctrine::getTable('Widget')->findByCuentaId(Auth::user()->cuenta_id);

        $data['sidebar'] = 'estadisticas';
        $data['content'] = view('tramites.estadisticas', $data);
        $data['title'] = 'Estadísticas';

        return view('layouts.procedure', $data);
    }

    public function buscador(Request $request, $offset = 0)
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $query = $request->input('query');
        $matches = "";
        $rowtramites = "";
        $contador = "0";
        $resultotal = "false";
        $perpage = 50;

        $page = $request->input('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $perpage) - $perpage;


        if ($query) {
            $result = Tramite::search($query)->get();
            $matches = array();
            foreach($result as $resultado){
                array_push($matches, $resultado->id);
            }
            if(count($result) > 0){
                $resultotal = "true";
            }else{
                $resultotal = "false";
            }
        }

        if ($resultotal == 'true') {
            $contador = Doctrine::getTable('Tramite')->findParticipadosMatched(Auth::user()->id, Cuenta::cuentaSegunDominio(), $matches, $query)->count();
            $rowtramites = Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), $perpage, $offset, $matches, $query);
        } else {
            $rowtramites = Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), $perpage, $offset, '0', $query);
            $contador = Doctrine::getTable('Tramite')->findParticipadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
        }

        $config['total_rows'] = $contador;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['page_query_string'] = false;
        $config['query_string_segment'] = 'offset';
        $config['first_link'] = 'Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data = \Cuenta::configSegunDominio();

        $data['tramites'] = new LengthAwarePaginator(
            $rowtramites, // Only grab the items we need
            $contador, // Total items
            $perpage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
        $data['query'] = $query;
        $data['sidebar'] = 'buscador';
        $data['content'] = view('tramites.buscador', $data);
        $data['title'] = 'Buscador de proyectos';

        return view('layouts.procedure', $data);
    }

    public function buscador_mapa(Request $request, $offset = 0)
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $query = $request->input('query');
        $matches = "";
        $rowtramites = "";
        $contador = "0";
        $resultotal = "false";
        $perpage = 50;

        $page = $request->input('page', 1); // Get the ?page=1 from the url
        $offset = ($page * $perpage) - $perpage;

        if ($query) {
            $result = Tramite::search($query)->get();
            $matches = array();
            foreach($result as $resultado){
                array_push($matches, $resultado->id);
            }
            if(count($result) > 0){
                $resultotal = "true";
            }else{
                $resultotal = "false";
            }
        }

        if ($resultotal == 'true') {
            $contador = Doctrine::getTable('Tramite')->findParticipadosMatched(Auth::user()->id, Cuenta::cuentaSegunDominio(), $matches, $query)->count();
            $rowtramites = Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), $perpage, $offset, $matches, $query);
        } else {
            $rowtramites = Doctrine::getTable('Tramite')->findAllTramites(Cuenta::cuentaSegunDominio(), $perpage, $offset, '0', $query);
            $contador = Doctrine::getTable('Tramite')->findParticipadosALL(Auth::user()->id, Cuenta::cuentaSegunDominio())->count();
        }

        $config['total_rows'] = $contador;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['page_query_string'] = false;
        $config['query_string_segment'] = 'offset';
        $config['first_link'] = 'Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $data = \Cuenta::configSegunDominio();

        $data['tramites'] = new LengthAwarePaginator(
            $rowtramites, // Only grab the items we need
            $contador, // Total items
            $perpage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
        $data['query'] = $query;
        $data['sidebar'] = 'buscador_mapa';
        $data['content'] = view('tramites.buscador_mapa', $data);
        $data['title'] = 'Buscador de proyectos por mapa';

        return view('layouts.procedure', $data);
    }

    public function visualizar_bitacora( $tramite_id )
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $data = \Cuenta::configSegunDominio();

        $bitacora = \App\Helpers\Doctrine::getTable('Etapa')->verBitacora($tramite_id, Cuenta::cuentaSegunDominio());
        // $bitacora_str = str_replace('"', '', implode("<br />", array_column(json_decode($bitacora, true), 'content')));

        $data['tramite_id'] = $tramite_id;
        $data['bitacora'] = json_decode($bitacora, true);
        $data['content'] = view('tramites.visualizar_bitacora', $data);

        return view('layouts.procedure', $data);
    }

    public function agregar_bitacora(Request $request, $tramite_id, $redirect_to)
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $this->validate($request, [
            'contenido' => 'required',
            'escritor' => 'required'
        ]);

        $contenido = $request->input('contenido');
        $escritor = $request->input('escritor');

        $stmt = Doctrine_Manager::getInstance()->connection();
        $sql = "insert into bitacora (content, tramite_id, fecha, escritor) values ('" . $contenido . "', " . $tramite_id . ", '" . date ("Y-m-d H:i:s") . "', '" . $escritor . "');";
        $stmt->execute($sql);

        return redirect('tramites/' . $redirect_to);
    }

    public function disponibles()
    {
        if ( !(Auth::check() && Auth::user()->registrado) ) {
            return redirect('/');
        }

        $data = \Cuenta::configSegunDominio();
        $data['procesos'] = Doctrine::getTable('Proceso')->findProcesosDisponiblesParaIniciar(Auth::user()->id, Cuenta::cuentaSegunDominio(), 'nombre', 'asc');

        $data['sidebar'] = 'disponibles';
        $data['content'] = view('tramites.disponibles', $data);
        $data['title'] = 'Trámites disponibles a iniciar';

        return view('layouts.app', $data);
    }

    public function borrar_tramite(Request $request, $tramite_id)
    {
        $tramite = Doctrine::getTable('Tramite')->find($tramite_id);

        if (is_null($tramite->Proceso->eliminar_tramites) || !$tramite->Proceso->eliminar_tramites) {
            echo 'No tiene permisos para eliminar este tramite.';
            exit;
        }

        $request->validate(['motivo' => 'required']);

        // Auditar
        $fecha = new \DateTime ();
        $proceso = $tramite->Proceso;
        $registro_auditoria = new \AuditoriaOperaciones ();
        $registro_auditoria->fecha = \Carbon\Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        $registro_auditoria->operacion = 'Eliminación de Trámite';
        $registro_auditoria->motivo = $request->input('motivo');
        $usuario = Auth::user();
        $registro_auditoria->usuario = $usuario->nombres . ' ' . $usuario->apellido_paterno . ' ' . $usuario->apellido_materno .  ' <' . $usuario->email . '>';
        $registro_auditoria->proceso = $proceso->nombre;
        $cuenta = Cuenta::cuentaSegunDominio();
        $registro_auditoria->cuenta_id = $cuenta->id;

        // Detalles
        $tramite_array['proceso'] = $proceso->toArray(false);

        $tramite_array['tramite'] = $tramite->toArray(false);
        unset($tramite_array['tramite']['proceso_id']);

        $registro_auditoria->detalles = json_encode($tramite_array);
        $registro_auditoria->save();

        $tramite = Tramite::find($tramite_id);
        if($tramite)
            $tramite->delete();

        $request->session()->flash('status', 'Trámite eliminado exitosamente');

        return response()->json([
            'validacion' => true,
            'redirect' => url('/etapas/inbox')
        ]);
    }

    public function iniciar_post(Request $request, $proceso_id){
        Log::info('Iniciando proceso--'.$proceso_id);
        $data = $request->all();

        //Token
        if($request->has('token')){
            $existe_token = false;
            $api_token = $request->input('token');
            $cuenta = Cuenta::cuentaSegunDominio();

            if (!$cuenta->api_token)
                return response()->json(['status' => 'ERROR', 'message' => 'La cuenta no tiene configurado un token.'], 403);

            if ($cuenta->api_token != $api_token) {
                return response()->json(['status' => 'ERROR', 'message' => 'Token incorrecto.'], 403);
            }
            $existe_token = true;
            if(!$existe_token)
                return response()->json(['status' => 'ERROR', 'message' => 'Usuario no tiene permisos para ejecutar esta etapa.'], 403);
        }else{
            return response()->json(['status' => 'ERROR', 'message' => 'Solicitud sin datos de entrada.'], 403);
        }
        //Fin token

        $proceso = Doctrine::getTable('Proceso')->find($proceso_id);

        if (!$proceso->canUsuarioIniciarlo(Auth::user()->id)) {
            $url = $proceso->getTareaInicial()->acceso_modo == 'claveunica' ? route('login.claveunica').'?redirect='.route('tramites.iniciar', [$proceso->id]) : route('login').'?redirect='.route('tramites.iniciar', $proceso->id);
            return redirect()->away($url);
        }
        $bodyContent = $request->all();
        if($proceso->concurrente==1){
            $tramite = new \Tramite();
            $tramite->iniciar($proceso->id, $bodyContent);

            if(session()->has('redirect_url')){
                return redirect()->away(session()->get('redirect_url'));
            }
        }else{
            //Vemos si es que usuario ya tiene un tramite de proceso_id ya iniciado, y que se encuentre en su primera etapa.
            //Si es asi, hacemos que lo continue. Si no, creamos uno nuevo
            $tramite = Doctrine_Query::create()
                ->from('Tramite t, t.Proceso p, t.Etapas e, e.Tramite.Etapas hermanas')
                ->where('t.pendiente=1 AND p.activo=1 AND p.id = ? AND e.usuario_id = ?', array($proceso_id, Auth::user()->id))
                ->groupBy('t.id')
                ->having('COUNT(hermanas.id) = 1')
                ->fetchOne();

            if (!$tramite) {
                $tramite = new \Tramite();
                $tramite->iniciar($proceso->id, $bodyContent);

                if(session()->has('redirect_url')){
                    return redirect()->away(session()->get('redirect_url'));
                }
            }
        }
        $qs = $request->getQueryString();

        return redirect('etapas/ejecutar/' . $tramite->getEtapasActuales()->get(0)->id . ($qs ? '?' . $qs : ''));
    }

    public function eliminar_form($tramite_id){
        $data['tramite'] = $tramite_id;
        return view('stages.eliminar', $data);
    }
}

