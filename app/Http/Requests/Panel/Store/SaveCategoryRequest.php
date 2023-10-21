<?php

namespace App\Http\Requests\Panel\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class SaveCategoryRequest extends FormRequest
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
        if ($this->cover_img != null)
            $this->merge(['cover_img' => json_decode($this->cover_img, true)]);
        else
            $this->merge(['cover_img' => []]);
        return [
            'title' => 'required|unique:store_categories,title,' . $this->category?->id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:store_categories,id',
            'status' => 'required|in:' . implode(',', Model::StatusItems),
            'cover_img' => 'nullable|array|max:1|bail',
            'cover_img.*' => 'exists:upload_centers,id|bail',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'دسته بندی والد',
            'status' => 'وضعیت',
            'cover_img' => 'تصویر شاخص',
            'cover_img.*' => 'تصویر شاخص',
        ];
    }
}
