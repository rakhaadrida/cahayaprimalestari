<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
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
            // 'ukuran' => 'integer',
            // 'isi' => 'integer',
            // 'kapasitas' => 'integer'
            'harga.*' => 'required|integer',
            'ppn.*' => 'required|integer',
            'stok.*' => 'required|integer'
        ];
    }
}
