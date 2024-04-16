<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MomentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'media_id' => 'required|integer',
            'name' => 'required|string|max:32',
            'seconds' => 'required|integer'
        ];
    }
}
