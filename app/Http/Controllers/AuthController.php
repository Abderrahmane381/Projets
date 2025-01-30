<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        // Validation des informations de connexion
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion de l'utilisateur avec les informations fournies
        if (Auth::attempt($credentials)) {
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();
        
            // Vérifier si l'utilisateur a un rôle dans la table model_has_roles
            $role = \DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->whereIn('role_id', [1, 2, 3]) // Exemple de rôle, ajustez selon votre base de données
                    ->first();

            // Si l'utilisateur a un rôle valide (par exemple, editor)
            if ($role) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            } else {
                Auth::logout(); // Déconnecter l'utilisateur s'il n'a pas de rôle valide
                return back()->withErrors([
                    'email' => 'You are not subscriber yet ,Wait until the editor admit you re subscription ',
                ]);
            }
        }

        // Si la connexion échoue, retour avec un message d'erreur
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        // Assign default subscriber role using Spatie
        

        

        return redirect()->route('home')->with('success', 'Wait until the editor admit you re subscription .');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
