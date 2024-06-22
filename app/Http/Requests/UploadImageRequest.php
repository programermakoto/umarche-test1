<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
            "image"=>"image|mimes:jpg,jpeg,png|max:2048",
            "files.*.image"=>" required|image|mimes:jpg,jpeg,png|max:2048"
            //images/create.blade.phpのname="files[][image]"に合わせた画像のバリデーションが必要だから追加してrequiredで必須にする！

        ];
    }
}
