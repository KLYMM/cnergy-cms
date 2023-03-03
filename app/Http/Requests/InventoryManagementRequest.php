<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryManagementRequest extends FormRequest
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
                'type' => 'array',
                'creative_size' => 'array',
                'code' => 'array',
                'created_by' => 'array',
                'template_id' => 'array',
                'adunit_size' => 'array',
                'placement_id' => 'array',
        ];
    }
}
