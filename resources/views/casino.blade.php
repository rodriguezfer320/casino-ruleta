@extends('basic_template')

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const betButton = document.getElementById('bet');
            const rouletteButton = document.getElementById('roulette');
            const endRoundButton = document.getElementById('endRound');
            const roundSpan = document.getElementById('round');
            const timeSpan = document.getElementById('time');
            const rouletteResult = document.getElementById('_roulette');
            const users = document.getElementsByClassName('users');
            let status = false;
            let gameInterval;

            betButton.addEventListener('click', function() {
                // se deshabilita el boton
                this.disabled = true;
                let count = 0;
                status = true;

                for (let i = 0; i < users.length; i++) {
                    const userId = users[i].id;
                    const cashElemt = document.getElementById('cash' + userId);
                    let cash = parseInt(cashElemt.innerText.replace('$', ''));

                    // realiza la apuesta, si el usuario tiene dinero
                    if (cash > 0) {                        
                        // se guardan los datos de la apuesta
                        fetch('/apuesta', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id_user: userId,
                                round: roundSpan.innerText
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // se añaden los datos al html
                            cashElemt.innerText = '$' + data.cash;
                            document.getElementById("bet" + userId).innerText = '$' + data.bet;
                            document.getElementById("color" + userId).innerText = data.color;

                            // se activa el boton de la ruleta
                            if (count === (users.length - 1)) {
                                rouletteButton.disabled = false;
                            }

                            count++;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                }
            });

            rouletteButton.addEventListener('click', function() {
                // se deshabilita el boton
                this.disabled = true;

                const colors = {
                    'VERDE': '#00ff00',
                    'ROJO': '#ff0000',
                    'NEGRO': '#000000'
                };

                const rouletteInterval = setInterval(function() {
                    fetch('/ruleta/' + roundSpan.innerText, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        rouletteResult.style.backgroundColor = colors[data.color];
                        timeSpan.innerText = (parseFloat(timeSpan.innerText.replace(' sg', '')) + 0.1).toFixed(1) + ' sg';

                        // se habilita el boton terminar
                        if (timeSpan.innerText === '10.0 sg') {
                            endRoundButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });                    
                }, 100); // la ruleta se ejecuta cada 100 milisegundos

                setTimeout(function() {
                    clearInterval(rouletteInterval);                 
                }, 10000); // la ruleta se detiene despues de 10 segundos
            });

            endRoundButton.addEventListener('click', function() {
                // se deshabilita el boton
                this.disabled = true;

                const colors = {
                    '#00ff00': 'VERDE',
                    '#ff0000': 'ROJO',
                    '#000000': 'NEGRO'
                };

                let count = 0;

                for (let i = 0; i < users.length; i++) {
                    const userId = users[i].id;

                    // se actualizan los datos de la apuesta
                    fetch('/terminar/' + roundSpan.innerText, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_user: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // se añaden los datos al html
                        document.getElementById('cash' + userId).innerText  = '$' + data.cash;
                        document.getElementById('bet' + userId).innerText = '$0';
                        document.getElementById('color' + userId).innerText = 'NONE';

                        // se restablecen las configuraciones
                        if (count === (users.length - 1)) {
                            status = false;
                            betButton.disabled = false;
                            roundSpan.innerText = parseInt(roundSpan.innerText) + 1;
                            timeSpan.innerText = '0.0 sg';
                            rouletteResult.style.backgroundColor = '#ccc';
                        } 

                        count++;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });

            function automaticallyRunGame() {
                if (!status) {
                    gameInterval = setInterval(function() {
                        if(!betButton.disabled) {
                            betButton.click();
                        } else if(!rouletteButton.disabled) {
                            rouletteButton.click();
                        } else if(!endRoundButton.disabled) {
                            endRoundButton.click();
                            clearInterval(gameInterval);
                        }
                    }, 100); // se ejecuta cada 100 milisegundos
                }
            }

            // ejecuta el juego al recargar la pagina
            window.onload = automaticallyRunGame;

            // ejecuta el juego automaticamente cada 3 minutos
            setInterval(automaticallyRunGame, 180000);
        });
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header mb-4 fw-bold text-center text-uppercase bg-secondary text-white">Casino Ruleta</div>
        <div class="card-body">
            @forelse ($users as $user)
                <div id={{ $user->id }} class="users">
                    Usuario: <span>{{ $user->username }} </span>
                    Dinero: <span id="cash{{ $user->id }}">${{ $user->cash }}</span>
                    Apuesta: <span id="bet{{ $user->id }}">$0</span>
                    Color: <span id="color{{ $user->id }}">NONE</span>
                    <br>
                </div>
            @empty
                No se han registrado usuarios
            @endforelse
        </div>
        <div class="card-footer">
            @if (count($users) > 0)
                <div class="row p-0 m-0">
                    <div class="col-sm-2 m-2">
                        <button type="button" id="bet" class="btn btn-primary">Realizar apuestas</button>
                    </div>
                    <div class="col-sm-2 m-2">
                        <button type="button" id="roulette" class="btn btn-success" disabled>Iniciar ruleta</button>
                    </div>
                    <div class="col-sm-2 m-2">
                        <button type="button" id="endRound" class="btn btn-danger" disabled>Terminar ronda</button>
                    </div>
                    <div class="col-sm-2 m-1">
                        <b>Ronda: </b><span id="round">{{ $round }}</span><br>
                        <b>Tiempo: </b><span id="time">0.0 sg</span>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-4 m-2">
                                <b>Resultado: </b>
                            </div>
                            <div class="col-sm-1">
                                <div id="_roulette"
                                    style="width: 50px; height: 50px; background-color: #ccc; border-radius: 50%; position: relative;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
