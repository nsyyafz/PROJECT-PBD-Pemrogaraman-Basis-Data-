<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Pure Raw SQL
    $user = DB::selectOne(
        'SELECT user.*, role.nama_role 
         FROM user 
         JOIN role ON user.idrole = role.idrole 
         WHERE user.username = ?',
        [$request->username]
    );

    if (!$user) {
        return redirect()->back()
            ->withErrors(['username' => 'Username tidak ditemukan.'])
            ->withInput();
    }

    // Cek password
    if (!Hash::check($request->password, $user->password)) {
        return redirect()->back()
            ->withErrors(['password' => 'Password salah.'])
            ->withInput();
    }

    // Simpan session
    $request->session()->put([
        'user_id' => $user->iduser,
        'user_name' => $user->username,
        'user_role' => $user->idrole,
        'user_role_name' => $user->nama_role,
    ]);

    // Redirect berdasarkan role
    switch ($user->idrole) {
        case 1:
            return redirect()->route('admin.dashboard')
                ->with('success', 'Login berhasil sebagai Admin!');
        case 2:
            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Login berhasil sebagai Superadmin!');
        default:
            return redirect()->route('home')
                ->with('success', 'Login berhasil!');
        }
    }
    public function logout(Request $request)
    {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Logout berhasil!');
    }
}