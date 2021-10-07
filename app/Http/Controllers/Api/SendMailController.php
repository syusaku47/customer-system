<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MEmployee;
use App\Models\MStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * Class SendMailController
 * メール送信
 *
 * @package App\Http\Controllers\Api
 */
class SendMailController extends Controller
{
    /**
     * 
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    }

    /**
     * パスワード再設定メール
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset_password(Request $request)
    {
        $request->validate([
            'mail_address' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('mail_address')
        );

        $this->_body['data'] = [
            'status'  => $status,
            'message' => __($status)
        ];

        return $this->jsonResponse($request->path());
    }
}
