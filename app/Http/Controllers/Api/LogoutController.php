<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MEmployee;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = MEmployee::where("token",$token)->firstOrFail();
            if ($token && $user) {
                $user->token = null;
                $user->save();
                $this->_body['data'] = [];
                return $this->jsonResponse($request->path());
            }
        }catch(\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}
