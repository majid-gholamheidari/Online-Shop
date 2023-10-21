<?php

namespace App\Http\Controllers\Panel\Store;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\Store\SaveProductRequest;
use App\Models\Store\StoreBrand;
use App\Models\Store\StoreCategory;
use App\Models\Store\StoreProduct;
use App\Models\Store\StoreTag;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = StoreProduct::paginate(20);
        return view('admin.panel.store.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = StoreCategory::all();
        return view('admin.panel.store.product.create', compact('categories'));
    }

    public function brandsListAjax(Request $request)
    {
        $page = $request->page ?? 1;
        $resultCount = 25;
        $offset = ($page - 1) * $resultCount;
        $brands = StoreBrand::orWhere('title', 'LIKE',  '%' . $request->term . '%')
            ->orWhere('en_title', 'LIKE',  '%' . $request->term . '%')
            ->orderBy('title')
            ->skip($offset)
            ->take($resultCount)
            ->select('id', DB::raw("CONCAT(store_brands.title,' (',store_brands.en_title, ')') as text"))
            ->get();

        $count = StoreBrand::count();
        $endCount = $offset + $resultCount;
        $morePages = $endCount < $count;

        $results = array(
            "results" => $brands,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }

    public function tagsListAjax(Request $request)
    {
        $search = $request->search ?? null;
        $tags = StoreTag::where('title', 'LIKE', '%' . $search . '%')->pluck('title')->toArray();
        return response()->json($tags);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $colorFields = [
                count(array_filter($request->color_hexcode ?? [])),
                count(array_filter($request->color_title ?? [])),
                count(array_filter($request->color_price ?? [])),
                count(array_filter($request->color_stock ?? []))
            ];
            if(count(array_unique($colorFields)) != 1) {
                return Response::error('تمام فیلد های رنگ های موجود را تکمیل نمایید');
            }

            $propertyFields = [
                count(array_filter($request->property_value ?? [])),
                count(array_filter($request->property_index ?? []))
            ];
            if(count(array_unique($propertyFields)) != 1) {
                return Response::error('تمام فیلد های ویژگی های شاخص را تکمیل نمایید');
            }

            $attributeFields = [
                count(array_filter($request->attribute_value ?? [])),
                count(array_filter($request->attribute_index ?? []))
            ];
            if(count(array_unique($attributeFields)) != 1) {
                return Response::error('تمام فیلد های مشخصات را تکمیل نمایید');
            }

            $tagsId = StoreTag::whereIn('title', json_decode($request->tags, true) ?? [])
                ->pluck('id')
                ->toArray();
            $colors = [];
            foreach ($request->color_title ?? [] as $index => $color) {
                $colors[] = [
                    'hexcode' => $request->get('color_hexcode')[$index],
                    'price' => $request->get('color_price')[$index],
                    'stock' => $request->get('color_stock')[$index],
                    'title' => $color,
                ];
            }
            $properties = [];
            foreach ($request->property_index ?? [] as $index => $property) {
                $properties[] = [
                    'key' => $property,
                    'value' => $request->get('property_value')[$index]
                ];
            }
            $attributes = [];
            foreach ($request->attribute_index ?? [] as $index => $attribute) {
                $attributes[] = [
                    'key' => $attribute,
                    'value' => $request->get('attribute_value')[$index]
                ];
            }
            if ($request->special_offer == 'active') {
                $so_from = explode("/",$request->so_from);
                $so_from = Verta::jalaliToGregorian($this->faNumbersToEn($so_from[0]), $this->faNumbersToEn($so_from[1]), $this->faNumbersToEn($so_from[2]));
                $so_from = Carbon::create($so_from[0], $so_from[1], $so_from[2]);

                $so_to = explode("/",$request->so_to);
                $so_to = Verta::jalaliToGregorian($this->faNumbersToEn($so_to[0]), $this->faNumbersToEn($so_to[1]), $this->faNumbersToEn($so_to[2]));
                $so_to = Carbon::create($so_to[0], $so_to[1], $so_to[2]);
            } else {
                $so_from = null;
                $so_to = null;
            }
            $stock = $request->stock;
            if (count($colors) > 0) {
                $stock = -1;
            }
            $productData = [
                'code' => StoreProduct::codeGenerator(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'discount_percent' => (int)round(abs($request->discount_price * 100 / $request->price)),
                'stock' => $stock,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'images' => $request->images ?? [],
                'cover_img' => $request->cover_img[0],
                'tags' => $tagsId,
                'colors' => $colors,
                'properties' => $properties,
                'attributes' => $attributes,
                'special_offer' => $request->special_offer,
                'special_offer_from' => $so_from,
                'special_offer_to' => $so_to,
                'slug' => str_replace(' ', '-', $request->title)
            ];

            $product = StoreProduct::create($productData);
            DB::commit();
            return Response::success('اطلاعات با موفقیت ذخیره شد', null, route('admin.store.product.index'));
        } catch (\Exception $exception) {
            DB::beginTransaction();
            return Response::error($exception);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreProduct $product)
    {
        $categories = StoreCategory::all();
        return view('admin.panel.store.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $colorFields = [
                count(array_filter($request->color_hexcode ?? [])),
                count(array_filter($request->color_title ?? [])),
                count(array_filter($request->color_price ?? [])),
                count(array_filter($request->color_stock ?? []))
            ];
            if(count(array_unique($colorFields)) != 1) {
                return Response::error('تمام فیلد های رنگ های موجود را تکمیل نمایید');
            }

            $propertyFields = [
                count(array_filter($request->property_value ?? [])),
                count(array_filter($request->property_index ?? []))
            ];
            if(count(array_unique($propertyFields)) != 1) {
                return Response::error('تمام فیلد های ویژگی های شاخص را تکمیل نمایید');
            }

            $attributeFields = [
                count(array_filter($request->attribute_value ?? [])),
                count(array_filter($request->attribute_index ?? []))
            ];
            if(count(array_unique($attributeFields)) != 1) {
                return Response::error('تمام فیلد های مشخصات را تکمیل نمایید');
            }

            $tagsId = StoreTag::whereIn('title', json_decode($request->tags, true) ?? [])
                ->pluck('id')
                ->toArray();
            $colors = [];
            foreach ($request->color_title ?? [] as $index => $color) {
                $colors[] = [
                    'hexcode' => $request->get('color_hexcode')[$index],
                    'price' => $request->get('color_price')[$index],
                    'stock' => $request->get('color_stock')[$index],
                    'title' => $color,
                ];
            }
            $properties = [];
            foreach ($request->property_index ?? [] as $index => $property) {
                $properties[] = [
                    'key' => $property,
                    'value' => $request->get('property_value')[$index]
                ];
            }
            $attributes = [];
            foreach ($request->attribute_index ?? [] as $index => $attribute) {
                $attributes[] = [
                    'key' => $attribute,
                    'value' => $request->get('attribute_value')[$index]
                ];
            }
            if ($request->special_offer == 'active') {
                $so_from = explode("/",$request->so_from);
                $so_from = Verta::jalaliToGregorian($this->faNumbersToEn($so_from[0]), $this->faNumbersToEn($so_from[1]), $this->faNumbersToEn($so_from[2]));
                $so_from = Carbon::create($so_from[0], $so_from[1], $so_from[2]);

                $so_to = explode("/",$request->so_to);
                $so_to = Verta::jalaliToGregorian($this->faNumbersToEn($so_to[0]), $this->faNumbersToEn($so_to[1]), $this->faNumbersToEn($so_to[2]));
                $so_to = Carbon::create($so_to[0], $so_to[1], $so_to[2]);
            } else {
                $so_from = null;
                $so_to = null;
            }
            $stock = $request->stock;
            if (count($colors) > 0) {
                $stock = -1;
            }
            $productData = [
                'code' => StoreProduct::codeGenerator(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'discount_percent' => (int)round(abs($request->discount_price * 100 / $request->price)),
                'stock' => $stock,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'images' => $request->images ?? [],
                'cover_img' => $request->cover_img[0],
                'tags' => $tagsId,
                'colors' => $colors,
                'properties' => $properties,
                'attributes' => $attributes,
                'special_offer' => $request->special_offer,
                'special_offer_from' => $so_from,
                'special_offer_to' => $so_to,
                'slug' => str_replace(' ', '-', $request->title)
            ];

            $product = StoreProduct::create($productData);
            DB::commit();
            return Response::success('اطلاعات با موفقیت ذخیره شد', null, route('admin.store.product.index'));
        } catch (\Exception $exception) {
            DB::beginTransaction();
            return Response::error($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function faNumbersToEn($string)
    {
        return strtr($string, array('۰'=>'0', '۱'=>'1', '۲'=>'2', '۳'=>'3', '۴'=>'4', '۵'=>'5', '۶'=>'6', '۷'=>'7', '۸'=>'8', '۹'=>'9', '٠'=>'0', '١'=>'1', '٢'=>'2', '٣'=>'3', '٤'=>'4', '٥'=>'5', '٦'=>'6', '٧'=>'7', '٨'=>'8', '٩'=>'9'));
    }
}
