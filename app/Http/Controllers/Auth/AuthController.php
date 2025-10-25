<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Query murni tanpa query builder
        $user = DB::select("
            SELECT u.*, r.nama_role 
            FROM user u
            INNER JOIN role r ON u.idrole = r.idrole
            WHERE u.username = ?
            LIMIT 1
        ", [$request->username]);

        if (count($user) > 0) {
            $user = $user[0];
            
            // Verifikasi password
            if (Hash::check($request->password, $user->password)) {
                Session::put('user_id', $user->iduser);
                Session::put('username', $user->username);
                Session::put('role', $user->nama_role);
                Session::put('idrole', $user->idrole);

                // Redirect berdasarkan role
                if (strtolower($user->nama_role) == 'superadmin') {
                    return redirect()->route('superadmin.dashboard')->with('success', 'Login berhasil!');
                } else {
                    return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
                }
            }
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}