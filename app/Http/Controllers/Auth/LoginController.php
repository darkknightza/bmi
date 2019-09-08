<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:web')->except('logout');
    }
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
//             If the class is using the ThrottlesLogins trait, we can automatically throttle
//             the login attempts for this application. We'll key this by the username and
//             the IP address of the client making these requests into this application.
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
//             If the login attempt was unsuccessful we will increment the number of attempts
//             to login and redirect the user back to the login form. Of course, when this
//             user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */

    protected function attemptLogin(Request $request)
    {
        $user = \App\Models\User::where('username',$request->get('username'))
            ->first();
        if ($user) {
            if(Hash::check($request->get('password'), $user->password)){
                $this->guard()->login($user, $request->has('remember'));
                return true;
            }
            return false;
        }
        return false;
    }
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
//    protected function credentials(Request $request)
//    {
//        return $request->only($this->username(), 'password');
//    }

//    /**
//     * Get the login username to be used by the controller.
//     *
//     * @return string
//     */
    public function username()
    {
        return 'username';
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/');
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
}
