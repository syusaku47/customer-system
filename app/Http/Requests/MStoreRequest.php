<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class MStoreRequest extends FormRequest
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
            'name' => 'required|max:255',
            'furigana' => 'max:255',
            'short_name' => 'max:255',
            'tel_no' => 'max:20',
            'fax_no' => 'max:20',
            'free_dial' => 'max:20',
            'post_no' => 'regex:/\A\d{3}\d{4}\z/',
            'city' => 'max:255',
            'address' => 'max:255',
            'building_name' => 'max:255',
            'holder' => 'max:255',
            'bank_name' => 'max:255',
            'bank_store_name' => 'max:255',
            'account' => 'min:1|max:2',
            'bank_account_no' => 'max:7',
        ];

//            2~6までの銀行情報
        for ($i=2; $i<7; $i++) {
            $rules['bank_name'.$i] =  'max:255';
            $rules['bank_store_name'.$i] = 'max:255';
            $rules['account'.$i] =  'min:1|max:2';
            $rules['bank_account_no'.$i] = 'digits_between:1,7';
        }

        if ($this->has('order')) {
            $rules['order'] = 'integer|digits_between:1,3';
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [
            'name' => '名称',
            'furigana' => 'フリガナ',
            'short_name' => '略称',
            'tel_no' => '電話番号',
            'fax_no' => 'FAX番号',
            'free_dial' => 'フリーダイヤル',
            'post_no' => '郵便番号',
            'city' => '市区町村',
            'address' => '地名・番地',
            'building_name' => '建物名',
            'holder' => '口座名義',
            'bank_name' => '銀行名',
            'bank_store_name' => '店舗名',
            'account' => '口座',
            'bank_account_no' => '口座番号',
        ];

//            2~6までの銀行情報
        for ($i=2; $i<7; $i++) {
            $attributes['bank_name'.$i] =  '銀行名';
            $attributes['bank_store_name'.$i] = '店舗名';
            $attributes['account'.$i] =  '口座';
            $attributes['bank_account_no'.$i] = '口座番号';
        }

        return $attributes;

    }

    public function messages(): array
    {
        return [
            'post_no.regex' => '郵便番号の入力形式が間違ってます',
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
