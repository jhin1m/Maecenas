<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\QueryString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comic;
use App\Models\Story;
use App\Models\History;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Level;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Models\Chapter;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $seo = DB::table('seo')->get();
        SEOTools::setTitle($seo[0]->value);
        SEOTools::setDescription($seo[4]->value);
        SEOMeta::addKeyword($seo[2]->value);
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::opengraph()->addImage($seo[5]->value);
        $metaHtml = $seo[6]->value;
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $page = $request->input('page') ?? 1;
        $comicsMostViews = Cache::remember('home.hot_mangas', 86400, function () {
            return Comic::orderByDesc('view_total')->with('chapters')->take(8)->get();
        });

        $categories = Cache::remember('home.categories', 86400, function () {
            return Category::limit(4)->withCount('comics')->orderByDesc('comics_count')->get();
        });

        $ranking = Cache::remember('ranking', 3600 * 5, function () {
            $comicsDaily = Comic::withCount('chapters')
                ->select('name', 'slug', 'thumbnail', 'view_day as view_total', 'rating')
                ->with('lastChapter')
                ->orderByDesc('view_day')
                ->limit(6)
                ->get();
            $comicsWeekly = Comic::withCount('chapters')
                ->select('name', 'slug', 'thumbnail', 'view_week as view_total', 'rating')
                ->with('lastChapter')
                ->orderByDesc('view_week')
                ->limit(6)
                ->get();
            $comicsMonthly = Comic::withCount('chapters')
                ->select('name', 'slug', 'thumbnail', 'view_month as view_total', 'rating')
                ->with('lastChapter')
                ->orderByDesc('view_month')
                ->limit(6)
                ->get();

            return [
                'comicsDaily' => $comicsDaily,
                'comicsWeekly' => $comicsWeekly,
                'comicsMonthly' => $comicsMonthly
            ];
        });
        $comicsDaily = $ranking['comicsDaily'];
        $comicsWeekly = $ranking['comicsWeekly'];
        $comicsMonthly = $ranking['comicsMonthly'];

        $comicsUpdated = Cache::remember('comicsUpdated_' . $page, 3600, function () {
            return Comic::orderBy('updated_at', 'desc')->with('chapters')->paginate(24);
        });

        $completedComics = Cache::remember('completedComics', 3600, function () {
            return Comic::where('status', 'completed')
                ->select('name', 'slug', 'thumbnail', 'rating', 'view_total')
                ->with('lastChapter')
                ->orderBy('updated_at', 'desc')->limit(12)->get();
        });

        $newComments = Comment::select('id', 'content', 'created_at', 'user_id', 'comic_id')->with('user', 'comic')->orderBy('created_at', 'desc')->limit(15)->get();

        $blogs = Cache::remember('blogs', 3600, function () {
            return Blog::orderBy('created_at', 'desc')->with('user')->limit(12)->get();
        });

        return view("/users/index", compact(
            'comicsMostViews',
            'comicsUpdated',
            'comicsDaily',
            'comicsWeekly',
            'comicsMonthly',
            'metaHtml',
            'categories',
            'completedComics',
            'newComments',
            'blogs'
        ));
    }

    public function showDetailComic($slug)
    {
        $comic = Comic::where('slug', $slug)->withCount('follows')->with(['chapters', 'categories', 'author'])->first();
        if (!$comic) {
            abort(404);
        }

        $replacements = [
            '{{$comic->name}}' => $comic->name,
            '{{$comic->status}}' => $comic->status,
            '{{$comic->origin_name}}' => $comic->origin_name,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[7]->value);
        SEOTools::setTitle($title);
        SEOTools::setDescription("❶✔️ Đọc truyện {$comic->name} Tiếng Việt bản dịch Full mới nhất");
        SEOMeta::addKeyword("{$comic->name},{$comic->name} tiếng việt,đọc truyện {$comic->name},truyện {$comic->name}");
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addImage($comic->thumbnail);
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        $metaHtml = $seo[6]->value;
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $follow = false;
        if (Auth::check()) {
            $user = Auth::user();
            $check = DB::table('follows')->where('user_id', $user->id)->where('comic_id', $comic->id)->first();
            if ($check) {
                $follow = true;
            }
            $history = DB::table('histories')->where('user_id', $user->id)->where('comic_id', $comic->id)->first();
            if ($history) {
                $history = explode(",", $history->chapterComics_id);
                $comic->history = $history;
                $conti = Chapter::where('id', end($history))->select('slug', 'name', 'updated_at')->first();
                $comic->conti = $conti ? $conti : null;
            }
        }

        $comments = $comic->comments()->where('parent_id', null)->paginate(10);
        $this->upView('comic', $comic->id);

        $relatedComics = Comic::whereHas('categories', function ($query) use ($comic) {
            $query->whereIn('category_id', $comic->categories->pluck('id'));
        })->where('id', '!=', $comic->id)->with('lastChapter')->orderByDesc('view_total')->limit(5)->get();

        $topReadComicsMonthly = Comic::orderByDesc('view_month')->with('lastChapter')->limit(6)->get();
        $topReadComicsDaily = Comic::orderByDesc('view_day')->with('lastChapter')->limit(6)->get();
        $topReadComicsWeekly = Comic::orderByDesc('view_week')->with('lastChapter')->limit(6)->get();
        return view("/users/detail", compact(
            'comic',
            'follow',
            'comments',
            'metaHtml',
            'relatedComics',
            'topReadComicsDaily',
            'topReadComicsWeekly',
            'topReadComicsMonthly',
        ));
    }

    public function showReadComicPage($slug, $chapter)
    {
        $comic = Cache::remember("comic.{$slug}", 3600, function () use (&$slug) {
            return Comic::where('slug', $slug)->withCount('comments')->with('chapters')->first();
        });
        if (!$comic) {
            abort(404);
        }
        $chapterSelected = Cache::remember("chapter.{$slug}.{$chapter}", 600, function () use (&$comic, &$chapter) {
            return Chapter::where('comic_id', $comic->id)
                ->where('slug', $chapter)
                ->where('server', '!=', '')
                ->with('images')
                ->orderBy('created_at', 'desc')
                ->first();
        });
        if (!$chapterSelected) {
            abort(404);
        }
        $servers = Cache::remember("server.{$slug}.{$chapter}", 600, function () use (&$comic, &$chapterSelected) {
            return Chapter::where('comic_id', $comic->id)
                ->where('chapter_number', $chapterSelected->chapter_number)
                ->where('server', '!=', '')
                ->select('server', DB::raw('MAX(id) as id'), DB::raw('MAX(created_at) as created_at'))
                ->groupBy('server')
                ->orderBy('created_at', 'desc')
                ->get();
        });
        $comic->prevChap = Chapter::where('comic_id', $comic->id)
            ->where('chapter_number', '<', $chapterSelected->chapter_number)
            ->select('name', 'slug')
            ->orderByDesc('chapter_number')->first();
        $comic->nextChap = Chapter::where('comic_id', $comic->id)
            ->where('chapter_number', '>', $chapterSelected->chapter_number)
            ->select('name', 'slug')
            ->orderBy('chapter_number')->first();

        $this->upView('comic', $comic->id);
        $comments = $comic->comments()->where('parent_id', null)->paginate(5);
        $replacements = [
            '{{$comic->name}}' => $comic->name,
            '{{$comic->status}}' => $comic->status,
            '{{$comic->origin_name}}' => $comic->origin_name,
            '{{$chapterSelected->name}}' => $chapterSelected->name,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[8]->value);
        SEOTools::setTitle($title);
        $description = "Đọc truyện tranh " . $comic->name . ($comic->origin_name ? " - " . $comic->origin_name : "") . " chap " . $chapterSelected->name . " tiếng việt. Mới nhất nhanh nhất tại " . env('APP_NAME');
        SEOTools::setDescription($description);
        SEOMeta::addKeyword("{$comic->name},{$comic->name} tiếng việt,đọc truyện {$comic->name},truyện {$comic->name}, truyện {$comic->name} chap {$chapterSelected->name}");
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addImage($comic->thumbnail);
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        $metaHtml = $seo[6]->value;
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $this->saveHistory('comic', $comic->id, $chapterSelected->id);

        return view("/users/read", compact('comic', 'chapterSelected', 'servers', 'comments', 'metaHtml'));
    }

    public function showNewUpdate(Request $request)
    {
        $seo = DB::table('seo')->get();
        $title = 'Truyện mới cập nhật - ' . env('APP_NAME');
        SEOTools::setTitle($title);
        SEOTools::setDescription("Danh sách truyện tranh comics, manga, manhua, manhwa mới cập nhật tại " . env('APP_NAME'));
        SEOMeta::addKeyword("truyện mới cập nhật, truyện tranh mới");
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        $metaHtml = $seo[6]->value;

        $status = $request->input('status') ?? "all";
        $sort = $request->input('sort') ?? "0";
        $page = $request->input('page') ?? 1;

        if ($status == 'all' && $sort == '0') {
            $comics = Cache::remember('comicsUpdated_' . $page, 3600, function () {
                return Comic::orderBy('updated_at', 'desc')->with('chapters')->paginate(24);
            });
        } else {
            $comics = Comic::withCount('votes', 'follows', 'comments', 'chapters')->with('chapters');
            switch ($status) {
                case '1':
                    $comics->where('status', 'completed');
                    break;
                case '2':
                    $comics->where('status', 'ongoing');
                    break;
            }
            switch ($sort) {
                case '1':
                    $comics->orderByDesc('created_at');
                    break;
                case '2':
                    $comics->orderByDesc('view_total');
                    break;
                case '3':
                    $comics->orderByDesc('follows_count');
                    break;
                case '4':
                    $comics->orderByDesc('comments_count');
                    break;
                case '5':
                    $comics->orderByDesc('chapters_count');
                    break;
                default:
                    $comics->orderByDesc('updated_at');
                    break;
            }
            $comics = $comics->paginate(24);
        }

        return view("/users/new-updates", compact('comics', 'metaHtml'));
    }

    public function searchComic(Request $request)
    {
        $query = $request->get('search');
        if (!$query || $query == "") {
            return response()->json([
                'ok' => 1,
                'list' => "<center>Không có kết quả phù hợp</center>"
            ]);
        }
        $comics = Comic::where('name', 'like', '%' . $query . '%')->orWhere('slug', 'like', '%' . $query . '%')->orWhere('origin_name', 'like', '%' . $query . '%')->limit(10)->get();
        $msg = "";
        if (count($comics) == 0) {
            return response()->json([
                'ok' => 1,
                'list' => "<center>Không có kết quả phù hợp</center>"
            ]);
        } else {
            foreach ($comics as $comic) {
                $msg .= "<div class='li_search'><a href='" . route('detail', $comic->slug) . "'>
                <div class='img'><img src='" . $comic->thumbnail . "' alt='" . $comic->name . "'></div>
                <div class='info'>" . $comic->name . "</div>

                </a></div>";
            }
        }
        return response()->json([
            'ok' => 1,
            'list' => $msg
        ]);
    }

    public function vote(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['data' => false, 'status' => 401], 401);
        }
        $user = Auth::user();
        $check = DB::table('voting')->where('user_id', $user->id)->where('comic_id', $id)->first();
        $comic = Comic::find($id);

        if ($check) {
            DB::table('voting')->where('user_id', $user->id)->where('comic_id', $id)->update([
                'vote' => $request->vote,
            ]);

            $comic->rating = ($comic->rating * $comic->total_votes - $check->vote + $request->vote) / $comic->total_votes;
            $comic->save();

            return response()->json(['data' => true, 'status' => 200], 200);
        }
        DB::table('voting')->insert([
            'user_id' => $user->id,
            'comic_id' => $id,
            'vote' => $request->vote,
        ]);

        $comic->total_votes = $comic->total_votes + 1;
        $comic->rating = ($comic->rating * $comic->total_votes + $request->vote) / ($comic->total_votes + 1);
        $comic->save();
        return response()->json(['data' => true, 'status' => 200], 200);
    }

    public function follow(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['data' => false, 'status' => 401], 401);
        }
        $user = Auth::user();
        $check = DB::table('follows')->where('user_id', $user->id)->where('comic_id', $id)->first();
        if ($check) {
            DB::table('follows')->where('user_id', $user->id)->where('comic_id', $id)->delete();
            return response()->json([
                'data' => [
                    'isFollowing' => false,
                ],
                'status' => 200
            ], 200);
        }
        DB::table('follows')->insert([
            'user_id' => $user->id,
            'comic_id' => $id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json([
            'data' => [
                'isFollowing' => true,
            ],
            'status' => 200
        ], 200);
    }

    public function comments(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['data' => null, 'status' => 401], 401);
        }
        $user = Auth::user();
        $comment = Comment::create([
            'user_id' => $user->id,
            'comic_id' => $request->mangaId,
            'chapter_id' => $request->chapterId,
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 200,
            'data' => [
                'id' => $comment->id,
                'content' => $request->content,
                'createdAt' => now(),
            ]
        ], 200);
    }

    public function reply(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['data' => null, 'status' => 401], 401);
        }
        $user = Auth::user();
        $comment = Comment::find($id);
        $comment->replies()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'comic_id' => $comment->comic_id,
            'chapter_id' => $request->chapterId,
        ]);
        return response()->json([
            'status' => 200,
            'data' => [
                'id' => $comment->id,
                'content' => $request->content,
                'createdAt' => now(),
            ]
        ], 200);
    }

    public function getComments(Request $request)
    {
        $mangaId = $request->mangaId;
        $chapterId = $request->chapterId ?? null;
        $page = $request->page ?? 1;
        $pageSize = $request->pageSize ?? 10;
        $orderBy = $request->orderBy ?? 'created_at';

        $query = Comment::where('comic_id', $mangaId)
            ->with(['user', 'chapter', 'comic', 'replies.user'])
            ->where('parent_id', null);
        if ($chapterId) {
            $query->where('chapter_id', $chapterId);
        }
        $data = $query->orderBy('created_at', 'desc')->paginate($pageSize);

        $html = view('components.list-comments', compact('data'))->render();
        return response()->json([
            'status' => 200,
            'data' => $html
        ], 200);
    }

    public function like($id)
    {
        if (!Auth::check()) {
            return response()->json(['data' => null, 'status' => 401], 401);
        }
        $comment = Comment::find($id);
        $comment->like += 1;
        $comment->save();
        return response()->json([
            'status' => 200,
            'data' => [
                'totalLike' => $comment->like,
                'owner' => Auth::user()->id,
                'mangaId' => $comment->comic_id,
                'chapterId' => $comment->chapter_id,
                'isLiked' => true,
            ]
        ], 200);
    }

    public function upView($table, $id)
    {
        $firstDayOfWeek = Carbon::now()->startOfWeek();
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now()->format('Y-m-d');
        $comic = Comic::find($id);
        $viewTotal = $comic->getAttributes()['view_total'];
        $comic->view_total = $viewTotal + 1;
        if ($today != Carbon::parse($comic->upview_at)->format('Y-m-d')) {
            $comic->view_day = 1;
        } else {
            $comic->view_day += 1;
        }
        if ($firstDayOfWeek->isToday()) {
            $comic->view_week = 1;
        } else {
            $comic->view_week += 1;
        }
        if ($firstDayOfMonth->isToday()) {
            $comic->view_month = 1;
        } else {
            $comic->view_month += 1;
        }
        $comic->upview_at = now();
        $comic->timestamps = false;
        $comic->save();
        $comic->timestamps = true;
    }

    public function upExp(Request $request)
    {
        if (!$request->id) {
            return;
        }

        $user = User::where('id', $request->id)->first();
        $user->exp += 10;
        $user->save();
    }

    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }
        $user = Auth::user();

        return view("/users/profile", compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }
        $user = Auth::user();
        $user->name = $request->name;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $user->avatar = '/uploads/' . $fileName;
        }
        $user->save();
        return response()->json([
            'message' => 'Cập nhật thông tin thành công.'
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $newPass = $request->new_password;
        $oldPass = $request->old_password;
        $confirmPass = $request->new_password_confirm;

        if ($newPass != $confirmPass) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mật khẩu xác nhận không khớp.'
            ], 200);
        }

        $user = Auth::user();

        if (!password_verify($oldPass, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mật khẩu cũ không đúng.'
            ], 200);
        }

        $user->password = bcrypt($newPass);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Đổi mật khẩu thành công.'
        ], 200);
    }

    public function showCategory(Request $request)
    {
        $seo = DB::table('seo')->get();
        $title = 'Kho tổng hợp truyện tranh miễn phí, cập nhật truyện full mới nhất - ' . env('APP_NAME');
        SEOTools::setTitle($title);
        $description = env('APP_NAME') . ' là kho khổng lồ sưu tầm truyện tranh đủ thể loại manga, manhua, manhwa,marvel comics,dc comics hot nhất, cập nhật liên tục';
        SEOTools::setDescription($description);
        $keyword = 'truyện tranh,' . env('APP_NAME');
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $metaHtml = $seo[6]->value;

        $categories = Category::withCount('comics')
            ->with(['comics' => function ($query) {
                $query->with('chapters')->latest('updated_at')->limit(10);
            }])
            ->orderByDesc('comics_count')->limit(5)->get();

        return view("/users/category-list", compact('categories', 'metaHtml'));
    }

    public function showCategoryBySlug($slug, Request $request)
    {
        $status = $request->input('status') ?? "all";
        $sort = $request->input('sort') ?? "0";
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            abort(404);
        }
        $replacements = [
            '{{$category->name}}' => $category->name,
            '{{$category->slug}}' => $category->slug,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[9]->value);
        SEOTools::setTitle($title);
        $description = str_replace(array_keys($replacements), array_values($replacements), $seo[10]->value);
        SEOTools::setDescription($description);
        $keyword = str_replace(array_keys($replacements), array_values($replacements), $seo[11]->value);
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $metaHtml = $seo[6]->value;

        $queryStatus = $request->query('status');
        $querySort = $request->query('sort');
        $comics = Comic::whereHas('categories', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->withCount('votes', 'follows', 'comments', 'chapters')->with('chapters');
        if ($queryStatus) {
            switch ($queryStatus) {
                case '1':
                    $comics->where('status', 'completed');
                    break;
                case '2':
                    $comics->where('status', 'ongoing');
                    break;
            }
        }
        switch ($querySort) {
            case '1':
                $comics->orderByDesc('comics.created_at');
                break;
            case '2':
                $comics->orderByDesc('total_views');
                break;
            case '3':
                $comics->orderByDesc('follows_count');
                break;
            case '4':
                $comics->orderByDesc('comments_count');
                break;
            case '5':
                $comics->orderByDesc('chapters_count');
                break;
            default:
                $comics->orderByDesc('updated_at');
                break;
        }
        $comics = $comics->paginate(24);

        return view("/users/category", compact('comics', 'category', 'metaHtml'));
    }

    public function showAuthor($slug, Request $request)
    {
        $status = $request->input('status') ?? "all";
        $author = Author::where('slug', $slug)->first();
        if (!$author) {
            abort(404);
        }
        $replacements = [
            '{{$author->name}}' => $author->name,
            '{{$author->slug}}' => $author->slug,
        ];
        $seo = DB::table('seo')->get();
        $title = str_replace(array_keys($replacements), array_values($replacements), $seo[12]->value);
        SEOTools::setTitle($title);
        $description = str_replace(array_keys($replacements), array_values($replacements), $seo[13]->value);
        SEOTools::setDescription($description);
        $keyword = str_replace(array_keys($replacements), array_values($replacements), $seo[14]->value);
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');

        $comics = Comic::whereHas('author', function ($query) use ($author) {
            $query->where('id_author', $author->id);
        })->with('chapters');

        if ($status == "1") {
            $comics = $comics->where('status', 'ongoing');
        } else if ($status == "2") {
            $comics = $comics->where('status', 'completed');
        }

        $comics = $comics->paginate(24);
        return view("/users/author", compact('comics', 'author'));
    }

    public function showSearch(Request $request)
    {
        $querySort = $request->query('orderBy');
        $queryName = $request->query('keyword');
        $queryCategory = $request->query('genreIds');
        $comics = Comic::query();
        if ($queryName) {
            $slug = Str::slug($queryName, '-');
            $comics->where('comics.name', 'like', '%' . $queryName . '%')
                ->orWhere('comics.slug', 'like', '%' . $slug . '%')
                ->orWhere('comics.origin_name', 'like', '%' . $queryName . '%');
        }
        if ($queryCategory) {
            $queryCategory = explode(',', $queryCategory);

            $comics->whereHas('categories', function ($query) use ($queryCategory) {
                $query->whereIn('category_id', $queryCategory);
            });
        }

        switch ($querySort) {
            case 'view_asc':
                $comics->orderBy('view_total');
                break;
            case 'udpated_at_date_desc':
                $comics->orderByDesc('updated_at');
                break;
            case 'view_desc':
                $comics->orderByDesc('view_total');
                break;
            case 'udpated_at_date_asc':
                $comics->orderBy('updated_at');
                break;
            case 'created_at_date_desc':
                $comics->orderByDesc('created_at');
                break;
            case 'created_at_date_asc':
                $comics->orderBy('created_at');
                break;
            default:
                $comics->orderByDesc('updated_at');
                break;
        }

        $categories = Category::all();

        $comics = $comics->with('chapters')->paginate(24);
        return view("users.advancedFilter", compact('comics', 'categories'));
    }

    public function showFollow()
    {
        $comics = [];
        if (Auth::check()) {
            $user = Auth::user();
            $comics = Comic::whereHas('follows', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with('chapters')
                ->withCount('follows')
                ->paginate(20);
        } else {
            return redirect()->route('home');
        }
        return view("users.followPage", compact('comics', 'user'));
    }

    public function showHistory()
    {
        $comics = [];
        if (Auth::check()) {
            $user = Auth::user();
            $comics = Comic::whereHas('histories', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->orderByDesc(
                    History::select('updated_at')
                        ->whereColumn('histories.comic_id', 'comics.id')
                        ->latest('updated_at')
                        ->take(1)
                )
                ->withCount('follows')
                ->paginate(20);
            // Batch load: 1 query cho tất cả histories thay vì N queries trong loop
            $comicIds = $comics->pluck('id');
            $histories = DB::table('histories')
                ->where('user_id', $user->id)
                ->whereIn('comic_id', $comicIds)
                ->get()
                ->keyBy('comic_id');

            // Thu thập tất cả chapter IDs cần load
            $chapterIds = [];
            foreach ($comics as $comic) {
                if (isset($histories[$comic->id])) {
                    $chaptersRead = explode(",", $histories[$comic->id]->chapterComics_id);
                    $comic->history = $chaptersRead;
                    $lastChapterId = end($chaptersRead);
                    if ($lastChapterId) {
                        $chapterIds[] = $lastChapterId;
                    }
                }
            }

            // 1 query cho tất cả chapters thay vì N queries trong loop
            $continueChapters = DB::table('chapters')
                ->whereIn('id', $chapterIds)
                ->get()
                ->keyBy('id');

            foreach ($comics as $comic) {
                if (isset($comic->history)) {
                    $lastChapterId = end($comic->history);
                    $comic->conti = $continueChapters[$lastChapterId] ?? null;
                } else {
                    $comic->history = [];
                    $comic->conti = null;
                }
            }
        } else {
            return redirect()->route('home');
        }
        return view("users.historyPage", compact('comics', 'user'));
    }

    public function showComment()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }
        $user = Auth::user();
        $comments = Comment::where('user_id', $user->id)->with(['user', 'comic'])->orderByDesc('created_at')->paginate(10);
        return view("/users/commentPage", compact('comments', 'user'));
    }

    public function changeServer(Request $request)
    {
        $id = $request->id;
        $images = DB::table('chapterimgs')->where('chapter_id', $id)->orderBy('page')->get();
        return response()->json([
            'images' => $images
        ], 200);
    }

    public function saveHistory($table, $id, $chap)
    {
        if ($table == "story") {
            $col = "story_id";
        } else {
            $col = "comic_id";
        }
        if (Auth::check()) {
            $user = Auth::user();
            $check = DB::table('histories')->where('user_id', $user->id)->where($col, $id)->first();
            if ($check) {
                $chaptersWatched = explode(",", $check->chapterComics_id);
                if (!in_array($chap, $chaptersWatched)) {
                    $chaptersWatched[] = $chap;
                    DB::table('histories')->where('user_id', $user->id)->where($col, $id)->update([
                        'chapterComics_id' => implode(",", $chaptersWatched),
                        'updated_at' => now()
                    ]);
                } else {
                    $key = array_search($chap, $chaptersWatched);
                    unset($chaptersWatched[$key]);
                    array_push($chaptersWatched, $chap);
                    DB::table('histories')->where('user_id', $user->id)->where($col, $id)->update([
                        'chapterComics_id' => implode(",", $chaptersWatched),
                        'updated_at' => now()
                    ]);
                }
            } else {
                DB::table('histories')->insert([
                    $col => $id,
                    'user_id' => $user->id,
                    'chapterComics_id' => $chap,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function removeHistory(Request $request)
    {
        $id = $request->id;
        if (Auth::check()) {
            $user = Auth::user();
            History::where('comic_id', $id)->where('user_id', $user->id)->delete();
        }
        return response()->json([
            'message' => 'Xóa lịch sử thành công.'
        ], 200);
    }

    public function reportChapter(Request $request)
    {
        $id = $request->id;
        DB::table('chapters')->where('id', $id)->update([
            'has_report' => DB::raw('has_report + 1')
        ]);

        $cookieName = 'chapter_error_' . $id;
        $cookieValue = 'error';
        $minutes = 60 * 24;
        // $cookie = cookie($cookieName, $cookieValue, $minutes, '/', null, false, false, false);
        // Cookie::queue(Cookie::make($cookieName, $cookieValue, $minutes, '/', null, false, false, false));
        $cookie = cookie($cookieName, $cookieValue, $minutes, '/', null, false, false, false, 'Lax');

        return response()->json([
            'message' => 'Báo cáo thành công.'
        ], 200)->withCookie($cookie);
    }

    public function random()
    {
        $comic = Comic::inRandomOrder()->first();
        return redirect()->route('detail', ['slug' => $comic->slug]);
    }

    public function hot(Request $request)
    {
        $type = $request->input('type') ?? "all";

        if ($type == "all") {
            $comics = Comic::orderByDesc('view_total')->with('chapters')->limit(12)->get();
        } else if ($type == "day") {
            $comics = Comic::orderByDesc('view_day')->with('chapters')->limit(12)->get();
        } else if ($type == "week") {
            $comics = Comic::orderByDesc('view_week')->with('chapters')->limit(12)->get();
        } else if ($type == "month") {
            $comics = Comic::orderByDesc('view_month')->with('chapters')->limit(12)->get();
        }

        $seo = DB::table('seo')->get();
        $title = env('APP_NAME') . ' tranh hot trending đang được xem nhiều nhất | ' . env('APP_NAME');
        SEOTools::setTitle($title);
        $description = env('APP_NAME') . ' cập nhật truyện hot mới nhất trên thị trường. Đọc truyện tranh online đang top trending cập nhật mới nhất tại ' . env('APP_NAME');
        SEOTools::setDescription($description);
        $keyword = 'Truyện Hot,' . env('APP_NAME');
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $metaHtml = $seo[6]->value;

        return view("users.hot", compact('comics', 'type', 'metaHtml'));
    }

    public function showNews()
    {
        $seo = DB::table('seo')->get();
        $title = 'Tin tức truyện tranh mới nhất';
        SEOTools::setTitle($title);
        $description = 'Tổng hợp các thông tin về truyện tranh được cập nhật mới nhất';
        SEOTools::setDescription($description);
        $keyword = 'tin tức, blog tin tức, tin truyện';
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $metaHtml = $seo[6]->value;

        $blogsHot = Blog::where('is_hot', true)->where('status', 'published')->orderBy('created_at', 'desc')->limit(3)->get();
        $blogs = Blog::where('status', 'published')->orderBy('created_at', 'desc')->paginate(12);

        $comicsDaily = Cache::remember('home.comics_daily', 86400, function () {
            return Comic::orderByDesc('view_day')->with('chapters')->limit(6)->get();
        });
        $comicsWeekly = Cache::remember('home.comics_weekly', 86400, function () {
            return Comic::orderByDesc('view_week')->with('chapters')->limit(6)->get();
        });
        $comicsMonthly = Cache::remember('home.comics_monthly', 86400, function () {
            return Comic::orderByDesc('view_month')->with('chapters')->limit(6)->get();
        });

        $comicsNew = Cache::remember('home.comics_new', 86400, function () {
            return Comic::orderByDesc('updated_at')->with('chapters')->limit(5)->get();
        });
        return view("users.news", compact('blogs', 'metaHtml', 'blogsHot', 'comicsDaily', 'comicsWeekly', 'comicsMonthly', 'comicsNew'));
    }

    public function showBlog($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog) {
            abort(404);
        }

        $seo = DB::table('seo')->get();
        $title = $blog->title;
        SEOTools::setTitle($title);
        $description = $blog->meta_description;
        SEOTools::setDescription($description);
        $keyword = $blog->meta_keywords;
        SEOMeta::addKeyword($keyword);
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->setSiteName(env('APP_NAME'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOMeta::setCanonical(url()->current());
        SEOMeta::addMeta('article:published_time', now(), 'property');
        $metaHtml = $seo[6]->value;

        $prevBlog = Blog::where('id', '<', $blog->id)->orderByDesc('id')->first();
        $nextBlog = Blog::where('id', '>', $blog->id)->orderBy('id')->first();

        $blogs = Blog::orderBy('created_at', 'desc')->where('is_hot', true)->where('id', '!=', $blog->id)->limit(3)->get();

        $relatedBlogs = Blog::orderBy('created_at', 'desc')->where('id', '!=', $blog->id)->limit(12)->get();
        return view("users.blog", compact('blog', 'metaHtml', 'prevBlog', 'nextBlog', 'blogs', 'relatedBlogs'));
    }
}
