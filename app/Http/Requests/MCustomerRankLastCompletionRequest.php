<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class MCustomerRankLastCompletionRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:10',
            'abbreviation' => 'required|string|max:1',
            'date' => 'required|integer|digits_between:0,10',
            'is_valid' => 'integer|between:0,1',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '顧客ランク（最終完工日）名',
            'abbreviation' => '略式表示',
            'date' => '最終完工日数',
            'is_valid' => '有効フラグ',
        ];
    }

    public function messages(): array
    {
        return [
            'is_valid.between' => '有効フラグの値が不正です',
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
