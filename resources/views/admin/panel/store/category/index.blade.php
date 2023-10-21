@extends('admin.layouts.master')

@section('title', 'فروشگاه | دسته بندی ها')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/panel/plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">لیست دسته بندی های فروشگاه</h3>
                    <a href="{{ route('admin.store.category.create') }}" class="btn btn-success">افزودن</a>
                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>محصولات</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories->where('level', 1) as $category)
                            <tr class="bg-gray-active">
                                <td>{{ $category->id }}</td>
                                <td>
                                    {{ $category->title }}
                                </td>
                                <td>{{ $category->products()->count() ?? 0 }}</td>
                                <td>
                                    <span class="badge bg-{{ $category->status_class }}">{{ $category->fa_status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.store.category.edit', $category->id) }}" class="btn btn-success">
                                        <i class="fa fa-edit"></i>
                                        ویرایش
                                    </a>
                                    <button onclick="removeRecord(this)" data-url="{{ route('admin.store.category.destroy', $category->id) }}" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                        حذف
                                    </button>
                                    @if($category->children()->count() > 0)
                                    <button onclick="toggleDisplayChildren(this)" data-id="{{ $category->id }}" class="btn btn-warning show-child">
                                        <i class="fa fa-level-up" style="transform: rotate(180deg)"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @if($category->children()->count() > 0)
                                @foreach($category->children as $childCategory)
                                    <tr class="first-child bg-light-blue" data-parent="{{ $category->id }}" style="display: none;">
                                        <td>{{ $childCategory->id }}</td>
                                        <td>
                                            {{ $childCategory->title }}
                                        </td>
                                        <td>{{ $childCategory->products()->count() ?? 0 }}</td>
                                        <td>
                                            <span class="badge bg-{{ $childCategory->status_class }}">{{ $childCategory->fa_status }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.store.category.edit', $childCategory->id) }}" class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                                ویرایش
                                            </a>
                                            <button onclick="removeRecord(this)" data-url="{{ route('admin.store.category.destroy', $childCategory->id) }}" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                                حذف
                                            </button>
                                            @if($childCategory->children()->count() > 0)
                                                <button onclick="toggleDisplayChildren(this)" data-id="{{ $childCategory->id }}" class="btn btn-warning show-child">
                                                    <i class="fa fa-level-up" style="transform: rotate(180deg)"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($childCategory->children()->count() > 0)
                                        @foreach($childCategory->children as $lastChildCategory)
                                            <tr class="first-child bg-maroon" data-parent="{{ $childCategory->id }}" style="display: none;">
                                                <td>{{ $lastChildCategory->id }}</td>
                                                <td>
                                                    {{ $lastChildCategory->title }}
                                                </td>
                                                <td>{{ $lastChildCategory->products()->count() ?? 0 }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $lastChildCategory->status_class }}">{{ $lastChildCategory->fa_status }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.store.category.edit', $lastChildCategory->id) }}" class="btn btn-success">
                                                        <i class="fa fa-edit"></i>
                                                        ویرایش
                                                    </a>
                                                    <button onclick="removeRecord(this)" data-url="{{ route('admin.store.category.destroy', $lastChildCategory->id) }}" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                        حذف
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>محصولات</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/panel/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/datatables/fa_language_json.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/removeRecord.js') }}"></script>
    <script>
        /*$('#table').DataTable({
            searching: false,
            language: fa_language_json,
        });*/

        function toggleDisplayChildren(el) {
            let parent_id = $(el).data('id');
            if ($(`tr[data-parent='${parent_id}']`).first().is(":hidden")) {
                $(`tr[data-parent='${parent_id}']`).show();
            } else {
                $(`tr[data-parent='${parent_id}']`).hide();
            }
        }

    </script>
@endsection
