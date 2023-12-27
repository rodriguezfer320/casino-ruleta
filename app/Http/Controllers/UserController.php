<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('show_users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create_user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->age = $request->age;
        $user->genero = $request->genero;
        $user->cash = $request->cash ?? 10000;
        $user->save();
        return redirect()->route('users.create')->with('success', 'El nuevo usuario se ha registrado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('edit_user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->age = $request->age;
        $user->genero = $request->genero;
        $user->cash = $request->cash;
        $user->update();
        return redirect()->route('users.index')->with('success', 'Los datos del usuario se actualizaron exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'El usuario fue eliminado correctamente.');
    }
}
