<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MtbXmlRequest;
use App\Models\MtbXml;
use Illuminate\Http\Request;

/**
 * Class MtbXmlsController<br>
 * 見積定型,署名
 *
 * @package App\Http\Controllers\Api\Master
 */
class MtbXmlController extends Controller
{

    /**
     * 見積定型,署名情報1件取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            // パラメータから検索
            $result = MtbXml::search_one($id);

            if (isset($result["code"])) {
                if ($result["code"] === '404') {
                    $this->_status = 0;
                    $this->_status_code = 404;
                    $this->_messages[] = 'データが存在しません.';
                }

                return $this->jsonResponse($request->path());
            }
            // 取得データ
            $this->_body['data'] = $result;

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            $this->_messages[] = 'システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 見積定型,署名更新処理
     *
     * @param MtbXmlRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MtbXmlRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {

            // パラメータから登録
            $result = MtbXml::upsert($request, $id);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'データの更新に失敗しました';
            }

            if ($result["code"] === '404') {
                $this->_status = 0;
                $this->_status_code = 404;
                $this->_messages[] = '更新対象のデータが存在しません.';
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
