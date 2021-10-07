<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TCustomer;
use App\Models\TCustomerRankLog;
use App\Models\TMaintenance;
use App\Models\TOrder;
use App\Models\TProject;
use App\Models\TSupport;
use Illuminate\Http\Request;

/**
 * Class CsvOutputsController<br>
 * CSV出力管理
 *
 * @package App\Http\Controllers\Api
 */
class CsvOutputsController extends Controller
{
    /**
     * CSV出力情報（顧客情報）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_customer(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TCustomer::search_csv_list($request);

            $count = TCustomer::search_csv_list_count($request)->count();
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
     * CSV出力情報（誕生日リスト）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_birthday(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TCustomer::search_csv_list($request);

            $count = TCustomer::search_csv_list_count($request)->count();
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
     * CSV出力情報（結婚記念日リスト）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_wedding(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TCustomer::search_csv_list($request);

            $count = TCustomer::search_csv_list_count($request)->count();
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
     * CSV出力情報（案件情報）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_project(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TProject::search_csv_list($request);

            $count = TProject::search_csv_list_count($request)->count();
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
     * CSV出力情報（受注情報）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_order(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TOrder::search_csv_list($request);

            $count = TOrder::search_csv_list_count($request)->count();
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
     * CSV出力情報（未受注案件）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_notorder(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TOrder::search_csv_list($request);

            $count = TOrder::search_csv_list_count($request)->count();
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
     * CSV出力情報（失注情報）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_lostorder(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TOrder::search_csv_list($request);

            $count = TOrder::search_csv_list_count($request)->count();
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
     * CSV出力情報（メンテナンス情報）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_maintenance(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TMaintenance::search_csv_list($request);

            $count = TMaintenance::search_csv_list_count($request)->count();
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
     * CSV出力情報（顧客ランク更新ログ）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_custrankupdlog(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TCustomerRankLog::search_csv_list($request);

            $count = TCustomerRankLog::search_csv_list_count($request)->count();
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
     * CSV出力情報（対応履歴）一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_supported(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TSupport::search_csv_list($request);

            $count = TSupport::search_csv_list_count($request)->count();
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
}
