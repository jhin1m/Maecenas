<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index() {
        $users = User::all();
        return view('pages.user.index', compact('users'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'exp' => 'nullable'
        ],[
            'name.required' => 'Vui lòng nhập tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'role.required' => 'Vui lòng chọn quyền'
        ]);
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->role = $request->role;
        $user->exp = $request->exp;
        $user->save();

        $res = redirect()->route('admin.user.index')->with('success', 'Tạo tài khoản thành công.');
        return response()->json([
            'status' => 'success',
            'url' => $res->getTargetUrl(),
            'message' => 'Tạo tài khoản thành công.'
        ]);
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required',
        ],[
            'name.required' => 'Vui lòng nhập tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Vui lòng nhập đúng định dạng email',
            'email.unique' => 'Email đã tồn tại',
            'role.required' => 'Vui lòng chọn quyền'
        ]);
        $user = User::find($id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->exp = $request->exp;
        $user->save();

        return back()->with('succes', 'Cập nhật tài khoản thành công.');
    }

    public function edit($id) {
        $user = User::find($id);
        return view('pages.user.edit', compact('user'));
    }

    public function destroy(Request $request) {
        $data = $request->all();
        $user = User::find($data['id']);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa tài khoản thành công'
        ]);
    }
}
