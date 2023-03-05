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
            // Desktop
                'desktop' => 'required|array',
                'desktop.*.inventory' => 'required|string',
                'desktop.*.id' => 'sometimes|nullable',
                'desktop.*.slot_name' => 'required|string',
                'desktop.*.adunit_size' => 'sometimes|nullable',
                'desktop.*.creative_size' => 'sometimes|nullable',
                'desktop.*.template_id' => 'sometimes|nullable',
                'desktop.*.placement_id' => 'sometimes|nullable',
                'desktop.*.code' => 'sometimes|nullable',
            // Mobile
                'mobile' => 'required|array',
                'mobile.*.inventory' => 'required|string',
                'mobile.*.id' => 'sometimes|nullable',
                'mobile.*.slot_name' => 'required|string',
                'mobile.*.adunit_size' => 'sometimes|nullable',
                'mobile.*.creative_size' => 'sometimes|nullable',
                'mobile.*.template_id' => 'sometimes|nullable',
                'mobile.*.placement_id' => 'sometimes|nullable',
                'mobile.*.code' => 'sometimes|nullable',
            // AMP 
                'amp' => 'required|array',
                'amp.*.inventory' => 'required|string',
                'amp.*.id' => 'sometimes|nullable',
                'amp.*.slot_name' => 'required|string',
                'amp.*.adunit_size' => 'sometimes|nullable',
                'amp.*.creative_size' => 'sometimes|nullable',
                'amp.*.template_id' => 'sometimes|nullable',
                'amp.*.placement_id' => 'sometimes|nullable',
                'amp.*.code' => 'sometimes|nullable',
                
        ];
    }
}
