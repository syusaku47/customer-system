<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TMaintenance;
use Illuminate\Http\Request;

/**
 * Class MaintenancesController<br>
 * メンテナンス管理
 *
 * @package App\Http\Controllers\Api
 */
class MaintenancesController extends Controller
{
    /**
     * メンテナンス情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TMaintenance::search_list($request);

            $count = TMaintenance::search_list_count($request)->count();
            if ($count == 0) {
                // 総件数
                $this->_body['hit_count'] = 0;
                // 取得データ
                $this->_body['data'] = [];
                return $this->jsonResponse($request->path());
            }
            // 総件数
            $this->_body['hit_count'] = $count;
            // 取得データ
            $this->_body['data'] = $results;

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * メンテナンス情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id メンテナンスID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TMaintenance::search_one($request, $id);

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
     * メンテナンス情報登録
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TMaintenance::upsert($request);
        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * メンテナンス情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id メンテナンスID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TMaintenance::upsert($request, $id);
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
