<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود به فروشگاه</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="{{ asset('assets/panel/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/font.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/panel/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/panel/plugins/iCheck/square/blue.css') }}">
    <!-- Notify -->
    <link rel="stylesheet" href="{{ asset('assets/css/notify.css') }}">

</head>
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#!">فروشگاه آنلاین</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">برای ورود به پنل مدیریت فروشگاه وارد شوید.</p>
        <form action="" class="ajax-form" method="post">
            @csrf
            <div class="form-group has-feedback">
                <input type="number" name="mobile" class="form-control" placeholder="موبایل">
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="رمز عبور">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">ورود</button>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset('assets/panel/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<!-- Bootstrap 3.3.4 -->
<script src="{{ asset('assets/panel/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('assets/panel/plugins/iCheck/icheck.min.js') }}"></script>
<!-- submit form with ajax -->
<script src="{{ asset('assets/js/submit-form-with-ajax.js') }}"></script>
<!-- Notify -->
<script src="{{ asset('assets/js/notify.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
