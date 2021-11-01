<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MAreaRequest;
use App\Models\MArea;
use Illuminate\Http\Request;

/**
 * Class MAreasController<br>
 * エリアマスタ
 *
 * @package App\Http\Controllers\Api
 */
class MAreasController extends Controller
{
    /**
     * エリアマスタ情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // パラメータから検索
            $results = MArea::search_list($request);

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
     * エリアマスタ登録処理
     *
     * @param MAreaRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonRespBonse
     */
    public function store(MAreaRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MArea::upsert($request);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'エリア名称の新規登録処理に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名のエリア名称データが存在しています。新規登録処理を実行できません';
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
     * エリアマスタ更新処理
     *
     * @param MAreaRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MAreaRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            // パラメータから登録
            $result = MArea::upsert($request, $id);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'エリア名称の更新処理に失敗しました。';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名のエリア名称データが存在しています。更新処理を実行できません';
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
