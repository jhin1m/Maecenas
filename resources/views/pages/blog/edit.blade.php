@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" rel="stylesheet">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sửa Bài Viết'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>{{$blog->title}}</h3>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <form id="form-edit" class="bg-white p-3 rounded-3">
                                <div class="row">
                                    <div class="col-xxl-9">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control" value="{{$blog->title}}" placeholder="Nhập tiêu đề bài viết" id="title" name="title">
                                        </div>
                                        <div class="mb-3">
                                            <label for="slug" class="form-label">Đường dẫn tĩnh:</label>
                                            <input type="text" class="form-control" value="{{$blog->slug}}" placeholder="Nhập đường dẫn tĩnh của bài viết" id="slug" name="slug">
                                        </div>
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Nội dung</label>
                                            <div id="content" style="height: fit-content;">
                                                {!! $blog->content !!}
                                            </div>
                                        </div>
                                        <div class="mb-3 d-flex gap-3">
                                            <div class="w-100">
                                                <label for="status" class="form-label">Trạng thái</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option {{$blog->status == "draft" ? 'selected' : ''}} value="draft">Draft</option>
                                                    <option {{$blog->status == "published" ? 'selected' : ''}} value="published">Published</option>
                                                    <option {{$blog->status == "archived" ? 'selected' : ''}} value="archived">Archived</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_hot" id="trueRecommend"  @if($blog->is_hot) checked @endif>
                                            <label class="form-check-label" for="trueRecommend">
                                              Hot
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_hot" id="falseRecommend" @if(!$blog->is_hot) checked @endif>
                                            <label class="form-check-label" for="falseRecommend">
                                                Không hot
                                            </label>
                                        </div>
                                        <div class="mb-3 form-urlThumb">
                                            <label for="imageUrl" class="form-label">URL Ảnh thumbnail</label>
                                            <input class="form-control" value="{{$blog->image}}" placeholder="Nhập đường dẫn ảnh" type="text" id="imageUrl" name="imageUrl">
                                        </div>
                                        <div class="d-flex justify-content-center mb-4">
                                            <div class="preview-img">
                                                <img class="w-100" src="{{$blog->image}}" alt="preview" />
                                            </div>
                                        </div>
                                        <div class="mb-4 d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.blog.index')}}" class="btn btn-secondary p-3">Quay lại</a>
                                            <button data-id="{{$blog->id}}" class="btn btn-submit-create btn-primary p-3" type="submit">Lưu</button>
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
        const quill = new Quill('#content', {
            theme: 'snow'
        });

        $('#imageUrl').on('input', function() {
            $('.preview-img').html('<img class="w-100" src="' + this.value + '" alt="preview" />');
        });
        $('#form-edit').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('admin.blog.update', ["blog" => $blog->id]) }}',
                type: 'PUT',
                data: {
                    id: {{$blog->id}},
                    title: $('#title').val(),
                    slug: $('#slug').val(),
                    content: quill.root.innerHTML,
                    status: $('#status').val(),
                    image: $('#imageUrl').val(),
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
