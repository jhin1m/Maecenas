<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'DESC')->get();
        return view("pages/blog/index", compact('blogs'));
    }

    public function create()
    {
        return view('pages.blog.create');
    }

    public function edit(Blog $blog){
        return view('pages.blog.edit', compact('blog'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:blogs,title',
            'content' => 'nullable',
            'status' => 'nullable',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'title.unique' => 'Tiêu đề đã tồn tại',
        ]);
        $data = $request->all();
        $slug = Str::slug($data['title'], '-');
        $data['slug'] = $slug;
        if($request->imageOption == "file"){
            if($request->hasFile('image')){
                $image = $request->file('image');
                $fullPath = env('APP_URL'). '/storage/'. $image->storeAs('images', $image->getClientOriginalName());
                $data['image'] = $fullPath;
            }
        }else{
            if ($request->imageUrl) {
                $data['image'] = $request->imageUrl;
            }
        }
        $id = Blog::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'status' => $data['status'],
            'image' => $data['image'],
            'user_id' => auth()->user()->id,
        ])->id;

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm bài viết thành công',
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'title' => 'required|unique:blogs,title,' . $id . ',id',
            'slug' => 'required|unique:blogs,slug,' . $id . ',id',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'title.unique' => 'Tiêu đề đã tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
        ]);
        $data = $request->all();
        Blog::where('id', $id)->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'status' => $data['status'],
            'image' => $data['image'],
            'is_hot' => $request->has('is_hot') && $request->is_hot == 'true' ? 1 : 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Sửa bài viết thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        Blog::where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa bài viết thành công',
        ]);
    }
}
