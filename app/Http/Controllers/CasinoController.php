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
        $user = null;

        DB::transaction(function () use ($request, &$user) {
            // guarda la apuesta
            $bet = new BET();
            $bet->id_user = $request->id_user;
            $bet->bet = $request->bet;
            $bet->color = $request->color;
            $bet->round = $request->round;
            $bet->save();

            // descuenta el dinero apostado
            $user = $bet->user;
            $user->update([
                'cash' => $user->cash - $bet->bet
            ]);
        });

        return response()->json('La apuesta del usuario ' . optional($user)->username . ', se guardo correctamente.');
    }

    /**
     * Update the result obtained in the roulette.
     */
    public function spinWheel(Request $request, string $round)
    {
        BET::where('round', $round)
            ->update([
                'result' => $request->color
            ]);
        return response()->json('El resultado de la ruleta se actualizo correctamente.');
    }

    /**
     * 
     */
    public function endRound(Request $request, string $round)
    {
        $user = null;

        DB::transaction(function () use ($request, $round, &$user) {
            // actualiza la apuesta
            $bet = BET::where('id_user', $request->id_user)
                    ->where('round', $round)
                    ->first();

            $bet->earned_money = $request->bet;
            $bet->status = 0;
            $bet->update();

            // se añade el dinero obtenido
            $user = $bet->user;
            $user->update([
                'cash' => $user->cash + $bet->earned_money
            ]);
        });

        return response()->json('La apuesta del usuario ' . optional($user)->username . ', se actualizo correctamente.');
    }
}
