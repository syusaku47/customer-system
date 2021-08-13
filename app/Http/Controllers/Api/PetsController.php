<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TPet;

/**
 * Class PetsController<br>
 * 顧客管理<br>
 * ペットデータ
 *
 * @package App\Http\Controllers\Api
 */
class PetsController extends Controller
{
    /**
     * ペット情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TPet::search_list($id);

            $count = $results->count();
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
     * ペット情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @param int $pet_id ペットID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id, int $pet_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TPet::search_one($id, $pet_id);

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
     * ペット情報登録
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TPet::upsert($request, $id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * ペット情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @param int $pet_id ペットID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id, int $pet_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TPet::upsert($request, $id, $pet_id);
            if ($result == '404') {
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

    /**
     * ペット情報削除
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @param int $pet_id ペットID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, int $pet_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから削除
            TPet::remove($pet_id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}
