<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return ['category_id' => 'required|exists:categories,id', 'name' => 'required|string|max:120', 'price' => 'required|numeric|min:0.01|max:9999999999', 'is_active' => 'nullable|boolean'];
    }

    public function withValidator($validator): void
    {
        if ($this->route('item')) {
            $this->merge(['unit' => $this->route('item')->unit]);
        }
        $validator->after(function ($validator) {
            $category = \App\Models\Category::find($this->input('category_id'));
            if (!$category) return;
            $this->merge(['unit' => $category->type === 'kiloan' ? 'kg' : 'pcs']);
            $existing = \App\Models\Item::where('category_id', $category->id)->where('name', $this->input('name'))
                ->when($this->route('item'), fn ($query, $item) => $query->where('id', '!=', $item->id))->exists();
            if ($existing) $validator->errors()->add('name', 'Nama item sudah terdaftar pada kategori ini.');
        });
    }
}
