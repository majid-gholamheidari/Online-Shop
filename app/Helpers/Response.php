<?php

namespace App\Helpers;

class Response
{

    private static $error = 'danger';
    private static $success = 'success';
    private static $warning = 'warning';
    private static $info = 'info';

    public static function success($message='اطلاعات با موفقیت ذخیره شد', $data = null, $redirect = null, $code = 200)
    {
        return self::Fire($code, self::$success, $message, $redirect, $data);
    }

    public static function error($message, $data = null, $redirect = null, $code = 400)
    {
        return self::Fire($code, self::$error, $message, $redirect, $data);
    }

    public static function info($message, $data = null, $redirect = null, $code = 200)
    {
        return self::Fire($code, self::$info, $message, $redirect, $data);
    }

    public static function warning($message, $data = null, $redirect = null, $code = 200)
    {
        return self::Fire($code, self::$warning, $message, $redirect, $data);
    }

    private static function Fire($code, $status, $message, $redirect = null, $data = null)
    {
        if (request()->ajax()) {
            if ($data != null) {
                return response()->json([
                    'status'  => $status,
                    'message' => $message,
                    'data'    => $data,
                    'redirect' => $redirect
                ])->setStatusCode($code);
            }
            return response()->json(['status' => $status, 'message' => $message, 'redirect' => $redirect])->setStatusCode($code);
        }
        session()->flash('message', ['status' => $status, 'message' => $message]);

        if ($redirect == null) {
            return back();
        }

        return $redirect;
    }
}
