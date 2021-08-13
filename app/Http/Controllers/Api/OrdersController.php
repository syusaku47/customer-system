<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TOrder;
use Illuminate\Http\Request;

/**
 * Class OrdersController<br>
 * 受注管理
 *
 * @package App\Http\Controllers\Api
 */
class OrdersController extends Controller
{
    /**
     * 受注情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $project_id 案件ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $project_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TOrder::search_one($request, $project_id);

            if (is_null($result)) {
                return $this->jsonResponse($request->path());
            }
            // 取得データ
            $this->_body['data'] = $result;

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 受注情報登録
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TOrder::upsert($request);
        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 受注情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 受注ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TOrder::upsert($request, $id);
            if ($result === '404') {
                $this->_status = 0;
                $this->_status_code = 404;
                $this->_messages[] = '更新対象のデータが存在しません.';
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
