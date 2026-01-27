<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::orderBy('created_at', 'desc')->get();
        return view('pages.comment.index', compact('comments'));
    }
    public function destroy(Request $request)
    {
        $data = $request->all();
        $comment = Comment::find($data['id']);
        $comment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa bình luận thành công'
        ]);
    }
}
