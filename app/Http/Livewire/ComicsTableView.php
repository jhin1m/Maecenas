<?php

namespace App\Http\Livewire;

use LaravelViews\Views\TableView;
use App\Models\Comic;
use Illuminate\Support\HtmlString;
use LaravelViews\Facades\Header;


class ComicsTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $paginationTheme = 'bootstrap';
    public $searchBy = ['name', 'origin_name'];
    protected $paginate = 10;

    public function repository()
    {
        return Comic::query()->withCount('chapters')->orderByDesc('created_at');
    }

    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): array
    {
        return [
            Header::title('Thông tin'),
            Header::title('Ảnh bìa'),
            Header::title('Thể loại'),
            Header::title('Lần cập nhật gần nhất'),
            Header::title('Lượt xem'),
            Header::title('Hành động'),
        ];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($comic): array
    {
        return [
            new HtmlString('<div class="d-flex px-2 py-1">
                                <div>
                                    <div>
                                        <span style="color: #7c69ef;">' . $comic->name . '</span>
                                    </div>
                                    <div>
                                        <small class="text-gray-500">(' . ($comic->origin_name ?? 'Đang cập nhật') . ')</small>&nbsp;
                                        <small style="color: rgb(239 68 68/1)">[' . $comic->chapters_count . ' chap]</small> -
                                        ' . ($comic->chapters_count > 0 ? '<small style="color: rgb(239 68 68/1)">[Chapter ' . $comic->chapters->first()->name . ']</small>&nbsp;' : '') . '
                                    </div>
                                    <div class="text-[75%] text-white gap-2" style="display: inline-flex">
                                        <div style="background: #7c69ef" class="bg-[#7c69ef] rounded px-1">Truyện tranh</div>
                                        <div class="bg-success rounded px-1">' . $comic->status . '</div>
                                    </div>
                                </div>
                            </div>'),
            new HtmlString("<img src='{$comic->thumbnail}' alt='Comic Thumbnail' loading='lazy' class='avatar me-3' style='width:80px;height:100px'"),
            new HtmlString('<p class="text-xs font-weight-bold mb-0 text-center d-flex flex-wrap gap-2">' .
                                ($comic->categories->count() > 0 ?
                                    $comic->categories->map(function($category) {
                                        return '<span class="badge bg-gradient-dark">' . $category->name . '</span>';
                                    })->implode('')
                                : '') .
                            '</p>'),
            new HtmlString("<span class='badge badge-sm bg-gradient-success'>{$comic->updated_at->diffForHumans()}</span>"),
            new HtmlString("<span class='text-secondary text-xs font-weight-bold'>{$comic->total_views}</span>"),
            new HtmlString('
            <div class="d-flex gap-2">
                <a target="_blank" href="' . route('detail', ['slug' => $comic->slug]) . '" class="rounded font-weight-bold bg-success btn text-white" style="height:fit-content">
                    Xem
                </a>
                <a style="height:fit-content;overflow:hidden;white-space: nowrap;" href="' . route('admin.chapterComic.index', ['comic_id' => $comic->id]) . '" class="btn btn-warning">Danh sách chương</a>

                <a href="' . route('admin.comic.edit', ['comic' => $comic->id]) . '" style="background: #7c69ef;height:fit-content;" class="text-white btn rounded font-weight-bold">
                    Sửa
                </a>
                <button style="height:fit-content" data-id="' . $comic->id . '" type="button" data-bs-toggle="modal" data-bs-target="#modal-notification" data-name="' . $comic->name . '" class="btn-delete btn btn-danger">
                    Xóa
                </button>
            </div>
            '),
        ];
    }
}
