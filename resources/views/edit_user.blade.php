@extends('basic_template')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header mb-4 fw-bold text-center text-uppercase bg-secondary text-white">Editar Datos del Usuario</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label font-weight-normal text-body">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Nombre de usuario" value="{{ $user->username }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label font-weight-normal text-body">Correo</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Correo" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label font-weight-normal text-body">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" value="{{ $user->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="lastname" class="form-label font-weight-normal text-body">Apellido</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Apellido" value="{{ $user->lastname }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="age" class="form-label font-weight-normal text-body">Edad</label>
                                    <input type="number" class="form-control" id="age" name="age" placeholder="Edad" min="1" max="100" value="{{ $user->age }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="genero" class="form-label font-weight-normal text-body">Genero</label>
                                    <select class="form-control" id="genero" name="genero" placeholder="Seleccione un genero">
                                        <option value="hombre" @if($user->genero == 'hombre') selected @endif>Hombre</option>
                                        <option value="mujer" @if($user->genero == 'mujer') selected @endif>Mujer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cash" class="form-label font-weight-normal text-body">Dinero</label>
                                    <input type="number" class="form-control" id="cash" name="cash" placeholder="Dinero" value="{{ $user->cash }}" min="1">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success">Actualizar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection