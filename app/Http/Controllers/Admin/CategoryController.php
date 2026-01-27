<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('pages.category.index', compact('categories'));
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        Category::destroy($id);
        return response()->json([
            'title' => 'Thành công',
            'message' => 'Xóa thành công',
            'type' => 'success',
            'url' => redirect()->route('admin.listCategory')->getTargetUrl()
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $slug = $request->slug;
        Category::where('id', $id)->update([
            'name' => $name,
            'slug' => $slug
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Sửa thể loại thành công',
        ]);
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $slug = $request->slug;

        $check = DB::table('categories')->where('name', $name)->orWhere('slug', $slug)->get();

        if (count($check) > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tên hoặc slug đã tồn tại',
            ]);
        }

        Category::create([
            'name' => $name,
            'slug' => $slug
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm thể loại thành công',
        ]);
    }
}
