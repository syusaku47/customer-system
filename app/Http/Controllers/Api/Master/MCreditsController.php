<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MCreditRequest;
use App\Models\MCredit;
use Illuminate\Http\Request;

class MCreditsController extends Controller
{
    /**
     * 単位マスタ情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // パラメータから検索
            $results = MCredit::search_list($request);

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
            $this->_messages[] = 'システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }


    /**
     * 単位マスタ登録処理
     *
     * @param MCreditRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonRespBonse
     */
    public function store(MCreditRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MCredit::upsert($request);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = '単位名称の新規登録処理に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の単位名称データが存在しています。新規登録処理を実行できません';
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = 'システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }


    /**
     * 単位マスタ更新処理
     *
     * @param MCreditRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MCreditRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            // パラメータから登録
            $result = MCredit::upsert($request, $id);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'データの更新に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の単位名称データが存在しています。更新処理を実行できません';
            }

            if ($result["code"] === '404') {
                $this->_status = 0;
                $this->_status_code = 404;
                $this->_messages[] = '更新対象のデータが存在しません.';
            }

            if ($result["code"] === '403') {
                $this->_status = 0;
                $this->_status_code = 403;
                $this->_messages[] = '更新権限がありません.';
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = 'システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}