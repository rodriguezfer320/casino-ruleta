<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(5);
        $round = Bet::max('round') + 1 ?? 1;
        return view('casino', compact('users', 'round'));
    }

    /**
     * Save the bet placed by a user.
     */
    public function storeBet(Request $request)
    {
        $user = User::find($request->id_user);
        $data = ['message' => 'El usuario ' . $user->username . ' no puede apostar, porque no tiene dinero.'];

        if ($user->cash > 0) {
            // begin a transaction
            //DB::beginTransaction();

            // guarda la apuesta
            $bet = new BET();
            $bet->id_user = $user->id;
            $bet->bet = $this->generateBet($user->cash);
            $bet->color = $this->randomcolor();
            $bet->round = $request->round;
            $bet->result = 'NONE';
            $bet->earned_money = 0;
            $bet->status = 1;
            $bet->save();

            // descuenta el dinero apostado
            $user->cash -= $bet->bet;
            $user->update();

            // commit changes
            //DB::commit();

            $data = [
                'cash' => $user->cash,
                'bet' => $bet->bet,
                'color' => $bet->color
            ];
        }

        return response()->json($data);
    }

    /**
     * Update the result obtained in the roulette.
     */
    public function spinWheel(Request $request, string $round)
    {
        // obtiene un color aleatorio
        $color = $this->randomcolor();


        // actuliza el color de la ronda
        BET::where('round', $round)
            ->update([
                'result' => $color
            ]);
        
        return response()->json([
            'color' => $color
        ]);
    }

    /**
     * 
     */
    public function endRound(Request $request, string $round)
    {
        // actualiza la apuesta
        $bet = BET::where('id_user', $request->id_user)
                ->where('round', $round)
                ->first();

        $bet->earned_money = $this->payBet($bet->color, $bet->result, $bet->bet);
        $bet->status = 0;
        $bet->update();

        // se añade el dinero obtenido
        $user = $bet->user;
        $user->update([
            'cash' => $user->cash + $bet->earned_money
        ]);

        return response()->json([
            'cash' => $user->cash
        ]);
    }

    private function generateBet(int $cash) {
        return ($cash > 1000) ? round($cash * (rand(8, 15) / 100)) : $cash;
    }

    private function randomColor() {
        $probabilityColors = [
            'VERDE' => 0.02,
            'ROJO' => 0.49,
            'NEGRO' => 0.49
        ];

        $number = round(mt_rand() / mt_getrandmax(), 2);
        $acumulativeProbability = 0;

        foreach ($probabilityColors as $color => $probability) {
            $acumulativeProbability += $probability;

            if ($number <= $acumulativeProbability) {
                return $color;
            }
        }
    }

    private function payBet(string $color, string $result, int $bet) {
        return $bet * ($color == $result ? ($result === 'VERDE' ? 15 : 2) : 0);
    }
}
