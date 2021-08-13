<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MEmployee;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        try {
//            dd($request);
            $password = $request->new_password;
            $confirm_password = $request->new_password_conf;

//            確認パスワードと一致しているか
            if($password == $confirm_password){

                $email = $request->mail_address;
                if($email){
                    $user = MEmployee::where("mail_address", $email)->firstOrFail();
                    $user->password = $password;
                    $user->update();
                }
                $this->_body['data'] = [];
            }else{
                $this->_status = 0;
                $this->_status_code = 500;
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
