@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" rel="stylesheet">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sửa Truyện Chữ'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>{{$comic->name}}</h3>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <form id="form-edit" class="bg-white p-3 rounded-3">
                                <div class="row">
                                    <div class="col-xxl-9">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên truyện</label>
                                            <input type="text" class="form-control" value="{{$comic->name}}" placeholder="Nhập tên truyện" id="name" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="origin_name" class="form-label">Tên khác</label>
                                            <input type="text" class="form-control" value="{{$comic->origin_name}}" placeholder="Nhập tên khác của truyện" id="origin_name" name="origin_name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">Đường dẫn tĩnh:</label>
                                            <input type="text" class="form-control" value="{{$comic->slug}}" placeholder="Nhập tên khác của truyện" id="slug" name="slug">
                                        </div>
                                        <div class="mb-3">
                                            <label for="comicDescription" class="form-label">Mô tả</label>
                                            <div id="comicDescription" style="height: fit-content;">
                                                {!! $comic->content !!}
                                            </div>
                                        </div>
                                        <div class="mb-3 d-flex gap-3">
                                            <div class="w-100">
                                                <label for="status" class="form-label">Trạng thái</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option {{$comic->status == "Đang ra" ? 'selected' : ''}} value="Đang ra">Đang ra</option>
                                                    <option {{$comic->status == "Full" ? 'selected' : ''}} value="Full">Full</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categories" class="form-label">Thể loại</label>
                                            <select multiple class="form-control js-choice" name="categories" id="categories">
                                                <option value="">Chọn thể loại</option>
                                                @foreach($categories as $category)
                                                    <option {{ in_array($category->id, $comic->categories->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="authors" class="form-label">Tác giả</label>
                                            <select multiple class="form-control js-choice" name="authors" id="authors">
                                                <option value="">Chọn tác giả</option>
                                                @foreach($authors as $author)
                                                    <option {{ in_array($author->id, $comic->author->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $author->id }}">{{ $author->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_hot" id="trueRecommend"  @if($comic->is_hot) checked @endif>
                                            <label class="form-check-label" for="trueRecommend">
                                              Hot
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_hot" id="falseRecommend" @if(!$comic->is_hot) checked @endif>
                                            <label class="form-check-label" for="falseRecommend">
                                                Không hot
                                            </label>
                                        </div>
                                        <div class="mb-3 form-urlThumb">
                                            <label for="thumbnailUrl" class="form-label">URL Ảnh thumbnail</label>
                                            <input class="form-control" value="{{$comic->thumbnail}}" placeholder="Nhập đường dẫn ảnh" type="text" id="thumbnailUrl" name="thumbnailUrl">
                                        </div>
                                        <div class="d-flex justify-content-center mb-4">
                                            <div class="preview-img">
                                                <img class="w-100" src="{{$comic->thumbnail}}" alt="preview" />
                                            </div>
                                        </div>
                                        <div class="mb-4 d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.comic.index')}}" class="btn btn-secondary p-3">Quay lại</a>
                                            <button data-id="{{$comic->id}}" class="btn btn-submit-create btn-primary p-3" type="submit">Lưu</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js"></script>
    <script>
        const quill = new Quill('#comicDescription', {
            theme: 'snow'
        });
        const element = document.getElementById('categories');
        const element1 = document.getElementById('authors');
        const choices = new Choices(element, {
            removeItemButton: true,
            searchResultLimit: 5,
            position: 'button',
            searchPlaceholderValue: 'Tìm kiếm'
        });
        const choices1 = new Choices(element1, {
            removeItemButton: true,
            searchResultLimit: 5,
            position: 'button',
            searchPlaceholderValue: 'Tìm kiếm'
        });
        $('#thumbnailUrl').on('input', function() {
            $('.preview-img').html('<img class="w-100" src="' + this.value + '" alt="preview" />');
        });
        $('#form-edit').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('admin.comic.update', ["comic" => $comic->id]) }}',
                type: 'PUT',
                data: {
                    id: {{$comic->id}},
                    name: $('#name').val(),
                    origin_name: $('#origin_name').val(),
                    slug: $('#slug').val(),
                    content: quill.root.innerHTML,
                    status: $('#status').val(),
                    thumbnail: $('#thumbnailUrl').val(),
                    categories: JSON.stringify($('#categories').val()),
                    authors: JSON.stringify($('#authors').val()),
                    is_hot: $('input[name="is_hot"]:checked').attr('id') == 'trueRecommend' ? true : false,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == "success") {
                        alert(response.message);
                        window.location.reload();
                    }
                }, error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            });
        });
    </script>
@endpush
