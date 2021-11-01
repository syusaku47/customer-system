<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\MEmployee;
use App\Http\Requests\MEmployeeRequest;
use Illuminate\Http\Request;

/**
 * Class MEmployeesController<br>
 * 社員マスタ
 *
 * @package App\Http\Controllers\Api
 */
class MEmployeesController extends Controller
{
    /**
     * 社員マスタ情報一覧取得
     *
     * @param Request $request リクエストパラメータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        try {

            // パラメータから検索
            $results = MEmployee::search_list($request);

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
            $this->_messages[] = 'システムエラーが発生しました。管理者にご連絡ください。';
            $this->error($e);
        }

        return $this->jsonResponse($request->path());
    }

    /**
     * 社員マスタ登録処理
     *
     * @param  \Illuminate\Http\MEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MEmployeeRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MEmployee::upsert($request);

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

            if ($result["code"] === 'account_over') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '作成できる社員数の上限に達しています。新規登録処理を実行できません';
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
     * 社員マスタ更新処理
     *
     * @param  \Illuminate\Http\MEmployeeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MEmployeeRequest $request,int $id): \Illuminate\Http\JsonResponse
    {
        try {
            // パラメータから登録
            $result = MEmployee::upsert($request,$id);

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

            if ($result["code"] === 'account_over') {
                $this->_status = 0;
                $this->_status_code = 400;
                $this->_messages[] = '作成できる社員数の上限に達しています。新規登録処理を実行できません';
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
