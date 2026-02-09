<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Import truyện trực tiếp từ DB DamCoNuong (mymanga) sang DB chính (hangtruyen).
 *
 * Tại sao import DB thay vì crawl API?
 * - Crawl API: ~187K requests × 1s delay = ~52 giờ
 * - Import DB: đọc batch → ghi batch = vài phút
 *
 * Cách chạy:
 *   php artisan import:damconuong                  # Import tất cả
 *   php artisan import:damconuong --only-chapters   # Chỉ import chapters (nếu comics đã có)
 *   php artisan import:damconuong --skip-chapters   # Chỉ import comics, bỏ qua chapters
 */
class ImportDamCoNuong extends Command
{
    protected $signature = 'import:damconuong
        {--skip-chapters : Bỏ qua import chapters}
        {--only-chapters : Chỉ import chapters (comics phải đã có)}
        {--chunk=500 : Số record xử lý mỗi batch}';

    protected $description = 'Import truyện từ DB DamCoNuong (mymanga) sang DB chính';

    private const SERVER_NAME = 'DamCoNuong';

    /** Lưu mapping UUID nguồn → ID đích (bigint auto_increment) */
    private array $genreMap = [];    // genre_id → category_id
    private array $artistMap = [];   // artist UUID → author_id
    private array $mangaMap = [];    // manga UUID → comic_id

    /** Đếm số record đã import */
    private int $comicCount = 0;
    private int $chapterCount = 0;
    private int $imageCount = 0;

    public function handle(): void
    {
        $chunkSize = (int) $this->option('chunk');

        $this->info('=== Import DamCoNuong DB → Hệ thống ===');
        $this->newLine();

        // Kiểm tra kết nối nguồn
        if (!$this->testSourceConnection()) return;

        if (!$this->option('only-chapters')) {
            // Phase 1-3: Import comics + relationships
            $this->importCategories();
            $this->importAuthors($chunkSize);
            $this->importComics($chunkSize);
        } else {
            // Nếu chỉ import chapters, cần load mapping từ DB
            $this->loadExistingMappings();
        }

        if (!$this->option('skip-chapters')) {
            // Phase 4: Import chapters + images
            $this->importChapters($chunkSize);
        }

        $this->printSummary();
    }

    /**
     * Kiểm tra kết nối đến DB nguồn có hoạt động không.
     * Luôn test connection trước khi bắt đầu import để tránh lỗi giữa chừng.
     */
    private function testSourceConnection(): bool
    {
        try {
            $count = DB::connection('damconuong')->table('mangas')->count();
            $this->info("Kết nối thành công! Nguồn có $count truyện.");
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error('Không kết nối được DB nguồn: ' . $e->getMessage());
            $this->error('Kiểm tra config "damconuong" trong config/database.php');
            return false;
        }
    }

    // =========================================================================
    // PHASE 1: Import Categories (genres → categories)
    // =========================================================================

    private function importCategories(): void
    {
        $this->info('--- Phase 1: Import thể loại ---');

        $genres = DB::connection('damconuong')->table('genres')->get();

        foreach ($genres as $genre) {
            // Check trùng bằng cả slug VÀ name (cả 2 đều UNIQUE trong DB đích)
            $existing = DB::table('categories')
                ->where('slug', $genre->slug)
                ->orWhere('name', $genre->name)
                ->first();

            if ($existing) {
                $this->genreMap[$genre->id] = $existing->id;
                continue;
            }

            try {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $genre->name,
                    'slug' => $genre->slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->genreMap[$genre->id] = $categoryId;
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Race condition hoặc data trùng — lấy record đã tồn tại
                $existing = DB::table('categories')
                    ->where('slug', $genre->slug)
                    ->orWhere('name', $genre->name)
                    ->first();
                if ($existing) {
                    $this->genreMap[$genre->id] = $existing->id;
                }
            }
        }

        $this->info("  Hoàn tất: " . count($this->genreMap) . " thể loại mapped.");
        $this->newLine();
    }

    // =========================================================================
    // PHASE 2: Import Authors (artists → authors)
    // =========================================================================

