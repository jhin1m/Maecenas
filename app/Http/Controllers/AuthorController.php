<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::orderBy('updated_at', 'desc')->get();
        return view('pages.author.index', compact('authors'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        Author::find($id)->delete();
        return response()->json([
            'title' => 'Thành công',
            'message' => 'Xóa thành công',
            'type' => 'success',
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $slug = $request->slug;
        Author::where('id', $id)->update([
            'name' => $name,
            'slug' => $slug
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
            'type' => 'success',
        ]);
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $slug = $request->slug;

        $check = Author::where('name', $name)->orWhere('slug', $slug)->first();

        if ($check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tên hoặc slug đã tồn tại',
                'type' => 'error',
            ]);
        }

        Author::create([
            'name' => $name,
            'slug' => $slug
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm tác giả thành công',
            'type' => 'success',
        ]);
    }
}
