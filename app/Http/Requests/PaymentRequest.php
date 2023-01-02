<?php

namespace App\Http\Requests;

use App\Http\Helpers\Balance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "type" => "numeric|max:2|min:0|required_without_all:number_of_people",
            "amount" => "numeric|required",
            "number_of_people" => "nullable|numeric|min:2|required_if:type,2"
        ];
    }
}
