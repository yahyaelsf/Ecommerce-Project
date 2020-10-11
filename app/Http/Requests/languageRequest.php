<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class languageRequest extends FormRequest
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
            'name'=>'required|string|max:100|',
            'abdr'=>'required|max:10',
            'direction'=>'required',
          //  'active'=>'required',

        ];
    }
    public function messages()
    {
        return [
            'required'=>'هذا الحقل مطلوب',
            'name.max'=>'اسم اللغة لا يزيد عن 100 حرف',
           // 'in'=> 'القيم المدخلة غير صحيحة',
            'abdr.max'=> 'اسم اللغة لا يزيد عن 10 حرفة',
            'string'=>'يجب أن يكون الحقل نص ',

        ];
    }
}
