<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if(auth()->id()){
            return redirect('/');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'username' => 'required',
            'password' => 'required'
        ])->validate();
        $username = strtolower($data['username']);
        $remember = $request->get('remember', false);
        $password = $data['password'];
        $user = User::query()->where("username", $username)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {
                auth('web')->login($user, $remember);
                return redirect()->intended('/home');
            }
        }
        return back()->withInput($request->only('username', 'remember'))
            ->with('success', false)->with('message', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }

    public function logout()
    {
        auth()->guard('web')->logout();
        return redirect()->route('login');
    }

    public function welcome()
    {
        return view('home');
    }

    public function unauthorized()
    {
        return view('unauthorized');
    }
}
