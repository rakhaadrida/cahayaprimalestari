<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'nama' => 'required|max:255',
            'alamat' => 'required|max:255',
            'telepon' => 'required|max:15',
            'contact_person' => 'required|max:30',
            'limit' => 'integer',
            'id_sales' => 'required|exists:sales,id'
        ];
    }
}