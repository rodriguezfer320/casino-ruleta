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

            betButton.addEventListener('click', function() {
                // se deshabilita el boton
                this.disabled = true;

                console.log('Ronda: ' + roundSpan.innerText);
                console.log('Apuestas');

                for (let i = 0; i < users.length; i++) {
                    const userId = users[i].id;
                    const cashElemt = document.getElementById('cash' + userId);
                    let cash = parseInt(cashElemt.innerText.replace('$', ''));

                    // realiza la apuesta, si el usuario tiene dinero
                    if (cash > 0) {
                        let bet = cash;
                        let color = randomcolor();

                        // realiza la apuesta escogiendo un porcentaje aleatorio,
                        // solo si el usuario tiene mas de 1000
                        if (cash > 1000) {
                            bet = (cash * (randomBettingPercentage(8, 15) / 100)).toFixed(0);
                        }

                        // descuenta del dinero el valor de la apuesta
                        cash -= bet;
                        
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
                                bet,
                                color,
                                round: roundSpan.innerText
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);

                            // se añaden los datos al html
                            cashElemt.innerText = '$' + cash;
                            document.getElementById("bet" + userId).innerText = '$' + bet;
                            document.getElementById("color" + userId).innerText = color;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                }

                // se activa el boton de la ruleta
                rouletteButton.disabled = false;
            });

            rouletteButton.addEventListener('click', function() {
                // se deshabilita el boton
                this.disabled = true;
                
                let color;
                const colors = {
                    'VERDE': '#00ff00',
                    'ROJO': '#ff0000',
                    'NEGRO': '#000000'
                };

                console.log('Ruleta');

                const rouletteInterval = setInterval(function() {
                    color = randomcolor();
                    timeSpan.innerText = (parseFloat(timeSpan.innerText.replace(' sg', '')) + 0.1).toFixed(1) + ' sg';
                    rouletteResult.style.backgroundColor = colors[color];
                }, 100); // la ruleta se ejecuta cada 100 milisegundos

                setTimeout(function() {
                    clearInterval(rouletteInterval);

                    // actualiza el color obtenido
                    fetch('/ruleta/' + roundSpan.innerText, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            color
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        // se habilita el boton de terminar ronda
                        endRoundButton.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
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

                console.log('Terminar');

                for (let i = 0; i < users.length; i++) {
                    const userId = users[i].id;
                    const cashElemt = document.getElementById('cash' + userId);
                    const betElemt = document.getElementById('bet' + userId);
                    const colorElemt = document.getElementById('color' + userId);

                    let cash = parseInt(cashElemt.innerText.replace('$', ''));
                    let bet = parseInt(betElemt.innerText.replace('$', ''));
                    let result = colors[rgbToHex(rouletteResult.style.backgroundColor)];

                    // se actuliza la apuesta segun el resultado de la ruleta
                    if(colorElemt.innerText === result) {
                        bet *= result === 'VERDE' ? 15 : 2;
                    } else {
                        bet = 0;
                    }

                    // se suma la apuesta al dinero del usuario
                    cash += bet;

                    // se actualizan los datos de la apuesta
                    fetch('/terminar/' + roundSpan.innerText, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_user: userId,
                            bet
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        // se añaden los datos al html
                        cashElemt.innerText = '$' + cash;
                        betElemt.innerText = '$0';
                        colorElemt.innerText = 'NONE';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                // se restablecen las configuraciones
                betButton.disabled = false;
                roundSpan.innerText = parseInt(roundSpan.innerText) + 1;
                timeSpan.innerText = '0.0 sg';
                rouletteResult.style.backgroundColor = '#ccc';
            });

            function randomBettingPercentage(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function randomcolor() {
                const probabilityColors = {
                    'VERDE': 0.02,
                    'ROJO': 0.49,
                    'NEGRO': 0.49
                };
                const number = Math.random();
                let acumulativeProbability = 0;

                for (const [color, probability] of Object.entries(probabilityColors)) {
                    acumulativeProbability += probability;

                    if (number <= acumulativeProbability) {
                        return color;
                    }
                }
            }

            function rgbToHex(rgb) {
                const match = rgb.match(/(\d+), (\d+), (\d+)/);

                if (match) {
                    // Convierte a valores enteros
                    const r = parseInt(match[1], 10);
                    const g = parseInt(match[2], 10);
                    const b = parseInt(match[3], 10);

                    // Convierte a hexadecimal
                    const red = r.toString(16).padStart(2, '0');
                    const green = g.toString(16).padStart(2, '0');
                    const blue = b.toString(16).padStart(2, '0');

                    return `#${red}${green}${blue}`;
                }

                return null;
            }
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
