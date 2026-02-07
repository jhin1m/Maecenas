# Crawl System Scout Report
**Date:** 2026-02-06 | **Time:** 17:21  
**Project:** truyenv11 (Vietnamese Manga/Comic Platform)

---

## Executive Summary

The project implements a **two-tier crawling system** for importing comics from OTruyen API:

1. **CLI Command** (`CrawlOTruyen`): Batch crawl via terminal
2. **Web UI** (CrawlController + Blade view): Interactive multi-step crawl via admin panel

Both systems support **VIP #1, VIP #2, and DamCoNuong** servers, with image storage in `chapterimgs` table.

---

## 1. Crawl Command (CLI)

**File:** `/Users/jhin1m/Desktop/ducanh-project/truyenv11/app/Console/Commands/CrawlOTruyen.php`

### Usage
```bash
php artisan crawl:otruyen {page} {topage}
# Example: php artisan crawl:otruyen 1 10
```

### How It Works

#### Step 1: Fetch Comic List (page by page)
```php
for($i = $page; $i <= $topage; $i++){
    $url = "https://otruyenapi.com/v1/api/danh-sach?page=$i";
    // Response: { data: { items: [...] } }
    // Each item has: slug, name, origin_name, status, content, chapters, etc.
}
```

#### Step 2: Check/Create Comic Record
- **Check:** Query `comics` table by slug
- **If exists:** Skip comic creation, set `$idComic`, fetch chapters in **reverse** order (oldest first)
- **If new:** Create Comic with:
  - `name`, `origin_name` (imploded array), `slug`, `content`, `status`
  - `thumbnail` (from SEO schema: `body['data']['seoOnPage']['seoSchema']['image']`)

#### Step 3: Sync Categories
```php
// For each category in OTruyen response
$category['slug'] => lookup/create in `categories` table
-> link via `comic_categories` pivot (comic_id, category_id)
```

#### Step 4: Sync Author
```php
// Single author from OTruyen (imploded if multiple)
$author['name'] => lookup/create in `authors` table
-> link via `author_comic` pivot (id_comic, id_author)
```

#### Step 5: Crawl Chapters (if not already present)
```php
// For each chapter in chapters[0].server_data
$checkChapter = Chapter::where('name', $chapter['chapter_name'])
                       ->where('server', 'VIP #1')
                       ->where('comic_id', $idComic)
                       ->first();

if (!$checkChapter) {
    // Fetch chapter images from $chapter['chapter_api_data']
    // Response: { data: { 
    //   domain_cdn: "...",
    //   item: { 
    //     chapter_path: "...",
    //     chapter_image: [ {image_page, image_file}, ... ]
    //   }
    // }}
    
    // Build image URL: $domain_cdn / $chapter_path / $image_file
    // Store in chapterimgs: (chapter_id, page, link)
}
```

### Key Details

| Aspect | Value |
|--------|-------|
| API Endpoint | `env('LINK_OTRUYEN_API')` = `https://otruyenapi.com/v1/api/truyen-tranh/` |
| Server Name | Hardcoded: `VIP #1` |
| List API | `https://otruyenapi.com/v1/api/danh-sach?page={page}` |
| Rate Limiting | `sleep(1)` between chapter fetches |
| Error Handling | Try-catch with `$this->error()` logging |

---

## 2. Web UI Controller (Admin Panel)

**File:** `/Users/jhin1m/Desktop/ducanh-project/truyenv11/app/Http/Controllers/Admin/CrawlController.php`

### Two Main Methods

#### `addComicByCrawl(Request $request)`
**Called by JS:** Step 3 in crawl.blade.php (line 263)

**Payload:**
```json
{
  "name": "...",
  "slug": "...",
  "origin_name": "...",
  "status": "...",
  "content": "...",
  "thumbnail": "...",
  "categories": [...],
  "author": "...",
  "views": 0,
  "serverName": "VIP #1" | "VIP #2" | "DamCoNuong"
}
```

