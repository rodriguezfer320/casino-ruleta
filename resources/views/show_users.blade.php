@extends('basic_template')

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Desaparecer el mensaje después de 3 segundos (3000 milisegundos)
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none';
            }, 3000);
        });
    </script>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header mb-4 fw-bold text-center text-uppercase bg-secondary text-white">Usuarios Registrados</div>

            @if(session('success'))
                <div class="alert alert-success" id="successMessage">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de usuario</th>
                            <th>Correo</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Edad</th>
                            <th>Genero</th>
                            <th>Dinero</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->lastname }}</td>
                                <td>{{ $user->age }}</td>
                                <td>{{ $user->genero }}</td>
                                <td>{{ $user->cash }}</td>
                                <th>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')                                
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <!-- Enlace "Anterior" -->
                        @if ($users->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        <!-- Números de página -->
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Enlace "Siguiente" -->
                        @if ($users->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    @endsection
