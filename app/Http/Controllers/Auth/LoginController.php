<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MEmployee;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

//        ログインユーザー取得
        $current_user = MEmployee::where('mail_address',$request->mail_address)->first();

//        ログインユーザーのcompany_id = 1 をsessionに詰める
        session(['company_id' =>$current_user->company_id]);

//        ログインのレスポンス
        return $this->jsonResponse($request->path());

//        return "ログインしました";
//        return $request->wantsJson()
//                    ? new JsonResponse([], 204)
//                    : redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

//        ログアウトのレスポンス
        return $this->jsonResponse($request->path());
//        return $request->wantsJson()
//            ? new JsonResponse([], 204)
//            : redirect('/');
    }

    public function username()
    {
//        return 'email';
        return 'mail_address';//m_employeesのmail_addressに変更
    }
}
