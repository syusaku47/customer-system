<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MProjectRankRequest;
use App\Models\MProjectRank;
use Illuminate\Http\Request;

/**
 * Class MProjectRanksController<br>
 * 案件ランクマスタ
 *
 * @package App\Http\Controllers\Api\Master
 */
class MProjectRanksController extends Controller
{
    /**
     * 案件ランクマスタ情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // パラメータから検索
            $results = MProjectRank::search_list($request);

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
     * 案件見込みランク登録処理
     *
     * @param MProjectRankRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonRespBonse
     */
    public function store(MProjectRankRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MProjectRank::upsert($request);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = '案件見込みランクの新規登録処理に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の案件見込みランクデータが存在しています。新規登録処理を実行できません';
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
     * 案件見込みランク更新処理
     *
     * @param MProjectRankRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MProjectRankRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            // パラメータから登録
            $result = MProjectRank::upsert($request, $id);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'データの更新に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の案件見込みランクデータが存在しています。更新処理を実行できません';
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