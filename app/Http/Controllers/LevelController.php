<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;

class LevelController extends Controller
{
    public function index(){
        $levels = Level::all();
        return view('pages/level/index', compact('levels'));
    }

    public function destroy(Request $request){
        $level = Level::find($request->id);
        $level->delete();
        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    }

    public function update(Request $request){
        $levelValue = $request->level;
        $id = $request->id;
        $experience = $request->experience;
        $image = $request->image;
        $check = Level::where('level', $levelValue)->where('id', '!=', $id)->first();
        if($check){
            return response()->json([
                'status' => 'error',
                'message' => 'Level đã tồn tại'
            ]);
        }
        $level = Level::find($id);
        $level->level = $levelValue;
        $level->experience = $experience;
        $level->image = $image;
        $level->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Sửa thành công'
        ]);
    }

    public function store(Request $requets){
        $levelValue = $requets->level;
        $experience = $requets->experience;
        $image = $requets->image;
        $check = Level::where('level', $levelValue)->first();
        if($check){
            return response()->json([
                'status' => 'error',
                'message' => 'Cấp bậc đã tồn tại'
            ]);
        }
        $level = new Level();
        $level->level = $levelValue;
        $level->experience = $experience;
        $level->image = $image;
        $level->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm thành công cấp bậc'
        ]);
    }
}
