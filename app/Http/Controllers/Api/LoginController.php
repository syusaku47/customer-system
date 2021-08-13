<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MEmployee;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {
            $email = $request->mail_address;
            $password = $request->password;
            $user = MEmployee::where("mail_address", $email)->firstOrFail();
            if ($user && ($password == $user->password)) {
                $token = Str::random(60);
                $user->token = $token;
                $user->save();

                $this->_body['data'] = [];
                return $this->jsonResponse($request->path());
            }else{
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = "メールアドレスとパスワードが一致していません";

            }
        }catch(\Exception $e) {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = $e->getMessage();
                $this->error($e);
            }

        return $this->jsonResponse($request->path());
    }

    public function get_user(Request $request){
        try {
           $user = MEmployee::find(1);
            $this->_body[] = $user;
        }catch(\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

}

