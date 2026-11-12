<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return ['customer_name' => 'required|string|max:150', 'customer_phone' => 'required|string|max:30', 'entry_date' => 'required|date', 'notes' => 'nullable|string|max:2000', 'items' => 'required|array|min:1', 'items.*.item_id' => 'required|distinct|exists:items,id', 'items.*.qty' => 'required|numeric|gt:0|max:99999'];
    }
}
