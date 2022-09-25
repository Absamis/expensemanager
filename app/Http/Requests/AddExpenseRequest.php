<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddExpenseRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            "merchant" => ["required", "max:30"],
            "amount" => ["required", "numeric", "min:1", "max:1000000000"],
            "date" => ["required", "date"],
            "remark" => ["required", "max:1500"],
            "receipt" => ["required", "mimes:png,jpg,jpeg", "mimetypes:image/png,image/jpg,image/jpeg", "max:10000"]
        ];
    }
}
