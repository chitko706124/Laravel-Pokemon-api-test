<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItem extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('item');
        return [
            'name' => "required|max:20|unique:items,name,$id",
            'type' => 'required|in:water,fire,air,stone',
            'power' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'image' => 'required',
            'status' => 'required|in:active,inactive'
        ];
    }
}
