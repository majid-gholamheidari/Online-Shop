$('form.ajax-form').on('submit', function (e) {
    let loading_el =
        `
            <div id="loading-element" style="vertical-align: middle; text-align: center ;background-color: rgba(12,12,12,0.35) ;width: 100% !important;height: 100% !important;top: 0px !important;left: 0px !important;position: fixed !important;display: block !important; z-index: 99 !important">
                <div style="display: flex; align-items: center; height: 100%; justify-content: center;">
                    <p style="color: #0c0c0c; font-size: 50px; font-weight: bold; text-shadow: -3px -3px 8px rgba(255,255,255,0.47);">در حال ارسال اطلاعات . . .</p>
                </div>
            </div>
        `;
    $('body').append(loading_el);
    e.preventDefault();
    let form = $(this);
    let form_method = form.attr('method');
    let form_action = form.attr('action');
    let form_data = form.serialize();
    $(document).find('span.error-span').remove();
    $(document).find('div').removeClass('has-error');
    $.ajax({
        url: form_action,
        data: form_data,
        type: form_method,
        success: function (response) {
            $(document).find('#loading-element').remove();
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
            if (response.redirect) {
                window.location.href = response.redirect;
            }
        },
        error: function (response) {
            $(document).find('#loading-element').remove();
            let message = "";
            if (response.responseJSON.errors) {
                $.each(response.responseJSON.errors, function (index, txt) {
                    message += "<br>" + txt;
                    if ($(document).find(`input[name='${index}']`).length > 0) {
                        $(document).find(`input[name='${index}']`)
                            .after(`<span class="error-span">${txt}</span>`);
                        /*$(document).find(`input[name='${index}']`)
                            .parent()
                            .addClass('has-error');*/
                    } else if ($(document).find(`select[name='${index}']`).length > 0) {
                        $(document).find(`select[name='${index}']`)
                            .after(`<span class="error-span">${txt}</span>`);
                        /*if ($(document).find(`select[name='${index}']`).hasClass('select2')) {
                            $(document).find(`select[name='${index}']`)
                                .parent()
                                .children()
                                .last()
                                .after(`<span class="error-span">${txt}</span>`);
                        } else {
                            $(document).find(`select[name='${index}']`)
                                .after(`<span class="error-span">${txt}</span>`);
                        }*/
                        /*$(document).find(`select[name='${index}']`)
                            .parent()
                            .addClass('has-error');*/
                    } else if ($(document).find(`textarea[name='${index}']`).length > 0) {
                        $(document).find(`textarea[name='${index}']`)
                            .after(`<span class="error-span">${txt}</span>`);
                        /*$(document).find(`textarea[name='${index}']`)
                            .parent()
                            .addClass('has-error');*/
                    }
                });
            } else {
                message = response.responseJSON.message ?? "اطلاعات وارد شده معتبر نمی باشند!";
            }
            new Notify ({
                status: 'error',
                title: 'خطا در عملیات',
                text: $.trim(message),
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
        }
    });
});
