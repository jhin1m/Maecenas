<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\SEOTools;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Comic;
use App\Models\Story;
use Spatie\Sitemap\SitemapIndex;
use Illuminate\Support\Facades\File;
use App\Models\Author;
use App\Models\Chapter;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    public function index(){
        $seo = DB::table('seo')->get();
        return view('pages.seo', compact('seo'));
    }

    public function update(Request $request){
        $requestData = $request->all();
        $data = $requestData['data'];
        foreach($data as $item){
            DB::table('seo')->where('key', $item['key'])->update(['value' => $item['value']]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công',
            'data' => $data,
        ], 200);
    }

    public function sitemap(){
        return view('pages.sitemap');
    }

    public function generateSitemap(){
        File::deleteDirectory(public_path('sitemaps/category'));
        File::deleteDirectory(public_path('sitemaps/comic'));
        File::deleteDirectory(public_path('sitemaps/author'));

        File::makeDirectory(public_path('sitemaps/category'), 0755, true, true);
        File::makeDirectory(public_path('sitemaps/comic'), 0755, true, true);
        File::makeDirectory(public_path('sitemaps/author'), 0755, true, true);

        $sitemapIndex = SitemapIndex::create();

        Category::query()->orderBy('id')->chunk(500, function ($categories) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($categories as $category) {
                $sitemap->add(Url::create(route('showCategory', ['slug' => $category->slug])));
            }

            $sitemapPath = 'sitemaps/category/category_sitemap_' . $categories->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        Comic::query()->orderBy('id')->chunk(500, function ($comics) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($comics as $comic) {
                $sitemap->add(Url::create(route('detail', ['slug' => $comic->slug])));
            }

            $sitemapPath = 'sitemaps/comic/comic_sitemap_' . $comics->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        Author::query()->orderBy('id')->chunk(500, function ($authors) use ($sitemapIndex) {
            $sitemap = Sitemap::create();

            foreach ($authors as $author) {
                if($author->slug){
                    $sitemap->add(Url::create(route('showAuthor', ['slug' => $author->slug])));
                }
            }

            $sitemapPath = 'sitemaps/author/author_sitemap_' . $authors->first()->id . '.xml';
            $sitemap->writeToFile(public_path($sitemapPath));

            $sitemapIndex->add('/' . $sitemapPath);
        });

        $sitemapIndex->writeToFile(public_path('sitemap.xml'));
        return response()->json([
            'status' => 'success',
            'message' => 'Tạo sitemap thành công',
        ], 200);
    }
}
