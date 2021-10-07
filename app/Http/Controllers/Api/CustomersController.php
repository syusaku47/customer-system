<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TCustomer;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class CustomersController<br>
 * 顧客管理
 *
 * @package App\Http\Controllers\Api
 */
class CustomersController extends Controller
{
    /**
     * 顧客情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TCustomer::search_list($request);

            $count = TCustomer::search_list_count($request)->count();
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
     * 顧客情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TCustomer::search_one($id);

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
     * 顧客情報登録
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = TCustomer::upsert($request);
            if ($result["code"] === 'err_tel_no') {
                $this->_status = 0;
                $this->_status_code = 400;
                array_push($this->_messages, '入力された電話番号は既に登録済みです', '今一度お確かめ下さい');
            }
            if ($result["code"] === 'err_geocoding') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = $result["name"].$result["keisho"].'で既に登録済みのジオコードです。';
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
     * 顧客情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
     public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
     {
         try {
             // パラメータから更新
             $result = TCustomer::upsert($request, $id);
             if ($result["code"] === '404') {
                 $this->_status = 0;
                 $this->_status_code = 404;
                 $this->_messages[] = '更新対象のデータが存在しません.';
             }
             if ($result["code"] === 'err_geocoding') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = $result["name"].$result["keisho"].'で既に登録済みのジオコードです。';
             }
            if ($result["code"] === 'err_tel_no') {
                $this->_status = 0;
                $this->_status_code = 400;
                array_push($this->_messages, '入力された電話番号は既に登録済みです', '今一度お確かめ下さい');
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
     * 顧客ID発行
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_id(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // 顧客ID発行
            $this->_body['data'][]['id'] = TCustomer::get_id($request);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 編集中顧客情報削除
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 顧客ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy_edit_data(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから削除
            TCustomer::remove_edit_data($id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }


    public function import_csv(Request $request)
    {
        try {
            if(TCustomer::csv_upsert($request)){
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_body['data'] = ["空です"];
            }else{
                $this->_body['data'] = [];
            }

        }catch(\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());

    }

    public function download_templete_csv(Request $request)
    {
        try {
            $results = TCustomer::all();
            TCustomer::download_csv($results);

        }catch(\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }


    public function import_csv_prod(Request $request)
    {
        try {
            if(TCustomer::csv_upsert($request,true)){
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_body['data'] = ["空です"];
            }else{
                $this->_body['data'] = [];
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
