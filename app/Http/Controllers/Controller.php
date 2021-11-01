<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

/**
 * Class Controller<br>
 * ベースControllerクラス
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // ステータス
    protected $_status = 1;
    // ステータスコード
    protected $_status_code = 200;
    // エラーメッセージ
    protected $_messages = [];
    // ボディ
    protected $_body = [];

    /**
     * レスポンスJSONの整形
     *
     * @param $path string リクエストパス
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse(string $path): \Illuminate\Http\JsonResponse
    {
        // header部
        $header = [
            'request' => $path,
            'status'  => self::getStatusName($this->_status),
            'status_code' => $this->_status_code,
            'messages' => $this->_messages,
        ];
        // body部
        $result = [
            'header'   => $header,
            'body'     => $this->_body,
        ];

        return response()->json(
            $result,
            $this->_status_code,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * ステータス名称取得
     *
     * @param $status
     * @return string
     */
    private function getStatusName($status): string
    {
        $statusName = '';
        switch ($status)
        {
            case 0:
                // 失敗
                $statusName = 'FAILED';
                break;
            case 1:
                // 正常終了
                $statusName = 'SUCCESS';
                break;
        }

        return $statusName;
    }

    /**
     * エラー時例外処理
     *
     * @param $e
     */
    protected function error($e)
    {
        Log::error("************************************************************************");
        $msg = $e->getMessage();
        if ( ! empty($msg))
        {
            Log::error("MESSAGE\t:".$e->getMessage());
        }
        Log::error("FILE\t:".$e->getFile());
        Log::error("LINE\t:".$e->getLine());
        Log::error("TRACE\t:\r\n".$e->getTraceAsString());
        Log::error("************************************************************************");
    }

}
