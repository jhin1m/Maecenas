<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comic;
use App\Models\Chapter;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CrawlOTruyen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:otruyen {page} {topage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl Otruyen from page {page} to {topage}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = $this->argument('page');
        $topage = $this->argument('topage');
        $comics = [];
        $client = new Client();
        $this->info('Bắt đầu cào truyện');
        try{
            for($i = $page; $i <= $topage; $i++){
                $this->info("Đang crawl trang $i OTruyen");
                $url = "https://otruyenapi.com/v1/api/danh-sach?page=$i";
                $response = $client->request
                ('GET', $url);
                $data = json_decode($response->getBody()->getContents(), true);
                $data = $data['data']['items'];
                foreach($data as $item){
                    $comics[] = [
                        'slug' => $item['slug'],
                    ];
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $comics = array_reverse($comics);
        foreach($comics as $item){
            try{
                $url = env('LINK_OTRUYEN_API').$item['slug'];
                $response = $client->request('GET', $url);
                $body = json_decode($response->getBody()->getContents(), true);
                $data = $body['data']['item'];
                $checkComic = DB::table('comics')->where('slug', $data['slug'])->first();
                $chapters = $data['chapters'][0]['server_data'] ?? [];
                $this->info("Đang crawl truyện " . $data['name']);
                if($checkComic){
                    $idComic = $checkComic->id;
                    $chapters = array_reverse($chapters);
                }else{
                    $comic = new Comic();
                    $comic->name = $data['name'];
                    $comic->origin_name = implode(", ", $data['origin_name']);
                    $comic->slug = $data['slug'];
                    $comic->content = $data['content'];
                    $comic->status = $data['status'];
                    $comic->thumbnail = $body['data']['seoOnPage']['seoSchema']['image'];
                    $comic->save();
                    $idComic = $comic->id;
                    foreach($data['category'] as $category){
                        $checkCategory = DB::table('categories')->where('slug', $category['slug'])->first();
                        if(!$checkCategory){
                            $idCategory = DB::table('categories')->insertGetId([
                                'name' => $category['name'],
                                'slug' => $category['slug'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }else{
                            $idCategory = $checkCategory->id;
                        }
                        DB::table('comic_categories')->insert([
                            'comic_id' => $idComic,
                            'category_id' => $idCategory,
                        ]);
                    }
                    $authors = implode(", ", $data['author']);
                    $checkAuthor = DB::table('authors')->where('name', $authors)->first();
                    if(!$checkAuthor){
                        $idAuthor = DB::table('authors')->insertGetId([
                            'name' => $authors,
                            'slug' => Str::slug($authors, '-'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }else{
                        $idAuthor = $checkAuthor->id;
                    }
                    DB::table('author_comic')->insert([
                        'id_comic' => $idComic,
                        'id_author' => $idAuthor,
                    ]);
                }
                $this->info("Crawl truyện " . $data['name'] . " thành công.");
                if(count($chapters) > 0){
                    foreach($chapters as $chapter){
                        $this->info("Đang crawl chương " . $chapter['chapter_name'] . " của truyện " . $data['name']);
                        $url = $chapter['chapter_api_data'];
                        $checkChapter = Chapter::where('name', $chapter['chapter_name'])->where('server', 'VIP #1')->where('comic_id', $idComic)->first();
                        if($checkChapter){
                            continue;
                        }
                        try {
                            sleep(1);
                            $responseChapter = $client->request('GET', $url);
                            $contentChapter = $responseChapter->getBody()->getContents();
                            $dataChapter = json_decode($contentChapter, true);
                            $domain = $dataChapter['data']['domain_cdn'];
                            $path = $dataChapter['data']['item']['chapter_path'];
                            $url = $domain . "/" . $path;

                            $newChapter = new Chapter();
                            $newChapter->server = 'VIP #1';
                            $newChapter->comic_id = $idComic;
                            $newChapter->title = $chapter['chapter_title'];
                            $newChapter->name = $chapter['chapter_name'];
                            $newChapter->slug = 'chuong-'. $chapter['chapter_name'];
                            $newChapter->chapter_number = $chapter['chapter_name'];
                            $newChapter->save();

                            foreach ($dataChapter['data']['item']['chapter_image'] as $image) {
                                DB::table('chapterimgs')->insert([
                                    'chapter_id' => $newChapter->id,
                                    'page' => $image['image_page'],
                                    'link' => $url . "/" . $image['image_file'],
                                ]);
                            }
                        } catch (\Exception $e) {
                            $this->error($e->getMessage());
                        }

                        Comic::where('id', $idComic)->update([
                            'updated_at' => now(),
                        ]);

                        $this->info("Crawl chương " . $chapter['chapter_name'] . " thành công.");
                    }
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
