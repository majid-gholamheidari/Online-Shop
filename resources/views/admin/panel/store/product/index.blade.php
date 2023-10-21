@extends('admin.layouts.master')

@section('title', 'فروشگاه | محصولات')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/panel/plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">لیست محصولات فروشگاه</h3>
                    <a href="{{ route('admin.store.product.create') }}" class="btn btn-success">افزودن</a>
                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>عنوان</th>
                            <th>قیمت</th>
                            <th>تخفیف</th>
                            <th>پیشنهاد ویژه</th>
                            <th>موجودی</th>
                            <th>دسته بندی</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <th>{{ $product->code }}</th>
                                <th>{{ $product->title }}</th>
                                <th>{{ number_format($product->price) }}</th>
                                <th>
                                    @if($product->discount_price > 0)
                                        {{ number_format($product->discount_price) }}
                                        <br>
                                        <span class="badge bg-green"> {{ $product->discount_percent }} % </span>
                                    @endif
                                </th>
                                <th>
                                    @if($product->special_offer == 'active')
                                        <span class="badge bg-green">فعال</span>
                                        <br>
                                        از:
                                        <b>{{ \Hekmatinasser\Verta\Verta::instance($product->special_offer_from)->format('Y/m/d') }}</b>
                                        <br>
                                        تا:
                                        <b>{{ \Hekmatinasser\Verta\Verta::instance($product->special_offer_to)->format('Y/m/d') }}</b>
                                    @else
                                        <span class="badge bg-gray">غیر فعال</span>
                                    @endif
                                </th>
                                <th>{{ $product->stock != -1 ? $product->stock : 'متغییر' }}</th>
                                <th>{{ $product->category->title }}</th>
                                <th>{{ $product->fa_status }}</th>
                                <th>
                                    <a href="{{ route('admin.store.product.edit', $product->id) }}" class=" btn bg-green">ویرایش</a>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>شناسه</th>
                            <th>عنوان</th>
                            <th>قیمت</th>
                            <th>تخفیف</th>
                            <th>پیشنهاد ویژه</th>
                            <th>موجودی</th>
                            <th>دسته بندی</th>
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

    </script>
@endsection
