<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CookieAuthenticationController extends Controller
{
    /**
     * CookieAuthenticationController constructor.
     * @param Auth $auth
     */
    public function __construct(
        private Auth $auth,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'mail_address' => ['required', 'email'],
            'password' => 'required',
        ]);

        if ($this->getGuard()->attempt($credentials)) {
            $request->session()->regenerate();

            //return new JsonResponse(['message' => 'ログインしました']);
            return $this->jsonResponse($request->path());
        }

        $this->_status = 0;
        $this->_status_code = 401;
        $this->_messages[] = "メールアドレスまたはパスワードが間違っています";
        return $this->jsonResponse($request->path());
        //throw new Exception('ログインに失敗しました。再度お試しください');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->getGuard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //return new JsonResponse(['message' => 'ログアウトしました']);
        return $this->jsonResponse($request->path());
    }

    /**
     * @return StatefulGuard
     */
    private function getGuard(): StatefulGuard
    {
        return $this->auth->guard(config('auth.defaults.guard'));
    }

    public function unauthenticated(Request $request): JsonResponse
    {
        $this->_status = 0;
        $this->_status_code = 401;
        $this->_messages[] = "認証に失敗しました";
        return $this->jsonResponse($request->path);
    }
}
