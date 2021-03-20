<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
            'bill_identifier' => 'required|min:3',
            'bill_date' => 'required|date',
            'bill_amount' => 'required|numeric|min:0,01',
            'bill_type' => 'required',
            'bill_stats' => 'required'
        ];
    }
}
