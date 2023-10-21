@php($rndStr = \Illuminate\Support\Str::random(15))
<div class="form-group">
    <label for="uploader_{{ $rndStr }}_title">
        {{ $uploader_title }}
        <button type="button" class="btn btn-sm" onclick="openUploaderModal_{{ $rndStr }}()">
            <i class="fa fa-plus"></i>
        </button>
    </label>
    <div>
        <ol id="uploader_{{ $rndStr }}_results">
            @if(isset($uploader_items) && count($uploader_items) > 0)
                @foreach($uploader_items as $item)
                    @php($item = \App\Models\UploadCenter::find($item))
                    <li style="margin-top: 5px;">
                        <img class="uploaded_{{ $rndStr }}_img_file" data-file="{{ $item->id }}" src="{{ url($item->path) }}" style="max-height: 60px; height: 60px; width: 60px; max-width: 60px; border-radius: 9px" alt="{{ $item->alt }}">
                        <input disabled readonly type="text" class="form-control" value="{{ $item->alt }}" style="display: inline; width: 80%;" placeholder="متن جایگزین تصویر">
                        <button type="button" class="btn btn-danger" onclick="removeUploaderFile_{{ $rndStr }}(this)" data-file="{{ $item->id }}"><i class="fa fa-trash"></i></button>
                    </li>
                @endforeach
            @endif
        </ol>
        <hr>
    </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="uploader_{{ $rndStr }}_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $uploader_title }}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h5>

            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="uploader_{{ $rndStr }}_select_file" class="btn btn-default" style="width: 100%;">
                        افزودن
                    </label>
                    <div class="progress" style="margin-top: 15px; /*display: none;*/" id="uploader_{{ $rndStr }}_progress_bar">
                        <div class="progress-bar" id="uploader_{{ $rndStr }}_progress_bar_percent" role="progressbar"
                             style="border-radius: 5px; width: 50%;"></div>
                    </div>

                        <input type="file" style="display: none;" id="uploader_{{ $rndStr }}_select_file" {{ $uploader_multiple ? "multiple" : "" }}
                               accept="{{ $uploader_accepts }}" onchange="uploadFileAjax_{{ $rndStr }}()">
                    <ol id="uploader_{{ $rndStr }}_files"></ol>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="uploader_{{ $rndStr }}_choose_file" onclick="chooseUploadedFiles_{{ $rndStr }}()" disabled style="display: none;">انتخاب</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="emptyUploadedFilesList_{{ $rndStr }}()">انصراف</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openUploaderModal_{{ $rndStr }}() {
        $('#uploader_{{ $rndStr }}_modal').modal('show');
        $("#uploader_{{ $rndStr }}_progress_bar").hide();
        $("#uploader_{{ $rndStr }}_progress_bar_percent")
            .text('')
            .width('0%');
        $('#uploader_{{ $rndStr }}_choose_file').hide();
    }

    function uploadFileAjax_{{ $rndStr }}() {
        var form_data = new FormData();
        if ($('#uploader_{{ $rndStr }}_select_file')[0].files.length > 1) {
            $.each($('#uploader_{{ $rndStr }}_select_file')[0].files, function () {
                form_data.append( 'files[]', this);
            })
        } else {
            form_data.append( 'files[]', $('#uploader_{{ $rndStr }}_select_file')[0].files[0]);
        }
        form_data.append( '_token', $(`meta[name='csrf']`).attr('content'));
        form_data.append( 'model', "{{ $uploader_model ?? null }}");
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $('#uploader_{{ $rndStr }}_progress_bar').show();
                        $("#uploader_{{ $rndStr }}_progress_bar_percent")
                            .text(percentComplete + '%')
                            .width(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: '{{ route('admin.uploader') }}',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $("#uploader_{{ $rndStr }}_progress_bar_percent")
                    .text('0%')
                    .width('0%');
            },
            error: function (response) {
                new Notify ({
                    status: 'error',
                    title: 'خطا در موفقیت',
                    text: response.responseJSON.message,
                    effect: 'slide',
                    speed: 300,
                    customClass: '',
                    customIcon: '',
                    showIcon: true,
                    showCloseButton: false,
                    autoclose: true,
                    autotimeout: 5000,
                    gap: 10,
                    distance: 10,
                    type: 3,
                    position: 'right top'
                });
            },
            success: function (response) {
                $.each(response.data.files, function () {
                    let new_file_uploaded =
                        `<li style="margin-top: 5px;">
                                <img src="${this.path}" style="max-height: 60px; height: 60px; width: 60px; max-width: 60px; border-radius: 9px" alt="">
                            <input type="text" class="form-control img-alt-text-{{ $rndStr }}" data-file="${this.id}" style="display: inline; width: 85%;" placeholder="متن جایگزین تصویر">
                        </li>`;
                    $('#uploader_{{ $rndStr }}_files').append(new_file_uploaded);
                });
                new Notify ({
                    status: 'success',
                    title: 'عملیات موفقیت',
                    text: response.message,
                    effect: 'slide',
                    speed: 300,
                    customClass: '',
                    customIcon: '',
                    showIcon: true,
                    showCloseButton: false,
                    autoclose: true,
                    autotimeout: 5000,
                    gap: 10,
                    distance: 10,
                    type: 3,
                    position: 'right top'
                });
                $('#uploader_{{ $rndStr }}_choose_file')
                    .prop("disabled",false)
                    .show();
            }
        });
    }

    function chooseUploadedFiles_{{ $rndStr }}() {
        let alts = [];
        let showAltError = false;
        $.each($(document).find('input.img-alt-text-{{ $rndStr }}'), function () {
            if ($(this).val()) {
                alts.push({'file_id': $(this).data('file'), 'alt_text': $(this).val()});
            } else {
                showAltError = true;
            }
        });
        if (!showAltError) {
            $.ajax({
                url: "{{ route('admin.uploader.alt-text') }}",
                type: 'POST',
                data: {
                    _token: $(`meta[name='csrf']`).attr('content'),
                    alts: JSON.stringify(alts)
                },
                success: function (response) {
                    let targetValue = [];
                    $.each(response.data.files, function () {
                        $(document).find(`input[data-file='${this.id}']`).val(this.alt);
                        targetValue.push(this.id);
                        let previewImg =
                            `<li style="margin-top: 5px;">
                                <img class="uploaded_{{ $rndStr }}_img_file" data-file="${this.id}" data-rndstr="{{ $rndStr }}" src="${this.path}" style="max-height: 60px; height: 60px; width: 60px; max-width: 60px; border-radius: 9px" alt="${this.alt}">
                                <input disabled readonly type="text" class="form-control" value="${this.alt}" data-file="${this.id}" style="display: inline; width: 80%;" placeholder="متن جایگزین تصویر">
                                <button type="button" class="btn btn-danger" onclick="removeUploaderFile_{{ $rndStr }}(this)" data-file="${this.id}"><i class="fa fa-trash"></i></button>
                            </li>`;
                        if ($(document).find(`img[data-rndstr='{{ $rndStr }}'][data-file='${this.id}']`).length === 0) {
                            $('#uploader_{{ $rndStr }}_results').append(previewImg)
                        }

                    });
                    $('#{{ $uploader_target }}').val(JSON.stringify(targetValue));

                    new Notify ({
                        status: 'success',
                        title: 'عملیات موفقیت',
                        text: response.message,
                        effect: 'slide',
                        speed: 300,
                        customClass: '',
                        customIcon: '',
                        showIcon: true,
                        showCloseButton: false,
                        autoclose: true,
                        autotimeout: 5000,
                        gap: 10,
                        distance: 10,
                        type: 3,
                        position: 'right top'
                    });
                    $('#uploader_{{ $rndStr }}_modal').modal('hide');
                },
                error: function (response) {
                    console.log(response)
                }
            });
        } else {
            alert('همه تصاویر باید دارای متن جایگزین (alt) باشند!');
        }
    }

    function removeUploaderFile_{{ $rndStr }}(el) {
        let btn = el;
        let fileId= $(btn).data('file');
        $(btn).parent().remove();
        let currentFilesId = $('#{{ $uploader_target }}').val();
        currentFilesId = JSON.parse(currentFilesId);
        currentFilesId = currentFilesId.filter(item => item != fileId);
        if (currentFilesId.length > 0)
            $('#{{ $uploader_target }}').val(JSON.stringify(currentFilesId));
        else
            $('#{{ $uploader_target }}').val("")
    }

    function emptyUploadedFilesList_{{ $rndStr }}() {
        $('#uploader_{{ $rndStr }}_files').empty();
    }
</script>
