<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Helper\SeoHelper;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Str;

class Comic extends Model
{
    use HasFactory;

    protected $table = 'comics';

    protected $fillable = [
        'name',
        'origin_name',
        'slug',
        'content',
        'status',
        'thumbnail',
        'created_at',
        'updated_at',
        'is_hot',
        'total_views',
        'rating',
        'total_votes',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'comic_categories', 'comic_id', 'category_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)
                ->selectRaw('MAX(id) as id, name, MAX(title) as title, slug, MAX(price) as price, comic_id, chapter_number, MAX(server) as server, MAX(created_at) as created_at, MAX(updated_at) as updated_at')
                ->groupBy('chapter_number', 'name', 'slug', 'comic_id')
                ->orderBy('chapter_number');
    }

    public function getUrl(){
        return route('detail', ['slug' => $this->slug]);
    }

    public function generateSeoTags(){
        $thumb = $this->thumbnail;
        $seoHelper = new SeoHelper();
        $replace = [
            'comic->name' => $this->name,
            'comic->origin_name' => $this->origin_name,
            'comic->status' => $this->status
        ];
        $meta_home = $seoHelper->getSeoSettings('general', ['site_name', 'author']);
        $meta = $seoHelper->getSeoSettings('comic', ['title_detail_comic', 'description_detail_comic', 'keywords_detail_comic'], $replace);
        $title = $meta['title_detail_comic'];
        $description = $meta['description_detail_comic'] ?? Str::limit(strip_tags($this->content), 150, '...');
        $getUrl = $this->getUrl();
        $site_name = $meta_home['site_name'];
        $keyword = $meta['keywords_detail_comic'];
        SEOMeta::setTitle($title, false)
            ->setDescription($description)
            ->addKeyword($keyword)
            ->addMeta('article:published_time', $this->updated_at->toW3CString(), 'property')
            ->addMeta('article:section', $this->categories->pluck('name')->join(","), 'property')
            ->setCanonical($getUrl)
            ->setPrev(url('/'))
            ->setPrev(url('/'));

        OpenGraph::setType('article')
            ->setTitle($title, false)
            ->setDescription($description)
            ->setSiteName($site_name)
            ->addProperty('locale', 'vi-VN')
            ->addProperty('updated_time', $this->updated_at)
            ->addProperty('url', $getUrl)
            ->addImages([$this->thumbnail])
            ->setArticle([
                'authors' => $this->authors->pluck('name')->join(","),
                'release_date' => $this->created_at,
            ]);

        TwitterCard::setSite($site_name)
            ->setTitle($title, false)
            ->setType('summary')
            ->setImage($this->thumbnail)
            ->setDescription($description)
            ->setUrl($getUrl);

        JsonLdMulti::newJsonLd()
            ->setSite($site_name)
            ->addValue('dateCreated', $this->created_at)
            ->addValue('dateModified', $this->updated_at)
            ->addValue('datePublished', $this->created_at)
            ->setTitle($title, false)
            ->setType('Comic')
            ->setDescription($description)
            ->setImages([$this->thumbnail])
            ->addValue('author', count($this->authors) ? $this->authors->map(function ($author) {
                return ['@type' => 'Person', 'name' => $author->name];
            }) : "")
            ->setUrl($getUrl);

        $breadcrumb = [];
        array_push($breadcrumb, [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => url('/')
        ]);

        foreach ($this->categories as $item) {
            array_push($breadcrumb, [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $item->name,
                'item' => $item->getUrl(),
            ]);
        }

        array_push($breadcrumb, [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $this->name
        ]);

        JsonLdMulti::newJsonLd()
            ->setType('BreadcrumbList')
            ->addValue('name', '')
            ->addValue('description', '')
            ->addValue('itemListElement', $breadcrumb);
    }

    public function lastChapter()
    {
        return $this->hasOne(Chapter::class)->orderByDesc('chapter_number');
    }

    public function author()
    {
        return $this->belongsToMany(Author::class, 'author_comic', 'id_comic', 'id_author');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'comic_id', 'id');
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'comic_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'comic_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'comic_id', 'id')->with('user')->orderBy('created_at', 'desc');
    }
}
