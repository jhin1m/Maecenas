<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassMail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if(Auth::check()) {
            return redirect()->route('home');
        }
        return view('users/login-signup');
    }

    public function showForgotPass(){
        return view('users/forgot-password');
    }
    public function login()
    {
        $data = request()->all();
        $email = $data['email'];
        $password = $data['password'];
        $remember = true;
        if (auth()->attempt(['email' => $email, 'password' => $password], $remember)) {
            Auth::login(Auth::user());

            return redirect()->route('home');
        }
        return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng')->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ],[
            'name.required' => 'Vui lòng nhập tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'email.unique' => 'Email đã tồn tại',
            'password.confirmed' => 'Mật khẩu không khớp'
        ]);

        $data = request()->all();
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function resetPass(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không tồn tại'
        ]);

        $token = Str::random(60);
        $check = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if ($check) {
            return redirect()->back()->with('status', 'Vui lòng kiểm tra email mới nhất (nếu không thấy hãy kiểm tra ở thư rác)!');
        }
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
        $resetPasswordUrl = url('/reset-password?token=' . $token);
        Mail::raw('Nhấp vào đường dẫn sau để tiến hành đổi mật khẩu mới: '.$resetPasswordUrl, function ($message) use ($request) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($request->email, "Người dùng")->subject('Quên mật khẩu');
        });
        return redirect()->back()->with('status', 'Vui lòng kiểm tra email mới nhất (nếu không thấy hãy kiểm tra ở thư rác)!');
    }

    public function showResetPass(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect()->route('login-signup');
        }
        $check = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$check) {
            return redirect()->route('home');
        }
        if (session()->has('user')) {
            return redirect()->route('home');
        }
        return view('users/reset-password');
    }

    public function changePass(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password_confirmation.same' => 'Mật khẩu không khớp'
        ]);
        $token = $request->query('token');
        $check = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$check) {
            return redirect()->route('login-signup');
        }
        DB::table('users')->where('email', $check->email)->update([
            'password' => bcrypt($request->password)
        ]);
        DB::table('password_reset_tokens')->where('token', $token)->delete();
        return redirect()->route('home');
    }
}
