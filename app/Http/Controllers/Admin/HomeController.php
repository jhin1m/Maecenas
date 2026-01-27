<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Story;
use App\Models\Comic;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function showDashboard()
    {
        $totalUser = DB::table('users')->count();
        $countComic = DB::table('comics')->count();
        $totalChapter = DB::table('chapters')->count();
        $totalViews = DB::table('comics')->sum('view_total');
        $todayViews = DB::table('comics')
            ->sum('view_day');
        $yesterdayViews = 0;
        $todayUser = DB::table('users')
            ->whereDate('created_at', now())
            ->count();
        $todayChapter = DB::table('chapters')
            ->whereDate('created_at', now())
            ->count();
        $todayTotalM = DB::table('comics')
            ->whereDate('created_at', now())
            ->count();
        setlocale(LC_TIME, 'vi_VN.UTF-8');
        $daysOfWeek = collect(range(0, 6))->mapWithKeys(function ($day) {
            $date = Carbon::now()->startOfWeek()->addDays($day);
            $formattedDate = $date->formatLocalized('%A');
            return [$formattedDate => 0];
        });

        $weeklyViews = DB::table('weekly_ranking')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_views) as views'))
            ->whereDate('created_at', '>=', Carbon::now()->startOfWeek())
            ->whereDate('created_at', '<=', Carbon::now()->endOfWeek())
            ->groupBy('date')
            ->get()
            ->keyBy(function ($item) {
                $date = Carbon::parse($item->date);
                return $date->formatLocalized('%A');
            })
            ->map(function ($item) {
                return $item->views;
            });

        $weeklyViews = $daysOfWeek->merge($weeklyViews);
        $comicsMonthly = Comic::orderByDesc('view_month')->limit(10)->get();
        $categoriesWithMostViews = DB::table('comics')
            ->join('comic_categories', 'comics.id', '=', 'comic_categories.comic_id')
            ->join('categories', 'comic_categories.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('sum(comics.view_total) as view_total'))
            ->groupBy('categories.name')
            ->orderBy('view_total', 'desc')
            ->limit(9)
            ->get();
        return view('pages.dashboard', [
            'totalUser' => $totalUser,
            'countComic' => $countComic,
            'totalChapter' => $totalChapter,
            'totalViews' => $totalViews,
            'comicsMonthly' => $comicsMonthly,
            'todayViews' => $todayViews,
            'yesterdayViews' => $yesterdayViews,
            'todayUser' => $todayUser,
            'todayChapter' => $todayChapter,
            'todayTotalM' => $todayTotalM,
            'weeklyViews' => $weeklyViews,
            'categoriesWithMostViews' => $categoriesWithMostViews,
        ]);
    }

    public function showApi()
    {
        return view('pages.crawl');
    }

    public function showApi1()
    {
        return view('pages.crawlTF');
    }

    public function showApiDamCoNuong()
    {
        return view('pages.crawl_damconuong');
    }

    public function showAdvanced()
    {
        $notification = DB::table('notifications')->where('title', 'main')->first();
        $theme = DB::table('themes')->first();
        return view('pages.advanced', [
            'notification' => $notification,
            'theme' => $theme
        ]);
    }

    public function updateAdvanced(Request $request)
    {
        $notification = DB::table('notifications')->where('title', 'main')->first();
        if ($notification) {
            DB::table('notifications')->where('title', 'main')->update([
                'content' => $request->notification
            ]);
        } else {
            DB::table('notifications')->insert([
                'title' => 'main',
                'content' => $request->notification
            ]);
        }
        $theme = DB::table('themes')->first();
        if ($theme) {
            DB::table('themes')->update([
                'footer' => $request->footer,
                'header' => $request->header
            ]);
        } else {
            DB::table('themes')->insert([
                'footer' => $request->footer,
                'header' => $request->header
            ]);
        }
        return response()->json([
            'message' => 'Cập nhật thành công.'
        ], 200);
        ;
    }

    public function addKeyword(Request $request)
    {
        $keyword = $request->keyword;
        $check = DB::table('keywords_ban')->where('keyword', $keyword)->first();
        if ($check) {
            return response()->json([
                'title' => 'Thất bại',
                'message' => 'Từ khóa đã tồn tại.',
                'type' => 'error',
            ]);
        }
        DB::table('keywords_ban')->insert([
            'keyword' => $keyword,
        ]);
        return response()->json([
            'title' => 'Thành công',
            'message' => 'Thêm thành công',
            'type' => 'success',
        ]);
    }

    public function deleteKeyword(Request $request)
    {
        $id = $request->id;
        DB::table('keywords_ban')->where('id', $id)->delete();
        return response()->json([
            'title' => 'Thành công',
            'message' => 'Xóa thành công',
            'type' => 'success',
        ]);
    }

    public function showSetting()
    {
        return view('pages.setting');
    }

    public function saveConfig(Request $request)
    {
        $data = $request->only([
            'BASE_URL_TRUYENQQ',
            'BASE_URL_NETTRUYEN',
            'OPENAI_API_KEY',
            'PROXY_URL',
            '2CAPTCHA_API_KEY',
        ]);

        $checkboxFields = [
            'TRUYENQQ_CAPTCHA',
            'NETTRUYEN_CAPTCHA',
        ];

        foreach ($checkboxFields as $field) {
            $data[$field] = $request->has($field) ? 'true' : 'false';
        }

        foreach ($data as $key => $value) {
            $this->setEnvValue($key, $value);
        }

        return redirect()->back()->with('success', 'Cấu hình đã được lưu thành công.');
    }

    protected function setEnvValue($key, $value)
    {
        $path = base_path('.env');
        $env = file_get_contents($path);

        $oldValue = env($key);

        if (strpos($value, ' ') !== false) {
            $value = '"' . $value . '"';
        }
        if (strpos($env, "{$key}=") === false) {
            $env .= "\n{$key}={$value}\n";
        } else {
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $env
            );
        }

        file_put_contents($path, $env);
    }
}
