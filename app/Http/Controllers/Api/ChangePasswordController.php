<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
//use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|confirmed',
            ]);
            $user = \Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
    public function changePasswordFromMail(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'mail_address' => 'required|email',
                'password' => 'required|confirmed',
            ]);

            $status = Password::reset(
                $request->only('mail_address', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ]);
                    $user->save();
                    event(new PasswordReset($user));
                }
            );

            // エラーチェック
            if ($status === Password::INVALID_TOKEN) {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_body['data'] = [
                    'status'  => $status,
                    'message' => __($status)
                ];
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    public function checkExpiryToken(Request $request)
    {
       try {
            $request->validate([
                'token' => 'required',
                'mail_address' => 'required|email',
            ]);

            // ユーザー情報取得
            $user = Password::getUser($request->only('mail_address', 'token'));

            // トークンチェック
            if (!Password::tokenExists($user, $request->token)) {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_body['data'] = [
                    'status'  => Password::INVALID_TOKEN,
                    'message' => __(Password::INVALID_TOKEN)
                ];
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}
