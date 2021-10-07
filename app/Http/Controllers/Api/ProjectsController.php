<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TProject;
use Illuminate\Http\Request;

/**
 * Class ProjectsController<br>
 * 案件管理
 *
 * @package App\Http\Controllers\Api
 */
class ProjectsController extends Controller
{
    /**
     * 案件情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TProject::search_list($request);

            $count = TProject::search_list_count($request)->count();
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
     * 案件情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 案件ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TProject::search_one($request, $id);

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
     * 案件情報登録
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TProject::upsert($request);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 案件情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 案件ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TProject::upsert($request, $id);
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
     * 案件ID発行
     *
     * @param Request $request リクエストパラメータ
     * @param int $customer_id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_id(Request $request, int $customer_id): \Illuminate\Http\JsonResponse
    {
        try {
            // 案件ID発行
            $this->_body['data'][]['id'] = TProject::get_id($request, $customer_id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 編集中案件情報削除
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 案件ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy_edit_data(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから削除
            TProject::remove_edit_data($id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}
