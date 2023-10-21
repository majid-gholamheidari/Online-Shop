@extends('admin.layouts.master')

@section('title', 'فروشگاه | افزودن محصول')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/panel/plugins/select2/select2.min.css') }}">
    <script src="https://cdn.tiny.cloud/1/n6j2ipk3qws640lzxor9uaalmn3jnm0cnq72qlmmpi5093sl/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="{{ asset('assets/panel/plugins/tagify/tagify.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/panel/plugins/persian-date-picker/persian-datepicker.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .custom-form-group {
            -webkit-box-shadow: 0px 0px 56px -22px rgba(0,0,0,0.39);
            -moz-box-shadow: 0px 0px 56px -22px rgba(0,0,0,0.39);
            box-shadow: 0px 0px 56px -22px rgba(0,0,0,0.39);
            border: 2px solid rgba(0,0,0,0.56);
            padding: 8px;
            border-radius: 6px;
            margin-top: 50px !important;
        }
        .tagify {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="row" style="display: flex; justify-content: center">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">محصول جدید</h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.store.product.store') }}" method="POST" class="ajax-form">
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

                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="parent">دسته بندی</label>
                            <select class="form-control select2" id="parent" name="category_id">
                                <option value="" disabled selected hidden>انتخاب دسته بندی</option>
                                @foreach($categories->where('level', 1) as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @foreach($categories->where('level', 2)->where('category_id', $category->id) as $categoryLevel2)
                                        <option value="{{ $categoryLevel2->id }}">{{ $category->title . " > " . $categoryLevel2->title }}</option>
                                        @foreach($categories->where('level', 3)->where('category_id', $categoryLevel2->id) as $categoryLevel3)
                                            <option value="{{ $categoryLevel3->id }}">{{ $category->title . " > " . $categoryLevel2->title . " > " . $categoryLevel3->title }}</option>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group  col-lg-6 col-sm-12">
                            <label for="brand_id">برند</label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                <option value="" disabled selected hidden>انتخاب برند</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="status">وضعیت</label>
                            <select class="form-control select2" id="status" name="status">
                                <option value="">انتخاب وضعیت</option>
                                <option value="{{ \Illuminate\Database\Eloquent\Model::StatusItems[1] }}">فعال</option>
                                <option value="{{ \Illuminate\Database\Eloquent\Model::StatusItems[0] }}">غیر فعال
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="stock">موجودی</label>
                            <input type="number" name="stock" id="stock" class="form-control">
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="price">قیمت</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="قیمت">
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="discount_price">مبلغ تخفیف</label>
                            <input type="number" class="form-control" id="price" name="discount_price" placeholder="قیمت" value="0">
                        </div>

                        <div class="form-group">
                            <input type="checkbox" class="custom-control-input" id="special_offer" onchange="toggleSpecialOfferDateInput()">
                            <input name="special_offer" type="hidden" value="inactive">
                            <label class="custom-control-label" for="special_offer">پیشنهاد ویژه</label>
                            <div id="datepicker-section" style="display: none;">
                                <div class="form-group col-lg-6">
                                    از تاریخ:
                                    <input id="range-from" name="so_from" class="form-control">
                                    <div class="range-from"></div>
                                </div>
                                <div class="form-group col-lg-6">
                                    تا تاریخ:
                                    <input id="range-to" name="so_to" class="form-control">
                                    <div class="range-to"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tags">برچسب ها</label>
                            <input type="text" style="width: 100% !important;" id="tags" name="tags" placeholder="برچسب ها">
                        </div>

                        <div class="custom-form-group form-group">
                            <label for="properties">
                                ویژگی های شاخص
                                <button onclick="addNewProperty()" type="button" class="btn btn-sm"><i class="fa fa-plus"></i></button>
                            </label>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>ویژگی</th>
                                    <th>مقدار</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody id="properties">

                                </tbody>
                            </table>
                        </div>

                        <div class="custom-form-group form-group">
                            <label for="properties">
                                مشخصات
                                <button onclick="addNewAttribute()" type="button" class="btn btn-sm"><i class="fa fa-plus"></i></button>
                            </label>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>مشخصه</th>
                                    <th>مقدار</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody id="attributes">

                                </tbody>
                            </table>
                        </div>

                        <div class="custom-form-group form-group">
                            <label for="colors">
                                رنگ های موجود
                                <button onclick="addNewColor()" type="button" class="btn btn-sm"><i class="fa fa-plus"></i></button>
                            </label>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>رنگ</th>
                                    <th>عنوان</th>
                                    <th>هزینه مازاد</th>
                                    <th>موجودی</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody id="colors">

                                </tbody>
                            </table>
                        </div>

                        @include('admin.layouts.components.uploader',
                            [
                                'uploader_title' => 'تصویر شاخص',
                                'uploader_accepts' => '.jpg,.jpeg,.png',
                                'uploader_multiple' => false,
                                'uploader_target' => 'cover_img',
                                'uploader_model' => class_basename(\App\Models\Store\StoreProduct::class),
                            ])
                        <input type="hidden" name="cover_img" value="" id="cover_img">

                        @include('admin.layouts.components.uploader',
                            [
                                'uploader_title' => 'تصاویر',
                                'uploader_accepts' => '.jpg,.jpeg,.png',
                                'uploader_multiple' => true,
                                'uploader_target' => 'images',
                                'uploader_model' => class_basename(\App\Models\Store\StoreProduct::class),
                            ])
                        <input type="hidden" name="images" value="" id="images">

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
    <script src="{{ asset('assets/panel/plugins/persian-date-picker/persian-date.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/persian-date-picker/persian-datepicker.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/panel/plugins/tagify/tagify.polyfills.js') }}"></script>
    <script>

        var to, from;
        to = $(".range-to").persianDatepicker({
            inline: true,
            altField: '#range-to',
            altFormat: 'YYYY/MM/DD',
            initialValue: false,
            onSelect: function (unix) {
                to.touched = true;
                if (from && from.options && from.options.maxDate != unix) {
                    var cachedValue = from.getState().selected.unixDate;
                    from.options = {maxDate: unix};
                    if (from.touched) {
                        from.setDate(cachedValue);
                    }
                }
            }
        });
        from = $(".range-from").persianDatepicker({
            inline: true,
            altField: '#range-from',
            altFormat: 'YYYY/MM/DD',
            initialValue: false,
            onSelect: function (unix) {
                from.touched = true;
                if (to && to.options && to.options.minDate != unix) {
                    var cachedValue = to.getState().selected.unixDate;
                    to.options = {minDate: unix};
                    if (to.touched) {
                        to.setDate(cachedValue);
                    }
                }
            }
        });
        function toggleSpecialOfferDateInput() {
            if ($('#special_offer').is(':checked')) {
                $('#datepicker-section').show();
                $(`input[name='special_offer']`).val("active")
            } else {
                $(`input[name='special_offer']`).val("inactive")
                $('#datepicker-section').hide();
                $('#range-from').val('');
                $('#range-to').val('');
            }
        }

        $('.select2').select2({
            dir: "rtl",
            language: "fa"
        })

        $('#brand_id').select2({
            placeholder: 'انتخاب برند',
            dir: "rtl",
            allowClear: true,
            ajax: {
                url: '{{ route('admin.store.product.brands-list-ajax') }}',
                dataType: 'json',
                type: 'POST',
                data: function(params) {
                    // console.log(params)
                    return {
                        term: params.term || '',
                        page: params.page || 1,
                        _token: $(`meta[name='csrf']`).attr('content')
                    }
                },
                cache: true,
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
            }
        });

        let input = document.getElementById('tags'),
            tagify = new Tagify(input, {
                whitelist: [],
                duplicates: false
            })
        tagify.on('input', onInput)
        function onInput( e ){
            let value = e.detail.value
            tagify.settings.whitelist.length = 0
            tagify.loading(true).dropdown.hide.call(tagify)
            fetch('{{ route('admin.store.product.tags-list-ajax') }}/?search=' + value, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": $(`meta[name='csrf']`).attr('content'),
                }
            })
                .then(RES => RES.json())
                .then(function(newWhitelist){
                    tagify.whitelist = newWhitelist
                    tagify.loading(false).dropdown.show.call(tagify, value);
                })
        }

        function addNewProperty() {
            let randStr = (Math.random() * 5).toString(36).substring(2);
            let newProperty =
                `<tr data-property="${randStr}">
                    <th>
                        <input name="property_index[]" type="text" class="form-control" placeholder="ویژگی">
                    </th>
                    <th>
                        <input name="property_value[]" type="text" class="form-control" placeholder="مقدار">
                    </th>
                    <th>
                        <button onclick="removeProductProperty('${randStr}')" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </th>
                </tr>`;
            $('#properties').append(newProperty)
        }

        function removeProductProperty(property_id) {
            $(document).find(`tr[data-property='${property_id}']`).remove();
        }

        function addNewAttribute() {
            let randStr = (Math.random() * 5).toString(36).substring(2);
            let newAttribute =
                `<tr data-attribute="${randStr}">
                    <th>
                        <input name="attribute_index[]" type="text" class="form-control" placeholder="مشخصه">
                    </th>
                    <th>
                        <input name="attribute_value[]" type="text" class="form-control" placeholder="مقدار">
                    </th>
                    <th>
                        <button onclick="removeProductAttribute('${randStr}')" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </th>
                </tr>`;
            $('#attributes').append(newAttribute)
        }

        function removeProductAttribute(attribute_id) {
            $(document).find(`tr[data-attribute='${attribute_id}']`).remove();
        }

        function addNewColor() {
            let randStr = (Math.random() * 5).toString(36).substring(2);
            let newAttribute =
                `<tr data-color="${randStr}">
                    <th>
                        <input name="color_hexcode[]" type="color" class="form-control" placeholder="رنگ" style="width: 45px;">
                    </th>
                    <th>
                        <input name="color_title[]" type="text" class="form-control" placeholder="عنوان">
                    </th>
                    <th>
                        <input name="color_price[]" type="text" class="form-control" placeholder="هزینه مازاد">
                    </th>
                    <th>
                        <input name="color_stock[]" type="text" class="form-control" placeholder="موجودی">
                    </th>
                    <th>
                        <button onclick="removeProductColor('${randStr}')" type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </th>
                </tr>`;
            $('#colors').append(newAttribute)
        }

        function removeProductColor(color_id) {
            $(document).find(`tr[data-color='${color_id}']`).remove();
        }

        const example_image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.uploader.tinymce') }}');

            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = () => {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = () => {
                reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        });
        tinymce.init({
            selector: 'textarea',
            language: "fa",
            images_upload_credentials: true,
            images_reuse_filename: true,
            images_upload_handler: example_image_upload_handler
        });
    </script>
@endsection
