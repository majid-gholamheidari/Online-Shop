@extends('admin.layouts.master')

@section('title', 'فروشگاه | افزودن دسته بندی')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/panel/plugins/select2/select2.min.css') }}">
@endsection

@section('content')
    <div class="row" style="display: flex; justify-content: center">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">دسته بندی جدید</h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.store.category.store') }}" method="POST" class="ajax-form">
                        @csrf
                        <div class="form-group">
                            <label for="title">عنوان</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="عنوان">
                        </div>

                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="توضیحات"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="parent">دسته بندی والد</label>
                            <select class="form-control select2" id="parent" name="category_id">
                                <option value="" disabled selected hidden>انتخاب دسته بندی والد</option>
                                @foreach($categories->where('level', 1) as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @foreach($categories->where('level', 2)->where('category_id', $category->id) as $categoryLevel2)
                                        <option value="{{ $categoryLevel2->id }}">{{ $category->title . " > " . $categoryLevel2->title }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">وضعیت</label>
                            <select class="form-control select2" id="status" name="status">
                                <option value="">انتخاب وضعیت</option>
                                <option value="{{ \Illuminate\Database\Eloquent\Model::StatusItems[1] }}">فعال</option>
                                <option value="{{ \Illuminate\Database\Eloquent\Model::StatusItems[0] }}">غیر فعال
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
                            ])
                        <input type="hidden" name="cover_img" value="" id="cover_img">

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
