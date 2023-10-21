@extends('admin.layouts.master')

@section('title', 'فروشگاه | ویرایش دسته بندی')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/panel/plugins/select2/select2.min.css') }}">
@endsection

@section('content')
    <div class="row" style="display: flex; justify-content: center">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        دسته بندی:
                        {{ $category->title }}
                    </h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.store.category.update', $category->id) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="title">عنوان</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $category->title }}" placeholder="عنوان">
                        </div>

                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="توضیحات">{{ $category->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="parent">دسته بندی والد</label>
                            <select class="form-control select2" id="parent" name="category_id">
                                <option value="" disabled selected hidden>انتخاب دسته بندی والد</option>
                                @php($currentCategory = $category)
                                @foreach($categories->where('level', 1)->where('id', '!=', $currentCategory->id) as $category)
                                    <option s="{{ $currentCategory->category_id }}" @selected($category->id == $currentCategory->category_id) value="{{ $category->id }}">{{ $category->title }}</option>
                                    @foreach($categories->where('level', 2)->where('category_id', $category->id)->where('id', '!=', $currentCategory->id) as $categoryLevel2)
                                        <option @selected($categoryLevel2->id == $currentCategory->category_id) value="{{ $categoryLevel2->id }}">{{ $category->title . " > " . $categoryLevel2->title }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">وضعیت</label>
                            <select class="form-control select2" id="status" name="status">
                                <option value="">انتخاب وضعیت</option>
                                <option @selected($currentCategory->status == \Illuminate\Database\Eloquent\Model::StatusItems[1]) value="{{ \Illuminate\Database\Eloquent\Model::StatusItems[1] }}">فعال</option>
                                <option @selected($currentCategory->status == \Illuminate\Database\Eloquent\Model::StatusItems[0]) value="{{ \Illuminate\Database\Eloquent\Model::StatusItems[0] }}">غیر فعال
                                </option>
                            </select>
                        </div>
                        @include('admin.layouts.components.uploader',
                            [
                                'uploader_title' => 'تصویر شاخص',
                                'uploader_accepts' => '.jpg,.jpeg,.png',
                                'uploader_multiple' => false,
                                'uploader_target' => 'cover_img',
                                'uploader_model' => class_basename(\App\Models\Store\StoreCategory::class),
                                'uploader_items' => array_filter([$currentCategory->img])
                            ])
                        <input type="hidden" name="cover_img" value='@if($currentCategory->img)@json(array_filter([$currentCategory->img]))@endif' id="cover_img">

                        @include('admin.layouts.components.form-buttons')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/panel/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/select2/i18n/fa.js') }}"></script>
    <script>
        $('.select2').select2({
            dir: "rtl",
            language: "fa"
        })
    </script>
@endsection
