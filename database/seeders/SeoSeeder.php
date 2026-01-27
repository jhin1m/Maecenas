<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seos = [
            [
                'key' => 'title',
                'name' => 'Tiêu đề',
                'value' => 'NQTComics đọc truyện miễn phí',
                'group' => 'general'
            ],
            [
                'key' => 'site_name',
                'name' => 'Meta Site name',
                'value' => 'NQTComics',
                'group' => 'general'
            ],
            [
                'key' => 'keywords',
                'name' => 'Meta keyword',
                'value' => 'nqtcomcis',
                'group' => 'general'
            ],
            [
                'key' => 'shortcut',
                'name' => 'Meta shortcut icon',
                'value' => '/favicon.ico',
                'group' => 'general'
            ],
            [
                'key' => 'description',
                'name' => 'Meta description',
                'value' => 'NQTComics đọc truyện miễn phí',
                'group' => 'general'
            ],
            [
                'key' => 'meta_image',
                'name' => 'Meta image',
                'value' => '/logo.png',
                'group' => 'general'
            ],
            [
                'key' => 'head_meta',
                'name' => 'Head meta tags',
                'value' => '<meta name="robots" content="index,follow" />
<meta name="revisit-after" content="1 days" />
<meta name="ROBOTS" content="index,follow" />
<meta name="googlebot" content="index,follow" />
<meta name="BingBOT" content="index,follow" />
<meta name="yahooBOT" content="index,follow" />
<meta name="slurp" content="index,follow" />
<meta name="msnbot" content="index,follow" />',
                'group' => 'general'
            ],
            [
                'key' => 'title_detail_comic',
                'name' => 'Mẫu tiêu đề trang thông tin truyện',
                'value' => 'Truyện {{$comic->name}}',
                'group' => 'comic',
                'description' => 'Thông tin truyện: {{$comic->name}}|{{$comic->origin_name}}|{{$comic->status}}'
            ],
            [
                'key' => 'title_read_comic',
                'name' => 'Mẫu tiêu đề trang xem đọc truyện',
                'value' => 'Đọc truyện {{$comic->name}} chương {{$chapterSelected->name}}',
                'group' => 'comic',
                'description' => 'Thông tin truyện: {{$comic->name}}|{{$comic->origin_name}}|{{$comic->status}}|{{$chapterSelected->name}}'
            ],
            [
                'key' => 'title_category',
                'name' => 'Tiêu đề thể loại mặc định',
                'value' => 'Danh sách truyện {{$category->name}} - tổng hợp truyện {{$category->name}} mới nhất',
                'group' => 'category',
                'description' => 'Thông tin: {{$category->name}}'
            ],
            [
                'key' => 'description_category',
                'name' => 'Mô tả thể loại mặc định',
                'value' => 'Danh sách truyện {{$category->name}} - tổng hợp truyện {{$category->name}} mới nhất',
                'group' => 'category',
                'description' => 'Thông tin: {{$category->name}}'
            ],
            [
                'key' => 'keywords_category',
                'name' => 'Từ khóa thể loại mặc định',
                'value' => 'Danh sách truyện {{$category->name}},truyện {{$category->name}}, truyen hay, nettruyen',
                'group' => 'category',
                'description' => 'Thông tin: {{$category->name}}|{{$category->slug}}'
            ],
            [
                'key' => 'title_author',
                'name' => 'Mẫu tiêu đề trang tác giả',
                'value' => 'Tác giả {{$author->name}}',
                'group' => 'author',
                'description' => 'Thông tin: {{$author->name}}'
            ],
            [
                'key' => 'description_author',
                'name' => 'Mô tả trang tác giả',
                'value' => 'Tác giả {{$author->name}} - Danh sách truyện của tác giả {{$author->name}}',
                'group' => 'author',
                'description' => 'Thông tin: {{$author->name}}'
            ],
            [
                'key' => 'keywords_author',
                'name' => 'Từ khóa trang tác giả',
                'value' => 'Tác giả {{$author->name}}, truyện của tác giả {{$author->name}}, truyen hay, nettruyen',
                'group' => 'author',
                'description' => 'Thông tin: {{$author->name}}|{{$author->slug}}'
            ],
        ];

        foreach ($seos as $seo) {
            DB::table('seo')->insert($seo);
        }
    }
}
