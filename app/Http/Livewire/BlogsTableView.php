<?php

namespace App\Http\Livewire;

use LaravelViews\Views\TableView;
use App\Models\Blog;
use Illuminate\Support\HtmlString;
use LaravelViews\Facades\Header;


class BlogsTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $paginationTheme = 'bootstrap';
    public $searchBy = ['title'];
    protected $paginate = 10;

    public function repository()
    {
        return Blog::query()->orderByDesc('created_at');
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
            Header::title('Trạng thái'),
            Header::title('Lần cập nhật gần nhất'),
            Header::title('Hành động'),
        ];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($blog): array
    {
        return [
            new HtmlString('<div class="d-flex px-2 py-1">
                                <div>
                                    <div>
                                        <span style="color: #7c69ef;">' . $blog->title . '</span>
                                    </div>

                                </div>
                            </div>'),
            new HtmlString("<img src='{$blog->image}' alt='Blog Image' loading='lazy' class='avatar me-3' style='width:80px;height:100px'"),
            new HtmlString("<span class='badge badge-sm bg-gradient-success'>" . ucfirst($blog->status) . "</span>"),
            new HtmlString("<span class='badge badge-sm bg-gradient-success'>{$blog->updated_at->diffForHumans()}</span>"),
            new HtmlString('
            <div class="d-flex gap-2">
                <a href="' . route('admin.blog.edit', ['blog' => $blog->id]) . '" style="background: #7c69ef;height:fit-content;" class="text-white btn rounded font-weight-bold">
                    Sửa
                </a>
                <button style="height:fit-content" data-id="' . $blog->id . '" type="button" data-bs-toggle="modal" data-bs-target="#modal-notification" data-title="' . $blog->title . '" class="btn-delete btn btn-danger">
                    Xóa
                </button>
            </div>
            '),
        ];
    }
}
