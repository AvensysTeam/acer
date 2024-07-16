<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Section;

class SubsectionRequest extends FormRequest
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
            'title' => 'required|min:3|max:100',
            'subtitle' => 'required|min:3|max:100',
            'section_id' => 'required|exists:sections,id'
        ];
    }
}
