<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\MCustomerRankKoji;
use App\Models\MCustomerRankLastCompletion;
use Illuminate\Http\Request;

/**
 * Class MCustomerRanksController<br>
 * 顧客ランクマスタ<br>
 * （顧客ランク（工事金額）マスタ + 顧客ランク（最終完工日）マスタ）
 *
 * @package App\Http\Controllers\Api\Master
 */
class MCustomerRanksController extends Controller
{
    /**
     * 顧客ランクマスタ情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // パラメータから検索
            $results = MCustomerRankKoji::search_customer_rank_list($request);

//            $count = $results->count();
//            if ($count == 0) {
//                // 総件数
//                $this->_body['hit_count'] = 0;
//                // 取得データ
//                $this->_body['data'] = [];
//                return $this->jsonResponse($request->path());
//            }
//            // 総件数
//            $this->_body['hit_count'] = $count;
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
     * 顧客ランク自動更新
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function rank_update(Request $request)
    {
        try {
            // パラメータから登録
            $result = MCustomerRankKoji::auto_update($request);
//            dd( $result["code"]);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = '自動ランク付け失敗しました。';
            }
//
//            if ($result["code"] === '400') {
//                $this->_status = 0;
//                $this->_status_code = 400;
//                $this->_messages[] = '自動ランク付け失敗しました。';
//            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = 'システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }




}
