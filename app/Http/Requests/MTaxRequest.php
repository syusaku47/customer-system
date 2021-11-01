<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class MTaxRequest extends FormRequest
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
        $rules =[
            'start_date' => 'required|date',
        ];

        if ($this->has('tax_rate')) {
//            0.や1.はエラー。0.0や1.0　0.00や1.00は許可
            $rules['tax_rate'] = ['required','regex:/\A(0(\.\d{1,2})?|1(\.0{1,2})?)\z/','numeric'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'start_date' => '適用開始日',
            'tax_rate' => '消費税率',
        ];
    }

    public function messages(): array
    {
        return [
            'tax_rate.regex' => '消費税率の入力形式が間違ってます',
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
