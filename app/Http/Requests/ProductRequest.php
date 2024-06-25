<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50'],

            'information' => ['required', 'string', 'max:1000'],//max1000文字

            'price' => ['required', 'integer'],//必須にしてintegerで数字

            'sort_order' => ['nullable', 'integer'],//nullableでnullでもOK!

            'quantity' => ['required', 'integer',"between:0,99"],

            'shop_id' => ['required', 'exists:shops,id'],//存在しているかどうかを確認するためにexists:shops,idと書く

            'category' => ['required', 'exists:secondary_categories,id'],//exists:secondary_categories,idでsecondary_categoriesテーブルの外部キーidと結ぶ

            'image1' => ['nullable', 'exists:images,id'],//画像1~4まであり,空でもOK!

            'image2' => ['nullable', 'exists:images,id'],

            'image3' => ['nullable', 'exists:images,id'],

            'image4' => ['nullable', 'exists:images,id'],

            "is_selling" => ["required","boolean"]
        ];
    }
}
