<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TFamily;
use Illuminate\Http\Request;

/**
 * Class FamiliesController<br>
 * 顧客管理<br>
 * ご家族情報
 *
 * @package App\Http\Controllers\Api
 */
class FamiliesController extends Controller
{
    /**
     * ご家族情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TFamily::search_list($id);

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
     * ご家族情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @param int $family_id 家族ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id, int $family_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TFamily::search_one($id, $family_id);

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
     * ご家族情報登録
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TFamily::upsert($request, $id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * ご家族情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @param int $family_id 家族ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id, int $family_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TFamily::upsert($request, $id, $family_id);
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
     * ご家族情報削除
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @param int $family_id 家族ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, int $family_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから削除
            TFamily::remove($family_id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}
