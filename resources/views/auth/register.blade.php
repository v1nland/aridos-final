@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-md-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{__('auth.register')}}</div>
                    <div class="card-body">
			<form role="form" method="POST" action="{{ url('registeradd') }}">
                            {{ csrf_field() }}
<div class="col-12">

                                                            <div class="form-group">
    <label for="usuario">Usuario</label>
    <input type="text" name="usuario" id="usuario" class="form-control" value="">
    </div>                            
                            <div class="form-group">
    <label for="password">Contraseña</label>
    <input type="password" name="password" id="password" class="form-control">
    </div>
<div class="form-group">
    <label for="password_confirmation">Confirmar Contraseña</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
    </div>                            <div class="form-group">
    <label for="nombres">Nombres</label>
    <input type="text" name="nombres" id="nombres" class="form-control" value="">
    </div>                            <div class="form-group">
    <label for="apellido_paterno">Apellido Paterno</label>
    <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control" value="">
    </div>                            <div class="form-group">
    <label for="apellido_materno">Apellido Materno</label>
    <input type="text" name="apellido_materno" id="apellido_materno" class="form-control" value="">
    </div>

                            <div class="form-group">
    <label for="email">Correo electrónico</label>
    <input type="email" name="email" id="email" class="form-control" value="">
    </div>

                  

                        </div>
			    <div class="form-group row">
                                <div class="col-lg-6 offset-lg-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{__('auth.register')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
