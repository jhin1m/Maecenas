<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Comic;
use Spatie\Sitemap\SitemapIndex;
use Illuminate\Support\Facades\File;
use App\Models\Author;
use App\Models\Chapter;
use Illuminate\Support\Str;


class ApiController extends Controller
{
    public function generateSitemap ()
    {
        File::deleteDirectory(public_path('sitemaps/category'));
        File::deleteDirectory(public_path('sitemaps/comic'));

        File::makeDirectory(public_path('sitemaps/category'), 0755, true, true);
        File::makeDirectory(public_path('sitemaps/comic'), 0755, true, true);

        $sitemapIndex = SitemapIndex::create();

        Category::query()->orderBy('id')->chunk(500, function ($categories) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($categories as $category) {
                $sitemap->add(Url::create('/the-loai/' . $category->slug));
            }

            $sitemapPath = 'sitemaps/category/category_sitemap_' . $categories->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        Comic::query()->orderBy('id')->chunk(500, function ($comics) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($comics as $comic) {
                $sitemap->add(Url::create('/' . $comic->slug));
            }

            $sitemapPath = 'sitemaps/comic/comic_sitemap_' . $comics->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        $sitemapIndex->writeToFile(public_path('sitemap.xml'));
    }

    public function searchManga(Request $request){
        $keyword = $request->query('keyword');
        $slug = Str::slug($keyword, '-');
        $mangas = Comic::query()
            ->select('id', 'name', 'slug', 'origin_name', 'thumbnail')
            ->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('slug', 'like', '%' . $slug . '%')
            ->orWhere('origin_name', 'like', '%' . $keyword . '%')
            ->with('lastChapter')
            ->limit(10)
            ->get();
        foreach ($mangas as $manga) {
            $manga->url = route('detail', ['slug' => $manga->slug]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $mangas,
        ]);
    }
}
