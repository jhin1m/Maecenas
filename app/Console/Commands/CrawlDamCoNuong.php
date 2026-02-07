<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comic;
use App\Models\Chapter;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CrawlDamCoNuong extends Command
{
    /**
     * Command signature — cách gọi từ terminal.
     * Ví dụ: php artisan crawl:damconuong 1 5
     * Sẽ crawl trang 1 đến trang 5 của DamCoNuong API.
     */
    protected $signature = 'crawl:damconuong {page} {topage}';

    protected $description = 'Crawl DamCoNuong API from page {page} to {topage}';

    /** Tên server — dùng để phân biệt nguồn dữ liệu trong DB */
    private const SERVER_NAME = 'DamCoNuong';

    public function handle()
    {
        $page = $this->argument('page');
        $topage = $this->argument('topage');

        // Lấy base URL từ .env, có fallback mặc định nếu chưa config
        $baseUrl = rtrim(env('LINK_DAMCONUONG_API', 'https://api.mymanga.vn/api/v1'), '/');
        $client = new Client(['timeout' => 30]);

        $this->info("Bắt đầu crawl DamCoNuong từ trang $page đến $topage");
        $this->info("Base URL: $baseUrl");

        // === BƯỚC 1: Thu thập danh sách slug truyện từ các trang listing ===
        $comicSlugs = $this->collectComicSlugs($client, $baseUrl, $page, $topage);

        if (empty($comicSlugs)) {
            $this->warn('Không tìm thấy truyện nào.');
            return;
        }

        $this->info("Tổng cộng: " . count($comicSlugs) . " truyện. Bắt đầu crawl...");
        $this->newLine();

        // Đảo ngược để crawl từ truyện cũ nhất trước (giống CrawlOTruyen)
        $comicSlugs = array_reverse($comicSlugs);

        // === BƯỚC 2: Crawl từng truyện ===
        foreach ($comicSlugs as $slug) {
            $this->crawlSingleComic($client, $baseUrl, $slug);
        }

        $this->newLine();
        $this->info('Hoàn tất crawl DamCoNuong!');
    }

    /**
     * Thu thập tất cả slug truyện từ API listing.
     * API: GET {baseUrl}/mangas?page={i}
     */
    private function collectComicSlugs(Client $client, string $baseUrl, int $fromPage, int $toPage): array
    {
        $slugs = [];

        for ($i = $fromPage; $i <= $toPage; $i++) {
            $this->info("Đang tải danh sách trang $i...");
            try {
                $response = $client->request('GET', "$baseUrl/mangas?page=$i");
                $data = json_decode($response->getBody()->getContents(), true);

                // API có thể trả về nhiều format khác nhau
                // Thử lần lượt: data.data (paginated) → data (array) → root array
                $comics = $data['data']['data'] ?? $data['data'] ?? [];

                if (!is_array($comics)) {
                    $comics = [];
                }

                foreach ($comics as $comic) {
                    if (isset($comic['slug'])) {
                        $slugs[] = $comic['slug'];
                    }
                }

                $this->info("  Tìm thấy " . count($comics) . " truyện.");
            } catch (\Exception $e) {
                $this->error("  Lỗi trang $i: " . $e->getMessage());
            }
        }

        return $slugs;
    }

    /**
     * Crawl 1 truyện: tạo/cập nhật comic → crawl chapters → lưu ảnh.
     */
    private function crawlSingleComic(Client $client, string $baseUrl, string $slug): void
    {
        try {
            // Lấy chi tiết truyện từ API
            $response = $client->request('GET', "$baseUrl/mangas/$slug");
            $body = json_decode($response->getBody()->getContents(), true);
            $comicData = $body['data'];

            $this->info("Đang crawl: " . $comicData['name']);

            // Tạo hoặc lấy comic từ DB
            [$idComic, $currentChapter] = $this->createOrGetComic($comicData);

            // Lấy danh sách chapters (có hỗ trợ phân trang)
            $chapters = $this->fetchAllChapters($client, $baseUrl, $slug);
            $this->info("  Tổng chương: " . count($chapters));

            // Đảo ngược để crawl từ chương cũ nhất → mới nhất
            $chapters = array_reverse($chapters);

            // Crawl từng chapter
            foreach ($chapters as $chapter) {
                $this->crawlSingleChapter($client, $baseUrl, $slug, $chapter, $idComic, $currentChapter);
            }

            $this->info("Crawl xong: " . $comicData['name']);
            $this->newLine();

        } catch (\Exception $e) {
            $this->error("Lỗi crawl $slug: " . $e->getMessage());
        }
    }

    /**
     * Tạo comic mới hoặc lấy comic đã tồn tại.
     * Trả về [comic_id, currentChapter].
     *
     * Tại sao check slug? Vì slug là unique identifier — nếu đã có thì chỉ cần
     * tiếp tục crawl chapter mới, không tạo trùng.
     */
    private function createOrGetComic(array $comicData): array
    {
        $existing = DB::table('comics')->where('slug', $comicData['slug'])->first();

        if ($existing) {
            // Tìm chapter mới nhất đã crawl để skip những chapter cũ
            $currentChapter = DB::table('chapters')
                ->where('comic_id', $existing->id)
                ->where('server', self::SERVER_NAME)
                ->orderByRaw('CAST(name AS FLOAT) DESC')
                ->value('name') ?? 0;

            $this->info("  Truyện đã tồn tại (ID: {$existing->id}). Chương hiện tại: $currentChapter");
            return [$existing->id, $currentChapter];
        }

        // Tạo comic mới
        $comic = new Comic();
        $comic->name = $comicData['name'];
        $comic->slug = $comicData['slug'];
        $comic->origin_name = $comicData['name_alt'] ?? '';
        $comic->content = $comicData['pilot'] ?? '';
        $comic->status = $comicData['status'] ?? 'ongoing';
        $comic->thumbnail = $comicData['cover_full_url'] ?? '';
        $comic->total_views = $comicData['views'] ?? 0;
        $comic->save();

        // Gán thể loại (genres → categories)
        $this->syncCategories($comic->id, $comicData['genres'] ?? []);

        // Gán tác giả
        $authorName = $comicData['artist']['name'] ?? null;
        $this->syncAuthor($comic->id, $authorName);

        $this->info("  Tạo truyện mới thành công (ID: {$comic->id}).");
        return [$comic->id, 0];
    }

    /**
     * Đồng bộ thể loại: tìm hoặc tạo category, rồi gắn vào comic.
     * Dùng slug để check (giống logic trong CrawlController cho DamCoNuong).
     */
    private function syncCategories(int $comicId, array $genres): void
    {
        foreach ($genres as $genre) {
            $category = DB::table('categories')->where('slug', $genre['slug'])->first();

            if (!$category) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $genre['name'],
                    'slug' => $genre['slug'] ?? Str::slug($genre['name'], '-'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $categoryId = $category->id;
            }

            DB::table('comic_categories')->insert([
                'comic_id' => $comicId,
                'category_id' => $categoryId,
            ]);
        }
    }

    /**
     * Gán tác giả cho comic.
     * Bỏ qua nếu tên tác giả là "Đang cập nhật" hoặc "Updating".
     */
    private function syncAuthor(int $comicId, ?string $authorName): void
    {
        if (!$authorName || in_array(strtolower($authorName), ['đang cập nhật', 'updating'])) {
            return;
        }

        $author = DB::table('authors')->where('name', $authorName)->first();

        if (!$author) {
            $authorId = DB::table('authors')->insertGetId([
                'name' => $authorName,
                'slug' => Str::slug($authorName, '-'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $authorId = $author->id;
        }

        DB::table('author_comic')->insert([
            'id_comic' => $comicId,
            'id_author' => $authorId,
        ]);
    }

    /**
     * Lấy TẤT CẢ chapters với hỗ trợ phân trang.
     * API: GET {baseUrl}/mangas/{slug}/chapters?per_page=1000&page={n}
     *
     * Tại sao per_page=1000? Để giảm số request. Nếu truyện có >1000 chương,
     * vòng while sẽ tự động lấy trang tiếp theo.
     */
    private function fetchAllChapters(Client $client, string $baseUrl, string $slug): array
    {
        $chapters = [];
        $page = 1;

        while (true) {
            try {
                $response = $client->request('GET', "$baseUrl/mangas/$slug/chapters?per_page=1000&page=$page");
                $data = json_decode($response->getBody()->getContents(), true);

                // Tương tự listing, API có thể trả paginated hoặc direct array
                $fetched = [];
                if (isset($data['data']) && is_array($data['data'])) {
                    if (isset($data['data']['data']) && is_array($data['data']['data'])) {
                        $fetched = $data['data']['data'];
                    } else {
                        $fetched = $data['data'];
                    }
                }

                if (empty($fetched)) break;

                $chapters = array_merge($chapters, $fetched);

                // Dừng nếu đã hết trang hoặc số kết quả < per_page
                if (isset($data['meta']['last_page']) && $page >= $data['meta']['last_page']) break;
                if (count($fetched) < 1000) break;

                $page++;
            } catch (\Exception $e) {
                $this->warn("  Lỗi tải chapters trang $page: " . $e->getMessage());
                break;
            }
        }

        return $chapters;
    }

    /**
     * Crawl 1 chapter: lấy ảnh → lưu chapter + ảnh vào DB.
     */
    private function crawlSingleChapter(
        Client $client,
        string $baseUrl,
        string $mangaSlug,
        array $chapter,
        int $comicId,
        float $currentChapter
    ): void {
        $chapterNumber = $chapter['chapter_number'] ?? null;
        if (!$chapterNumber) return;

        // Skip chapter đã crawl rồi
        if (floatval($chapterNumber) <= $currentChapter) return;

        // Kiểm tra trùng trong DB
        $exists = DB::table('chapters')
            ->where('comic_id', $comicId)
            ->where('server', self::SERVER_NAME)
            ->where('name', $chapterNumber)
            ->exists();

        if ($exists) return;

        try {
            // Rate limiting — tránh bị API block vì gửi quá nhiều request
            sleep(1);

            // Lấy danh sách ảnh của chapter
            $chapterSlug = $chapter['slug'];
            $imgResponse = $client->request('GET', "$baseUrl/mangas/$mangaSlug/chapters/$chapterSlug/images");
            $imgData = json_decode($imgResponse->getBody()->getContents(), true);

            // API có thể trả images hoặc content
            $images = $imgData['data']['images'] ?? $imgData['data']['content'] ?? [];

            if (empty($images)) {
                $this->warn("  Chương $chapterNumber: không có ảnh, bỏ qua.");
                return;
            }

            // Lưu chapter vào DB
            $fullName = $chapter['name'] ?? ("Chapter " . $chapterNumber);
            $newChapter = new Chapter();
            $newChapter->server = self::SERVER_NAME;
            $newChapter->comic_id = $comicId;
            $newChapter->name = $chapterNumber;
            $newChapter->title = $fullName;
            $newChapter->slug = 'chuong-' . $chapterNumber;
            $newChapter->chapter_number = $chapterNumber;
            $newChapter->save();

            // Lưu từng ảnh — page bắt đầu từ 1 (không phải 0)
            foreach ($images as $index => $imageUrl) {
                DB::table('chapterimgs')->insert([
                    'chapter_id' => $newChapter->id,
                    'page' => $index + 1,
                    'link' => $imageUrl,
                ]);
            }

            // Cập nhật thời gian truyện để hiển thị "vừa cập nhật" trên frontend
            Comic::where('id', $comicId)->update(['updated_at' => now()]);

            $this->info("  Chương $chapterNumber: " . count($images) . " ảnh OK");

        } catch (\Exception $e) {
            $this->error("  Chương $chapterNumber: " . $e->getMessage());
        }
    }
}
