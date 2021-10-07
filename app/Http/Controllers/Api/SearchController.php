<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TCustomer;
use App\Models\TProject;
use App\Models\TQuote;
use App\Models\TMaintenance;
use App\Models\TFile;
use App\Models\TSupport;


/**
 * Class SearchController<br>
 * 検索
 *
 * @package App\Http\Controllers\Api
 */
class SearchController extends Controller
{
    /**
     * フリーワード検索
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchFreeword(Request $request, String $page): \Illuminate\Http\JsonResponse
    {
        try {
            // 顧客
            if ($page == 'customer') {
                $results = TCustomer::search_list_freeword($request);

            // 案件
            } else if ($page == 'project') {
                $results = TProject::search_list_freeword($request);

            // 案件
            } else if ($page == 'quote') {
                $results = TQuote::search_list_freeword($request);

            // メンテナンス
            } else if ($page == 'maintenance') {
                $results = TMaintenance::search_list_freeword($request);

            // ファイル
            } else if ($page == 'file') {
                $results = TFile::search_list_freeword($request);

            // 対応履歴
            } else if ($page == 'supported') {
                $results = TSupport::search_list_freeword($request);
            }
            
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
