<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    public function loginPage()
    {
        return view('admin.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
            return Response::success("خوش آمدید.", null, route('admin.dashboard'));
        } else {
            return Response::error("کاربری با مشخصات وارد شده موجود نمی باشد");
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }
}
