<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Chapter;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function index(Request $request){
        $comicId = $request->query('comic_id');
        $comic = Comic::find($comicId);
        return view("pages.chapter.comic", compact('comic'));
    }

    public function store(Request $request){
        $comicId = $request->comic_id;
        $comic = Comic::find($comicId);
        $chapter = new Chapter();
        $chapter->name = $request->name;
        $chapter->title = $request->title;
        $chapter->chapter_number = floatval($request->name);
        $chapter->server = $request->serverName;
        $chapter->comic_id = $comicId;
        $chapter->slug = 'chuong-' . $request->name;
        $chapter->save();
        if($request->images){
            $images = $request->images;
            foreach($images as $image){
                if($image || $image != ''){
                    list($page, $link) = explode('|', $image);
                    $chapter->images()->create([
                        'chapter_id' => $chapter->id,
                        'page' => $page,
                        'link' => $link
                    ]);
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm mới thành công'
        ]);
    }

    public function update(Request $request, $id){
        $id = $request->id;
        $chapter = Chapter::find($id);
        $chapter->name = $request->name;
        $chapter->title = $request->title;
        $chapter->server = $request->serverName;
        $chapter->chapter_number = floatval($request->name);
        $slug = 'chuong-' . $request->name;
        $chapter->slug = $slug;
        if($request->images){
            $images = $request->images;
            $chapter->images()->delete();
            foreach($images as $image){
                if($image || $image != ''){
                    list($page, $link) = explode('|', $image);
                    $chapter->images()->create([
                        'page' => $page,
                        'link' => $link
                    ]);
                }
            }

        }
        $chapter->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công'
        ]);
    }

    public function error(){
        $chapters = Chapter::where('has_report', '>', 0)->get();
        return view('pages.chapter.error', compact('chapters'));
    }

    public function updateError(Request $request){
        $chapter = Chapter::find($request->id);
        $chapter->has_report = 0;
        $chapter->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công'
        ]);
    }

    public function destroy(Request $request){
        $chapter = Chapter::find($request->id);
        $chapter->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thành công'
        ]);
    }
}
