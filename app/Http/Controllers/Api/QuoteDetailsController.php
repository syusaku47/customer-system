<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TQuoteDetail;
use App\Models\TQuote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class QuoteDetailsController<br>
 * 見積管理<br>
 * 見積明細情報
 *
 * @package App\Http\Controllers\Api
 */
class QuoteDetailsController extends Controller
{
    /**
     * 見積明細情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TQuoteDetail::search_list($request, $id);

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
     * 見積明細情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @param int $detail_id 見積明細ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id, int $detail_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $result = TQuoteDetail::search_one($id, $detail_id);

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
     * 見積明細情報登録
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TQuoteDetail::upsert($request, $id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 見積明細情報更新
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @param int $detail_id 見積明細ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id, int $detail_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TQuoteDetail::upsert($request, $id, $detail_id);
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
     * 見積明細情報削除
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @param int $detail_id 見積明細ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, int $detail_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから削除
            TQuoteDetail::remove($id, $detail_id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 見積明細ツリー情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail_tree(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから検索
            $results = TQuoteDetail::search_detail_tree($request, $id);

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
            if ($id != 0) {
                // 総パーセント
                $this->_body['percent'] = round(mt_rand() / mt_getrandmax() * 100, 2); // TODO 計算ロジック確認要
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

    /**
     * 見積明細情報一括登録
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_list(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            TQuoteDetail::multi_insert_detail($request, $id);

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 見積印刷名称変更
     *
     * @param Request $request リクエストパラメータ
     * @param int $id 見積ID
     * @param int $detail_id 見積明細ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_name(Request $request, int $id, int $detail_id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから更新
            $result = TQuoteDetail::update_print_name($request, $id, $detail_id);
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

    public function xml_import(Request $request){

//        set_error_handler(function($errno, $errstr, $errfile, $errline) {
//            throw new Exception($errstr, $errno);
//        });
        try{

            $t_quotes = TQuote::all()->where('id','<',258);

            foreach ($t_quotes as $quote){

                $string = <<<XML
                        $quote->meisai
                        XML;

                $search = array("\0", "\x01", "\x02", "\x03", "\x04", "\x05","\x06", "\x07", "\x08", "\x0b", "\x0c", "\x0e", "\x0f");
                $string = str_replace($search, '', $string);
//                $string = utf8_encode($string);
    //            $xml = simplexml_load_string($data);
                $xml = simplexml_load_string($string);
                TQuoteDetail::insert([
                    'quote_id' => $quote->id,
                    'category_id' => $xml->mei->attributes()->daibunrui_id,
                    'sub_category_id' => $xml->mei->attributes()->tyubunrui_id,
                    'item_kubun' => $xml->mei->attributes()->shohin_kubun,
                    'koji_component_name' => $xml->mei->attributes()->name,
                    'print_name' => $xml->mei->attributes()->print_name,
                    'standard' => $xml->mei->attributes()->kikaku,
                    'quantity' => $xml->mei->attributes()->suryou,
                    'unit' => $xml->mei->attributes()->tani_id,
                    'quote_unit_price' => $xml->mei->attributes()->shikiri_kakaku,
                    'price' => $xml->mei->attributes()->kingaku,
                    'prime_cost' => $xml->mei->attributes()->genka,
                    'cost_amount' => $xml->mei->attributes()->genka_kei,
                    'gross_profit_amount' => 1,

                    'gross_profit_rate' => $xml->mei->attributes()->arari_ritu,
                    'category_index' => 1,
                    'sub_category_index' => 1,
                    'created_at' => now(),
                    'last_updated_by' => "桝田",


                ]);
//                $id = $xml->mei->attributes()->id;
//                $id2 =$id + $id;
//                dd($id2);

                $this->_body[$quote->id] = $xml;
            }

        } catch (\Exception $e) {
//            restore_error_handler();
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = $e->getMessage();
            $this->error($e);
        }
        return $this->jsonResponse($request->path());
    }
}
