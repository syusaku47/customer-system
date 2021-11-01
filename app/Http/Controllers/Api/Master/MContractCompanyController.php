<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\MContractCompany;
use Illuminate\Http\Request;
use App\Http\Requests\MContractCompanyRequest;

/**
 * Class CustomersController<br>
 * 契約会社管理
 *
 * @package App\Http\Controllers\Api\Master
 */
class MContractCompanyController extends Controller
{


    /**
     * 契約会社情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        try {
            // パラメータから検索
            $results = MContractCompany::search_list($request);

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
            array_push($this->_messages[] ,'システムエラーが発生しました。管理者にご連絡ください。', $e->getMessage());
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 契約会社登録処理
     *
     * @param MContractCompanyRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MContractCompanyRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MContractCompany::upsert($request);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'データの新規登録に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の会社名が存在しています。新規登録処理を実行できません';
            }

        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            array_push($this->_messages[] ,'システムエラーが発生しました。管理者にご連絡ください。', $e->getMessage());
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 契約会社更新処理
     *
     * @param MContractCompanyRequest $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MContractCompany::upsert($request,$id);

            if ($result["code"] === 'fail') {
                $this->_status = 0;
                $this->_status_code = 500;
                $this->_messages[] = 'データの更新に失敗しました';
            }

            if ($result["code"] === 'err_name') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '同名の会社名が存在しています。更新処理を実行できません';
            }

            if ($result["code"] === '404') {
                $this->_status = 0;
                $this->_status_code = 404;
                $this->_messages[] = '更新対象のデータが存在しません.';
            }
        } catch (\Exception $e) {
            $this->_status = 0;
            $this->_status_code = 500;
            array_push($this->_messages[] ,'システムエラーが発生しました。管理者にご連絡ください。', $e->getMessage());
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }
}
