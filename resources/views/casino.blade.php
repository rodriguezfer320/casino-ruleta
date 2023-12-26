@extends('basic_template')

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function generarNumeroAleatorio(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function obtenerColorAleatorio() {
                var colores = ["verde", "rojo", "negro"];
                var indiceAleatorio = Math.floor(Math.random() * colores.length);
                return colores[indiceAleatorio];
            }

            var users = document.getElementsByClassName("users");

            // Itera sobre cada usuario
            for (var i = 0; i < users.length; i++) {
                var id = users[i].id;
                var cash = parseInt(document.getElementById("cash"+id).innerText.replace('$', ''));
                
                if (cash > 0) {
                    var min = 8;
                    var max = 15;
                    var bet = cash;
                    var stakePercentage = 0;
                    
                    if (cash > 1000) {
                        stakePercentage = generarNumeroAleatorio(min, max);
                        bet = cash * (stakePercentage / 100);
                        cash -= bet;
                    }

                    document.getElementById("cash"+id).innerText = '$' + cash;
                    document.getElementById("bet"+id).innerText = '$' + bet;
                    document.getElementById("color"+id).innerText = obtenerColorAleatorio();
                }
            }
        });
    </script>
@endsection

@section('content')
    @forelse ($users as $user)
        <div id={{ $user->id }} class="users">
            Usuario: <span id="username{{ $user->id }}">{{ $user->username }} </span>
            Dinero: <span id="cash{{ $user->id }}">${{ $user->cash }}</span>
            Apuesta: <span id="bet{{ $user->id }}">$0</span>
            Color: <span id="color{{ $user->id }}">none</span>
            <br>
        </div>
    @empty
        No se han creado usuarios
    @endforelse

@endsection
