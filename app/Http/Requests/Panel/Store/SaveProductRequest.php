<?php

namespace App\Http\Requests\Panel\Store;

use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProductRequest extends FormRequest
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

        if ($this->images != null)
            $this->merge(['images' => json_decode($this->images, true)]);
        else
            $this->merge(['images' => []]);
        return [
            'title' => 'required|string|unique:store_products,title,' . $this->product?->id,
            'status' => 'required|in:' . implode(',', Model::StatusItems),
            'category_id' => 'required|exists:store_categories,id',
            'brand_id' => 'required|exists:store_brands,id',
            'description' => 'required|string|min:15',
            'cover_img' => 'required|array|max:1|bail',
            'cover_img.*' => 'exists:upload_centers,id|bail',
            'images' => 'nullable|array|bail',
            'images.*' => 'exists:upload_centers,id|bail',
            'price' => 'required|numeric|min:1000',
            'discount_price' => 'required|numeric|min:0|max:' . $this->price,
            'stock' => 'required|numeric|min:0',
            'tags' => 'nullable',
            'special_offer' => 'in:active,inactive',
            'so_from' => [
                Rule::requiredIf($this->special_offer == 'active'),
            ],
            'so_to' => [
                Rule::requiredIf($this->special_offer == 'active'),
            ],
            /*'property_index' => 'nullable|array',
            'property_value' => [
                Rule::requiredIf(count($this->property_index ?? []) > 0),
                'array',
                'min:' . count($this->property_index ?? []),
                'max:' . count($this->property_index ?? []),
            ],
            'property_value.*' => 'required|string',
            'attribute_index' => 'nullable|array',
            'attribute_value' => [
                Rule::requiredIf(count($this->attribute_index ?? []) > 0),
                'array',
                'min:' . count($this->attribute_index ?? []),
                'max:' . count($this->attribute_index ?? []),
            ],
            'attribute_value.*' => 'required|string',
            'color_hexcode' => 'nullable|array',
            'color_title' => [
                Rule::requiredIf(count($this->color_hexcode ?? []) > 0),
                'array',
                'min:' . count($this->color_hexcode ?? []),
                'max:' . count($this->color_hexcode ?? []),
            ],
            'color_title.*' => 'required|string',
            'color_price' => [
                Rule::requiredIf(count($this->color_hexcode ?? []) > 0),
                'array',
                'min:' . count($this->color_hexcode ?? []),
                'max:' . count($this->color_hexcode ?? []),
            ],
            'color_price.*' => 'required|numeric|min:0',
            'color_stock' => [
                Rule::requiredIf(count($this->color_hexcode ?? []) > 0),
                'array',
                'min:' . count($this->color_hexcode ?? []),
                'max:' . count($this->color_hexcode ?? []),
            ],
            'color_stock.*' => 'required|numeric|min:0',*/
        ];
    }

    public function attributes()
    {
        return [
            'cover_img' => 'تصویر شاخص',
            'images' => 'تصاویر',
            'tags' => 'برچسب ها',
            'brand_id' => 'برند',
            'category_id' => 'دسته بندی',
            'status' => 'وضعیت',
            'stock' => 'موجودی',
            'discount_price' => 'مبلغ تخفیف',
            'price' => 'قیمت',
            'so_to' => 'تاریخ پایان پیشنهاد ویژه',
            'so_from' => 'تاریخ شروع پیشنهاد ویژه',
            'attribute_value.*' => 'مقدار مشخصه',
            'property_value.*' => 'مقدار ویژگی',
        ];
    }
}
