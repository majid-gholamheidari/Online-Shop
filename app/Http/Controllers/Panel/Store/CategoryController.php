<?php

namespace App\Http\Controllers\Panel\Store;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\Store\SaveCategoryRequest;
use App\Models\Store\StoreCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = StoreCategory::all();
        return view('admin.panel.store.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = StoreCategory::whereIn('level', [1, 2])->get();
        return view('admin.panel.store.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCategoryRequest $request)
    {
        $parent = StoreCategory::find($request->category_id);
        if ($parent) {
            $level = $parent->level + 1;
        } else {
            $level = 1;
        }
        StoreCategory::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'level' => $level,
            'img' => count($request->cover_img) > 0 ? $request->cover_img[0] : null
        ]);
        return Response::success("دسته بندی ایجاد گردید.", null, route('admin.store.category.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreCategory $category)
    {
        $categories = StoreCategory::whereIn('level', [1, 2])->get();
        return view('admin.panel.store.category.edit', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategory $category, SaveCategoryRequest $request)
    {
        $parent = StoreCategory::find($request->category_id);
        if ($parent) {
            $level = $parent->level + 1;
        } else {
            $level = 1;
        }
        $category->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'level' => $level,
            'img' => count($request->cover_img) > 0 ? $request->cover_img[0] : null
        ]);
        return Response::success("دسته بندی ویرایش گردید.", null, route('admin.store.category.edit', $category));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreCategory $category)
    {
        $category->delete();
        return Response::success("دسته بندی حذف گردید.", null, null);
    }
}
