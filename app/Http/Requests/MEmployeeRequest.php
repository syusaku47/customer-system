<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class MEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        特にアクセス権限などを求めないリクエストであればtrue
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        // 編集時は異なるバリデーションルールを適用する
//        if ($request->method() === 'PUT') {
//            return [
//                'employee_cd' => 'required|max:2',
//            ];
//        }

        return [
            'employee_cd' => 'required|max:8',
            'new_password' => 'max:50',
            'confirm_password' => 'max:50',
            'name' => 'required|max:255',
            'short_name' => 'max:255',
            'furigana' => 'max:255',
            'job_title' => 'max:255',
            'mail_address' => 'required|email',
            'sales_target' => 'max:14',
            'store_id' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_cd' => '社員CD',
            'new_password' => '新しいパスワード',
            'confirm_password' => 'パスワード確認',
            'name' => '社員‗名称',
            'short_name' => '社員_略称',
            'furigana' => '社員‗フリガナ',
            'job_title' => '役職',
            'mail_address' => 'メールアドレス',
            'sales_target' => '売り上げ目標',
            'store_id' => '店舗',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
//        エラーメッセージを一つの配列に変換
        $messages = [];
        $data = $validator->errors()->toArray();
        foreach ($data as $values)
            foreach ($values as $value)
                array_push($messages, $value);
//
//      // header部
        $header = [
//            'request' => url()->current(),//$request->path()が使えないためコメントアウト
            'status' => 'FAILED',
            'status_code' => 422,
            'messages' => $messages,
        ];

        $res = response()->json(
            $header,
            422,
            [
                'Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE
        );
        throw new HttpResponseException($res);
    }
}
