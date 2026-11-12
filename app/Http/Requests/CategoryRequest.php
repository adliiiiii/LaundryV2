<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('category')?->id;
        return ['name' => 'required|string|max:120|unique:categories,name,'.$id, 'type' => 'required|in:satuan,kiloan', 'estimasi_hari' => 'required|integer|min:1|max:365', 'is_active' => 'nullable|boolean'];
    }
}
