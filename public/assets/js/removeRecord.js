function removeRecord(el) {
    let url = $(el).data('url');
    Swal.fire({
        icon: 'question',
        title: 'از حذف این مورد اطمینان دارید؟!',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'انصراف',
        denyButtonText: `حذف شود`,
    }).then((result) => {
        if (result.isDenied) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: $(`meta[name='csrf']`).attr('content'),
                    _method: "DELETE"
                },
                success: function () {
                    return window.location.reload();
                },
                error: function (response) {
                    alert('خطایی رخ داده است.')
                }
            });
        }
    })
}