**Logic:**
1. Check if comic exists by slug → return existing ID + currentChapter
2. Create new Comic record
3. Sync categories (handles VIP #1/DamCoNuong vs VIP #2 category lookup differently)
4. Add author (only for VIP #1/DamCoNuong)

**Database Operations:** Inserts to `comics`, `comic_categories`, `authors`, `author_comic`

---

#### `addChapterByCrawl(Request $request)`
**Called by JS:** Chapter loop in crawl.blade.php (line 298)

**Supports 3 Servers:**

##### VIP #1 (OTruyen)
```json
{
  "chapter": {
    "chapter_name": "1",
    "chapter_title": "Chương 1",
    "chapter_api_data": "https://otruyenapi.com/v1/api/..."
  },
  "id": comic_id,
  "serverName": "VIP #1"
}
```
- Fetch images from `chapter['chapter_api_data']`
- Parse `domain_cdn` + `chapter_path` + `image_file`
- Store in `chapterimgs`

##### VIP #2 (NComic)
```json
{
  "chapter": {
    "name": "Chapter 1.5",
    "id": "chapter-id"
  },
  "id": comic_id,
  "serverName": "VIP #2"
}
```
- Extract chapter number with regex: `/\d+(\.\d+)?/`
- Fetch from `env('LINK_NCOMIC_API') + "comics/{slug}/chapters/{id}"`
- Response format: `{ images: [{page, src}, ...] }`

##### DamCoNuong
```json
{
  "chapter_data": {
    "chapter_number": "1",
    "name": "Chapter 1",
    "views": 100
  },
  "manga_slug": "slug",
  "chapter_slug": "chapter-slug",
  "baseUrl": "api.base.url",
  "id": comic_id,
  "serverName": "DamCoNuong"
}
```
- Fetch from `{baseUrl}/mangas/{manga_slug}/chapters/{chapter_slug}/images`
- Response: `{ data: { images: ["url1", "url2", ...] } }`

---

## 3. Database Schema

### Comics Table
```sql
CREATE TABLE comics (
  id                BIGINT PRIMARY KEY,
  name              VARCHAR(255) UNIQUE NOT NULL,
  origin_name       VARCHAR(255) NULLABLE,
  slug              VARCHAR(255) UNIQUE NOT NULL,
  status            VARCHAR(255) NULLABLE,
  content           LONGTEXT NULLABLE,
  thumbnail         VARCHAR(255) NULLABLE,
  rating            DECIMAL(10, 1) DEFAULT 0,
  total_votes       BIGINT DEFAULT 0,
  view_total        BIGINT DEFAULT 0,
  view_day          BIGINT DEFAULT 0,
  view_week         BIGINT DEFAULT 0,
  view_month        BIGINT DEFAULT 0,
  upview_at         TIMESTAMP NULLABLE,
  is_hot            BOOLEAN (from migration 2024_08_07),
  created_at        TIMESTAMP,
  updated_at        TIMESTAMP
);
```

**Relationships:**
- `chapters()` → hasMany Chapter (grouped by chapter_number, multiple servers)
- `categories()` → belongsToMany Category (pivot: comic_categories)
- `author()` → belongsToMany Author (pivot: author_comic)
- `comments()`, `votes()`, `follows()`, `histories()`

---

### Chapters Table
```sql
CREATE TABLE chapters (
  id                BIGINT PRIMARY KEY,
  server            VARCHAR(255) DEFAULT 'Server#1',
  name              VARCHAR(255) NOT NULL,
  chapter_number    FLOAT NOT NULL,
  views             BIGINT DEFAULT 0,
  slug              VARCHAR(255) NOT NULL,
  title             VARCHAR(255) NULLABLE,
  has_report        INT DEFAULT 0,
  report_message    LONGTEXT NULLABLE,
  comic_id          BIGINT FOREIGN KEY,
  fee               ENUM('true', 'false') DEFAULT 'false',
  price             INT DEFAULT 500,
  content           LONGTEXT NULLABLE,
  created_at        TIMESTAMP,
  updated_at        TIMESTAMP
);
```

**Relationships:**
- `images()` → hasMany Image (alias for ChapterImg) ordered by page
- `comic()` → belongsTo Comic

**Key Index:** Unique constraint on (comic_id, server, chapter_number) implicitly via duplicate checks

---

### ChapterImgs (Images) Table
```sql
CREATE TABLE chapterimgs (
  id                BIGINT PRIMARY KEY,
  chapter_id        BIGINT FOREIGN KEY (chapters),
  link              LONGTEXT NOT NULL,
  page              INT DEFAULT 0
);
```

**No timestamps.** Links are external URLs or local paths.

---

### Pivot Tables

#### comic_categories
```sql
CREATE TABLE comic_categories (
  comic_id          BIGINT FOREIGN KEY,
  category_id       BIGINT FOREIGN KEY
);
```

#### author_comic
```sql
CREATE TABLE author_comic (
  id_comic          BIGINT FOREIGN KEY (comics),
  id_author         BIGINT FOREIGN KEY (authors)
);
```

---

## 4. OTruyen API Response Format

### List Endpoint
**URL:** `https://otruyenapi.com/v1/api/danh-sach?page=1`

```json
{
  "status": 200,
  "data": {
    "items": [
      {
        "id": "...",
        "name": "Comic Name",
        "slug": "comic-slug",
        "origin_name": ["Original Name"],
        "author": ["Author Name"],
        "category": [
          {
            "id": "...",
            "name": "Action",
            "slug": "action"
          }
        ],
        "chaptersLatest": [
          {
            "chapter_name": "100",
            "chapter_title": "Chương 100"
          }
        ]
      }
    ]
  }
}
```

### Comic Detail Endpoint
**URL:** `https://otruyenapi.com/v1/api/truyen-tranh/{slug}`

```json
{
  "status": 200,
  "data": {
    "item": {
      "name": "Comic Name",
      "slug": "comic-slug",
      "origin_name": ["Original Name"],
      "author": ["Author Name"],
      "content": "Description...",
      "status": "Ongoing",
      "category": [
        {
          "id": "...",
          "name": "Action",
          "slug": "action"
        }
      ],
      "chapters": [
        {
          "server_name": "VIP #1",
          "server_data": [
            {
              "chapter_name": "1",
              "chapter_title": "Chương 1",
              "chapter_api_data": "https://otruyenapi.com/v1/api/chapter/..."
            }
          ]
        }
      ]
    },
    "seoOnPage": {
      "seoSchema": {
        "image": "https://..."
      }
    }
  }
}
```

### Chapter Detail Endpoint
**URL:** From `chapter['chapter_api_data']`

```json
{
  "status": 200,
  "data": {
    "domain_cdn": "https://cdn.example.com",
    "item": {
      "chapter_path": "comic-slug/chuong-1",
      "chapter_image": [
        {
          "image_page": 1,
          "image_file": "001.jpg"
        },
        {
          "image_page": 2,
          "image_file": "002.jpg"
        }
      ]
    }
  }
}
```

**Final Image URL:** `{domain_cdn}/{chapter_path}/{image_file}`

---

## 5. Web UI Flow (Blade + JS)

**File:** `/Users/jhin1m/Desktop/ducanh-project/truyenv11/resources/views/pages/crawl.blade.php`

### 3-Step Process

#### Step 1: Load Comics
- Input: API link(s) or homepage pagination
- Two modes:
  1. **Homepage list:** `https://otruyenapi.com/v1/api/danh-sach?page={from}` to `{to}`
  2. **Multi-link:** Multiple direct comic URLs (newline-separated)
- Filters: Exclude comics with last chapter > input threshold
- Output: Checkbox list of comics to crawl

#### Step 2: Select Comics
- Show all fetched comics
- Allow select/deselect (stored in `sessionStorage`)
- "Select All" toggle

#### Step 3: Crawl & Monitor
- For each selected comic:
  1. POST to `/admin/comics/v1/addComicByCrawl`
  2. Loop chapters (reversed), POST each to `/admin/comics/v1/addChapterByCrawl`
  3. **1s delay** between chapter requests
- Real-time progress display

---

## 6. Environment Variables

**File:** `/Users/jhin1m/Desktop/ducanh-project/truyenv11/.env`

```env
# Primary crawl API
LINK_OTRUYEN_API="https://otruyenapi.com/v1/api/truyen-tranh/"

# (Optional, referenced in CrawlController but not in .env)
# LINK_NCOMIC_API="https://api.ncomic.com/v1/"

# Cache TTL for home page
TIME_CACHE=3600
```

---

## 7. Models Overview

### Comic Model
```php
class Comic extends Model {
  protected $fillable = [
    'name', 'origin_name', 'slug', 'content', 'status', 
    'thumbnail', 'created_at', 'updated_at', 'is_hot', 
    'total_views', 'rating', 'total_votes'
  ];
  
  public function chapters() { /* grouped by chapter_number */ }
  public function categories() { /* belongsToMany */ }
  public function author() { /* belongsToMany */ }
  public function generateSeoTags() { /* SEO structured data */ }
}
```

### Chapter Model
```php
class Chapter extends Model {
  protected $fillable = [
    'name', 'server', 'comic_id', 'slug', 'chapter_number',
    'title', 'content', 'price'
  ];
  
  public function images() { /* hasMany Image, ordered by page */ }
  public function comic() { /* belongsTo Comic */ }
}
```

### ChapterImg / Image Model
```php
class ChapterImg extends Model {
  protected $table = 'chapterimgs';
  protected $fillable = ['link', 'page'];
  public function chapter() { /* belongsTo Chapter */ }
}

// Alias
class Image extends Model {
  protected $table = 'chapterimgs';
  protected $fillable = ['page', 'link', 'chapter_id'];
}
```

---

## 8. Routing

**File:** `/Users/jhin1m/Desktop/ducanh-project/truyenv11/routes/web.php`

```php
// Admin routes (protected by checkLogin + checkAdmin)
Route::prefix('admin')->middleware(['checkLogin', 'checkAdmin'])->group(function () {
  Route::get("api", [HomeController::class, "showApi"])->name('admin.api');
  Route::post("comics/v1/addChapterByCrawl", [CrawlController::class, 'addChapterByCrawl'])
    ->name('addChapterByCrawl');
  Route::post("comics/v1/addComicByCrawl", [CrawlController::class, 'addComicByCrawl'])
    ->name('addComicByCrawl');
});
```

---

## 9. Image Storage & Handling

### Current Approach
- **Chapter images:** External URLs stored in `chapterimgs.link` (no local storage in crawl)
- **Thumbnails:** Optionally downloaded via `saveImageToHost()` helper (not used in main crawl)

### URL Construction
```php
// OTruyen VIP #1
$url = "{domain_cdn}/{chapter_path}/{image_file}"
// Example: https://cdn.otruyencdn.com/comic-slug/chuong-1/001.jpg

// DamCoNuong
$url = "{image_url}"  // Directly from API response
// Example: https://api.damconuong.com/images/...
```

---

## 10. Error Handling & Rate Limiting

### In CLI Command
```php
try {
  // Crawl logic
} catch (\Exception $e) {
  $this->error($e->getMessage());
  // Continues to next comic/chapter
}
```

### In Web Controller
```php
try {
  // Chapter fetch
} catch (\Exception $e) {
  return response()->json("Lỗi khi lấy ảnh chương: " . $e->getMessage(), 500);
}
```

### Rate Limiting
- **Between chapter fetches:** `sleep(1)` (CLI only)
- **Between requests in web UI:** `await new Promise(resolve => setTimeout(resolve, 1000))`

---

## 11. Key File Locations

| File | Purpose |
|------|---------|
| `/app/Console/Commands/CrawlOTruyen.php` | CLI batch crawl command |
| `/app/Http/Controllers/Admin/CrawlController.php` | Web UI crawl endpoints |
| `/resources/views/pages/crawl.blade.php` | Admin crawl UI + JS logic |
| `/app/Models/Comic.php` | Comic model + SEO generation |
| `/app/Models/Chapter.php` | Chapter model |
| `/app/Models/ChapterImg.php` | Chapter image model |
| `/database/migrations/2024_03_12_044102_create_comics_table.php` | Comics schema |
| `/database/migrations/2024_03_12_045730_create_chapters_table.php` | Chapters schema |
| `/database/migrations/2024_03_12_051959_create_chapterimgs_table.php` | Images schema |
| `/routes/web.php` | Crawl route definitions |
| `/.env` | API configuration |

---

## 12. Notable Implementation Details

1. **Chapter Grouping:** `chapters()` query groups by `chapter_number` & `name`, taking MAX of other fields (handles multi-server duplicates)

2. **Author Storage:** Single author string per comic, concatenated from array

3. **Category Lookup:** Different logic for VIP #1 (by slug) vs VIP #2 (by name)

4. **Session Storage:** Web UI uses `sessionStorage.ListComics` to maintain selected comics across steps

5. **Duplicate Prevention:** Before crawling chapter, checks if it already exists by (comic_id, server, name)

6. **Updated At:** Comic's `updated_at` refreshed after each chapter crawl (line 151-153 in CrawlOTruyen.php)

7. **SEO Integration:** Comic model has `generateSeoTags()` using `artesaos/seotools` package

---

## Unresolved Questions

1. Is `LINK_NCOMIC_API` environment variable documented? Not found in `.env` or config
2. Does the crawl system handle image CDN timeouts gracefully or does entire chapter fail?
3. Is there a max concurrent request limit or should we batch differently for large crawls?
4. Should chapter image links be validated/cached for availability before storing in DB?