    private function importAuthors(int $chunkSize): void
    {
        $this->info('--- Phase 2: Import tác giả ---');

        DB::connection('damconuong')->table('artists')
            ->orderBy('id')
            ->chunk($chunkSize, function ($artists) {
                foreach ($artists as $artist) {
                    $existing = DB::table('authors')->where('slug', $artist->slug)->first();

                    if ($existing) {
                        $this->artistMap[$artist->id] = $existing->id;
                        continue;
                    }

                    // Tránh trùng name (UNIQUE constraint)
                    $existByName = DB::table('authors')->where('name', $artist->name)->first();
                    if ($existByName) {
                        $this->artistMap[$artist->id] = $existByName->id;
                        continue;
                    }

                    $authorId = DB::table('authors')->insertGetId([
                        'name' => $artist->name,
                        'slug' => $artist->slug,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->artistMap[$artist->id] = $authorId;
                }
            });

        $this->info("  Hoàn tất: " . count($this->artistMap) . " tác giả mapped.");
        $this->newLine();
    }

    // =========================================================================
    // PHASE 3: Import Comics (mangas → comics + relationships)
    // =========================================================================

    private function importComics(int $chunkSize): void
    {
        $this->info('--- Phase 3: Import truyện ---');

        $total = DB::connection('damconuong')->table('mangas')->count();
        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Đang import...');

        DB::connection('damconuong')->table('mangas')
            ->orderBy('created_at')
            ->chunk($chunkSize, function ($mangas) use ($bar) {
                foreach ($mangas as $manga) {
                    $this->importSingleComic($manga);
                    $bar->advance();
                }
            });

        $bar->setMessage('Hoàn tất!');
        $bar->finish();
        $this->newLine();
        $this->info("  Import: {$this->comicCount} truyện mới.");
        $this->newLine();
    }

    private function importSingleComic(object $manga): void
    {
        // Skip nếu slug hoặc name đã tồn tại (cả 2 đều UNIQUE)
        $existing = DB::table('comics')
            ->where('slug', $manga->slug)
            ->orWhere('name', $manga->name)
            ->first();

        if ($existing) {
            $this->mangaMap[$manga->id] = $existing->id;
            return;
        }

        // Mapping status: nguồn dùng int (1=completed, 2=ongoing)
        $status = match ($manga->status) {
            1 => 'completed',
            2 => 'ongoing',
            default => 'ongoing',
        };

        try {
            $comicId = DB::table('comics')->insertGetId([
                'name' => $manga->name,
                'slug' => $manga->slug,
                'origin_name' => $manga->name_alt ?? '',
                'content' => $manga->pilot ?? '',
                'status' => $status,
                'thumbnail' => $manga->cover ?? '',
                'view_total' => $manga->views ?? 0,
                'view_day' => $manga->views_day ?? 0,
                'view_week' => $manga->views_week ?? 0,
                'is_hot' => $manga->is_hot ?? 0,
                'rating' => $manga->average_rating ?? 0,
                'total_votes' => $manga->total_ratings ?? 0,
                'created_at' => $manga->created_at,
                'updated_at' => $manga->updated_at,
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Trùng name/slug — lấy record đã có
            $existing = DB::table('comics')
                ->where('slug', $manga->slug)
                ->orWhere('name', $manga->name)
                ->first();
            if ($existing) {
                $this->mangaMap[$manga->id] = $existing->id;
            }
            return;
        }

        $this->mangaMap[$manga->id] = $comicId;
        $this->comicCount++;

        // Gán thể loại
        $this->syncComicCategories($manga->id, $comicId);

        // Gán tác giả
        if ($manga->artist_id && isset($this->artistMap[$manga->artist_id])) {
            DB::table('author_comic')->insertOrIgnore([
                'id_comic' => $comicId,
                'id_author' => $this->artistMap[$manga->artist_id],
            ]);
        }
    }

    private function syncComicCategories(string $mangaId, int $comicId): void
    {
        $genreIds = DB::connection('damconuong')
            ->table('manga_has_genres')
            ->where('manga_id', $mangaId)
            ->pluck('genre_id');

        $inserts = [];
        foreach ($genreIds as $genreId) {
            if (isset($this->genreMap[$genreId])) {
                $inserts[] = [
                    'comic_id' => $comicId,
                    'category_id' => $this->genreMap[$genreId],
                ];
            }
        }

        if (!empty($inserts)) {
            DB::table('comic_categories')->insert($inserts);
        }
    }

    // =========================================================================
    // PHASE 4: Import Chapters + Images
    // =========================================================================

    private function importChapters(int $chunkSize): void
    {
        $this->info('--- Phase 4: Import chapters + ảnh ---');

        $total = DB::connection('damconuong')->table('chapters')->count();
        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Đang import chapters...');

        DB::connection('damconuong')->table('chapters')
            ->orderBy('created_at')
            ->chunk($chunkSize, function ($chapters) use ($bar) {
                foreach ($chapters as $chapter) {
                    $this->importSingleChapter($chapter);
                    $bar->advance();
                }
            });

        $bar->setMessage('Hoàn tất!');
        $bar->finish();
        $this->newLine();
        $this->info("  Import: {$this->chapterCount} chapters, {$this->imageCount} ảnh.");
        $this->newLine();
    }

    private function importSingleChapter(object $srcChapter): void
    {
        // Bỏ qua nếu manga chưa được import
        if (!isset($this->mangaMap[$srcChapter->manga_id])) return;

        $comicId = $this->mangaMap[$srcChapter->manga_id];
        $chapterNumber = $srcChapter->order;

        // Skip nếu chapter đã tồn tại (cùng comic + server + chapter_number)
        $exists = DB::table('chapters')
            ->where('comic_id', $comicId)
            ->where('server', self::SERVER_NAME)
            ->where('chapter_number', $chapterNumber)
            ->exists();

        if ($exists) return;

        // Lưu chapter
        $newChapterId = DB::table('chapters')->insertGetId([
            'server' => self::SERVER_NAME,
            'comic_id' => $comicId,
            'name' => (string) $chapterNumber,
            'chapter_number' => $chapterNumber,
            'title' => $srcChapter->name,
            'slug' => $srcChapter->slug ?? ('chuong-' . $chapterNumber),
            'views' => $srcChapter->views ?? 0,
            'created_at' => $srcChapter->created_at,
            'updated_at' => $srcChapter->updated_at,
        ]);

        $this->chapterCount++;

        // Parse content → danh sách URL ảnh (phân cách bởi newline)
        $this->importChapterImages($newChapterId, $srcChapter->content);
    }

    /**
     * Tách chuỗi content (URLs cách nhau bởi newline) thành từng record ảnh.
     * Dùng batch insert để tối ưu hiệu suất.
     */
    private function importChapterImages(int $chapterId, ?string $content): void
    {
        if (empty($content)) return;

        // Tách URLs — content có thể dùng \n hoặc \r\n
        $urls = array_filter(
            array_map('trim', preg_split('/\r?\n/', $content)),
            fn($url) => !empty($url)
        );

        if (empty($urls)) return;

        // Batch insert — hiệu quả hơn nhiều so với insert từng dòng
        $inserts = [];
        foreach ($urls as $index => $url) {
            $inserts[] = [
                'chapter_id' => $chapterId,
                'page' => $index + 1,
                'link' => $url,
            ];
        }

        // Insert theo chunk nhỏ để tránh MySQL max_allowed_packet limit
        foreach (array_chunk($inserts, 100) as $batch) {
            DB::table('chapterimgs')->insert($batch);
        }

        $this->imageCount += count($urls);
    }

    // =========================================================================
    // Helper: Load existing mappings (cho --only-chapters)
    // =========================================================================

    /**
     * Khi chạy --only-chapters, comics đã import trước đó.
     * Cần build lại mapping manga UUID → comic ID bằng cách match slug.
     */
    private function loadExistingMappings(): void
    {
        $this->info('Đang load mapping từ DB...');

        // Load genre map
        $genres = DB::connection('damconuong')->table('genres')->get();
        foreach ($genres as $genre) {
            $cat = DB::table('categories')->where('slug', $genre->slug)->first();
            if ($cat) $this->genreMap[$genre->id] = $cat->id;
        }

        // Load artist map
        $artists = DB::connection('damconuong')->table('artists')->get();
        foreach ($artists as $artist) {
            $author = DB::table('authors')->where('slug', $artist->slug)->first();
            if ($author) $this->artistMap[$artist->id] = $author->id;
        }

        // Load manga map — match bằng slug
        DB::connection('damconuong')->table('mangas')
            ->select('id', 'slug')
            ->orderBy('id')
            ->chunk(1000, function ($mangas) {
                $slugs = $mangas->pluck('slug')->toArray();
                $comics = DB::table('comics')
                    ->whereIn('slug', $slugs)
                    ->pluck('id', 'slug');

                foreach ($mangas as $manga) {
                    if (isset($comics[$manga->slug])) {
                        $this->mangaMap[$manga->id] = $comics[$manga->slug];
                    }
                }
            });

        $this->info("  Mapped: " . count($this->mangaMap) . " truyện, "
            . count($this->genreMap) . " thể loại, "
            . count($this->artistMap) . " tác giả.");
        $this->newLine();
    }

    private function printSummary(): void
    {
        $this->newLine();
        $this->info('===================================');
        $this->info('        KẾT QUẢ IMPORT');
        $this->info('===================================');
        $this->info("  Truyện mới:   {$this->comicCount}");
        $this->info("  Chapters mới: {$this->chapterCount}");
        $this->info("  Ảnh mới:      {$this->imageCount}");
        $this->info("  Thể loại:     " . count($this->genreMap));
        $this->info("  Tác giả:      " . count($this->artistMap));
        $this->info('===================================');
    }
}
