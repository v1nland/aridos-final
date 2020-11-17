@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1 class="title">Bienvenido a la plataforma Áridos</h1>
    {{--<div class="date"><i class="material-icons red">date_range</i></div>--}}
    <hr>
    <br>

    <div class="row">
        <div class="col-sm-12">
            @include('home.tramites', ['login' => false])
            <section id="simple-destacados">
                <div class="row">
                    <div class="col-md-12 item">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="media">
                                    <i class="icon-archivo"></i>

                                    <div class="media-body">
                                        <p class="card-text">
                                            Iniciar sesión
                                        </p>
                                        <p>Para acceder a todas las funcionalidades de la plataforma deber iniciar sesión previamente en el enlace indicado a continuación.</p>
                                        <p><a href="/login" style="text-decoration: underline;font-size: 14px;">Pincha AQUÍ</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

