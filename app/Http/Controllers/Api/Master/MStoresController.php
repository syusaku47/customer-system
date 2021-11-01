<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MStoreRequest;
use App\Models\MStore;
use Illuminate\Http\Request;

class MStoresController extends Controller
{
    /**
     * 店舗マスタ情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // パラメータから検索
            $results = MStore::search_list($request);

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
            $this->_messages[] ='システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 店舗マスタ登録処理
     *
     * @param MStoreRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonRespBonse
     */
    public function store(MStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MStore::upsert($request);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = '店舗情報の新規登録処理に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の店舗が存在しています。登録処理を実行できません';
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] ='システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }


    /**
     * 店舗マスタ更新処理
     *
     * @param MStoreRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MStoreRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            // パラメータから登録
            $result = MStore::upsert($request, $id);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = '店舗情報の更新処理に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の店舗が存在しています。登録処理を実行できません';
            }

            if ($result["code"] === '404') {
                $this->_status = 0;
                $this->_status_code = 404;
                $this->_messages[] = '更新対象のデータが存在しません.';
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] ='システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }








    //    /**
//     * 店舗マスタ情報1件取得
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show(Request $request, int $id)
//    {
//        try {
//            // パラメータから検索
//            $result = MStore::search_one($id);
//
//            if (is_null($result)) {
//                return $this->jsonResponse($request->path());
//            }
//            // 取得データ
//            $this->_body['data'] = $result;
//
//        } catch (\Exception $e) {
//            $this->_status = 0;
//            $this->_status_code = 500;
//            $this->_messages[] = $e->getMessage();
//            $this->error($e);
//        }
//
//        return $this->jsonResponse($request->path());
//    }
}
