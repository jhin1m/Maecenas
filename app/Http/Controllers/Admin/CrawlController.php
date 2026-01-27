<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\Comic;
use App\Models\Story;
use App\Models\Category;
use App\Models\ChapterStory;
use Illuminate\Support\Str;
use Goutte\Client as GoutteClient;
use App\GPT\Actions\generateContent\generateContentGPTAction;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Models\Chapter;

class CrawlController extends Controller
{
    public function addComicByCrawl(Request $request)
    {
        $checkComic = DB::table('comics')->where('slug', $request->slug)->first();
        if ($checkComic) {
            $currentChapter = DB::table('chapters')->where('comic_id', $checkComic->id)->where('server', $request->serverName)->orderByRaw('CAST(name AS FLOAT) DESC')->first()->name ?? 0;
            return response()->json([
                'message' => "Thêm thành công bộ " . $request->name,
                'id' => $checkComic->id,
                'currentChapter' => $currentChapter
            ]);
        }

        // Add Comic
        $comic = new Comic();
        $comic->name = $request->name;
        $comic->slug = $request->slug;
        $comic->origin_name = $request->origin_name;
        $comic->status = $request->status;
        $comic->thumbnail = $request->thumbnail;
        $comic->content = $request->content;
        $comic->save();

        // Add Category
        if ($request->categories) {
            foreach ($request->categories as $category) {
                if ($request->serverName != "VIP #1" && $request->serverName != "DamCoNuong") {
                    $checkCategory = Category::where('name', $category['name'])->first();
                } else {
                    $checkCategory = Category::where('slug', $category['slug'])->first();
                }
                if ($checkCategory == null) {
                    $slugCate = Str::slug($category['name'], '-');
                    $idCategory = Category::insertGetId([
                        'name' => $category['name'],
                        'slug' => $slugCate,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $idCategory = $checkCategory->id;
                }

                DB::table('comic_categories')->insert([
                    'comic_id' => $comic->id,
                    'category_id' => $idCategory,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Add Author
        if ($request->serverName == "VIP #1" || $request->serverName == "DamCoNuong") {
            if ($request->author != null && strtolower($request->author) != "đang cập nhật" && strtolower($request->author) != "Updating") {
                $checkAuthor = DB::table('authors')->where('name', $request->author)->first();
                if ($checkAuthor == null) {
                    $slugAuthor = Str::slug($request->author, '-');
                    $idAuthor = DB::table('authors')->insertGetId([
                        'name' => $request->author,
                        'slug' => $slugAuthor,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $idAuthor = $checkAuthor->id;
                }

                DB::table('author_comic')->insert([
                    'id_comic' => $comic->id,
                    'id_author' => $idAuthor,
                ]);
            }
        }
        return response()->json([
            'message' => "Thêm thành công bộ " . $request->name,
            'id' => $comic->id,
            'currentChapter' => 0
        ]);
    }
    public function addChapterByCrawl(Request $request)
    {
        set_time_limit(10000);
        $client = new Client();
        $id = $request->id;
        $comic = DB::table("comics")->where('id', $id)->first();
        if (!$comic) {
            return response()->json("Không tìm thấy.", 404);
        }
        $chapter = $request->chapter;
        if ($request->serverName == "VIP #1") {
            $checkChapter = DB::table('chapters')->where('comic_id', $id)->where('server', $request->serverName)->where('name', $chapter['chapter_name'])->first();
            if ($checkChapter == null) {
                $idChapter = DB::table('chapters')->insertGetId([
                    'server' => $request->serverName,
                    'comic_id' => $id,
                    'title' => $chapter['chapter_title'],
                    'name' => $chapter['chapter_name'],
                    'chapter_number' => $chapter['chapter_name'],
                    'slug' => 'chuong-' . $chapter['chapter_name'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $responseChapter = $client->request('GET', $chapter['chapter_api_data']);
                $contentChapter = $responseChapter->getBody()->getContents();
                $dataChapter = json_decode($contentChapter, true);
                $domain = $dataChapter['data']['domain_cdn'];
                $path = $dataChapter['data']['item']['chapter_path'];
                $url = $domain . "/" . $path;
                foreach ($dataChapter['data']['item']['chapter_image'] as $image) {
                    DB::table('chapterimgs')->insert([
                        'chapter_id' => $idChapter,
                        'page' => $image['image_page'],
                        'link' => $url . "/" . $image['image_file'],
                    ]);
                }
            }
            $nameChapter = $chapter['chapter_name'];
        } else if ($request->serverName == "VIP #2") {
            $nameChapter = $chapter['name'];
            preg_match('/\d+(\.\d+)?/', $nameChapter, $matches);
            $chapterNumber = isset($matches[0]) ? $matches[0] : null;
            $checkChapter = DB::table('chapters')->where('comic_id', $id)->where('server', $request->serverName)->where('name', $chapterNumber)->first();
            if ($checkChapter == null) {
                $idChapter = DB::table('chapters')->insertGetId([
                    'server' => $request->serverName,
                    'comic_id' => $id,
                    'name' => $chapterNumber,
                    'slug' => 'chuong-' . $chapterNumber,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $responseChapter = $client->request('GET', env('LINK_NCOMIC_API') . 'comics/' . $comic->slug . '/chapters/' . $chapter['id']);
                $contentChapter = $responseChapter->getBody()->getContents();
                $dataChapter = json_decode($contentChapter, true);
                foreach ($dataChapter['images'] as $image) {
                    DB::table('chapterimgs')->insert([
                        'chapter_id' => $idChapter,
                        'page' => $image['page'],
                        'link' => $image['src'],
                    ]);
                }
            }
            $nameChapter = $chapterNumber;
        } else if ($request->serverName == "DamCoNuong") {
            $chapterData = $request->chapter_data;
            $nameChapter = $chapterData['chapter_number']; // e.g. "1"
            $fullChapterName = $chapterData['name'] ?? ("Chapter " . $nameChapter);

            $checkChapter = DB::table('chapters')->where('comic_id', $id)->where('server', $request->serverName)->where('name', $nameChapter)->first();

            if ($checkChapter == null) {
                // Fetch images first
                // API: /mangas/{slug}/chapters/{chapter_slug}/images
                // We constructed URL in JS? No, controler does it.
                // Request has manga_slug, chapter_slug, baseUrl.
                $imagesUrl = $request->baseUrl . "/mangas/" . $request->manga_slug . "/chapters/" . $request->chapter_slug . "/images";
                try {
                    $responseImages = $client->request('GET', $imagesUrl);
                    $contentImages = $responseImages->getBody()->getContents();
                    $dataImagesResp = json_decode($contentImages, true);

                    // ChapterImagesResponse -> data -> images (array of strings) or data -> content (from Chapter schema)
                    // Check schema: ChapterImagesResponse.data is $ref Chapter. Chapter has 'images' array.
                    $images = $dataImagesResp['data']['images'] ?? ($dataImagesResp['data']['content'] ?? []);

                    if (!empty($images)) {
                        $idChapter = DB::table('chapters')->insertGetId([
                            'server' => $request->serverName,
                            'comic_id' => $id,
                            'name' => $nameChapter,
                            'title' => $fullChapterName,
                            'slug' => 'chuong-' . $nameChapter,
                            'chapter_number' => $nameChapter,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        foreach ($images as $index => $imageUrl) {
                            DB::table('chapterimgs')->insert([
                                'chapter_id' => $idChapter,
                                'page' => $index + 1,
                                'link' => $imageUrl,
                            ]);
                        }
                    } else {
                        return response()->json("Không tìm thấy ảnh chương " . $nameChapter, 404);
                    }
                } catch (\Exception $e) {
                    return response()->json("Lỗi khi lấy ảnh chương: " . $e->getMessage(), 500);
                }
            }
        }
        return response()->json("Thêm thành công bộ " . $comic->name . " chương " . $nameChapter);
    }

    public function saveImageToHost($url, $referer, $folder = null)
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                'Referer' => $referer,
            ]
        ]);
        $response = $client->request('GET', $url);
        $content = $response->getBody();
        $name = basename($url);
        if ($folder) {
            Storage::put($folder . '/' . $name, $content);
            $fullPath = env('APP_URL') . '/storage/' . $folder . '/' . $name;
            return $fullPath;
        }
        Storage::put('thumbnails/' . $name, $content);
        $fullPath = env('APP_URL') . '/storage/thumbnails/' . $name;
        return $fullPath;
    }

    private function uploadImageAsync(Client $client, $imageUrl, $referer)
    {
        $client = new Client();
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
        $referer = $referer;
        $response = $client->request('GET', $imageUrl, [
            'headers' => [
                'User-Agent' => $userAgent,
                'Referer' => $referer,
            ],
        ]);
        $imageContent = $response->getBody();
        $imageName = basename($imageUrl);

        return $client->requestAsync('POST', 'https://api.pixhost.to/images', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'multipart' => [
                [
                    'name' => 'img',
                    'contents' => $imageContent,
                    'filename' => $imageName,
                ],
                [
                    'name' => 'content_type',
                    'contents' => '1',
                ],
                [
                    'name' => 'max_th_size',
                    'contents' => '500',
                ],
            ],
        ]);
    }
}
