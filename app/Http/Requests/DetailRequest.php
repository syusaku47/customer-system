<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class DetailRequest extends FormRequest
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
            'product_kubun' => 'required|integer|min:1|max:5',
            'category_id' => 'required|integer|min:1',
            'subcategory_id' => 'required|integer|min:1',
            'name' => 'required|max:255',
            'standard' => 'max:255',
            'quantity' => ['regex:/^[-]?\d{0,10}+(?:|\.\d{0,2}+)$/'],
            'credit_id' => 'integer|min:1',
            'quote_unit_price' => ['regex:/^[-]?\d{0,10}+(?:|\.\d{0,2}+)$/'],
            'prime_cost' => ['regex:/^[-]?\d{0,10}+(?:|\.\d{0,2}+)$/'],
            'valid_flag' => 'integer|min:0|max:1',
        ];

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'product_kubun' => '商品区分',
            'category_id' => '大分類名称',
            'subcategory_id' => '中分類名称',
            'name' => '名前',
            'standard' => '規格',
            'quantity' => '数量',
            'credit_id' => '単位',
            'quote_unit_price' => '見積単価',
            'prime_cost' => '原価',
            'valid_flag' => '有効フラグ',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.regex' => '数量の入力形式が間違ってます',
            'quote_unit_price.regex' => '見積単価の入力形式が間違ってます',
            'prime_cost.regex' => '原価の入力形式が間違ってます',
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
