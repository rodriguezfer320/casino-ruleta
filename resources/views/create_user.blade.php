@extends('basic_template')

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desaparecer el mensaje después de 3 segundos (3000 milisegundos)
            setTimeout(function() {
                const successMessage = document.getElementById('successMessage');

                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }, 3000);
        });
    </script>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header mb-4 fw-bold text-center text-uppercase bg-secondary text-white">Registrar Usuario</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            
                            @if(session('success'))
                                <div class="alert alert-success" id="successMessage">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label font-weight-normal text-body">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Nombre de usuario">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label font-weight-normal text-body">Correo</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Correo">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label font-weight-normal text-body">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre">
                                </div>
                                <div class="col-md-6">
                                    <label for="lastname" class="form-label font-weight-normal text-body">Apellido</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Apellido">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="age" class="form-label font-weight-normal text-body">Edad</label>
                                    <input type="number" class="form-control" id="age" name="age" placeholder="Edad" min="1" max="100">
                                </div>
                                <div class="col-md-6">
                                    <label for="genero" class="form-label font-weight-normal text-body">Genero</label>
                                    <select class="form-control" id="genero" name="genero" placeholder="Seleccione un genero">
                                        <option value="hombre">Hombre</option>
                                        <option value="mujer">Mujer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cash" class="form-label font-weight-normal text-body">Dinero</label>
                                    <input type="number" class="form-control" id="cash" name="cash" placeholder="Dinero" value="10000" min="1">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success">Registrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection