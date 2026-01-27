@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css" rel="stylesheet">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Thêm Mới Truyện Tranh'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>Thêm mới truyện tranh</h3>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <form id="form-create" class="bg-white p-3 rounded-3">
                                <div class="row">
                                    <div class="col-xxl-9">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên truyện</label>
                                            <input type="text" class="form-control" placeholder="Nhập tên truyện" id="name" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="origin_name" class="form-label">Tên khác</label>
                                            <input type="text" class="form-control" placeholder="Nhập tên khác của truyện" id="origin_name" name="origin_name">
                                        </div>

                                        <div class="mb-3">
                                            <label for="comicDescription" class="form-label">Mô tả</label>
                                            <div id="comicDescription" style="height: fit-content;">
                                            </div>
                                        </div>
                                        <div class="mb-3 d-flex gap-3">
                                            <div class="w-100">
                                                <label for="status" class="form-label">Trạng thái</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option value="Đang ra">Đang ra</option>
                                                    <option value="Full">Full</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categories" class="form-label">Thể loại</label>
                                            <select multiple class="form-control js-choice" name="categories" id="categories">
                                                <option value="">Chọn thể loại</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="authors" class="form-label">Tác giả</label>
                                            <select multiple class="form-control js-choice" name="authors" id="authors">
                                                <option value="">Chọn tác giả</option>
                                                @foreach($authors as $author)
                                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3">
                                        <div class="mb-3 form-fileThumb">
                                            <label for="thumbnail" class="form-label">Ảnh thumbnail</label>
                                            <input class="form-control" type="file" accept="image/*" id="thumbnail" name="thumbnail">
                                        </div>
                                        <div class="mb-3 form-urlThumb d-none">
                                            <label for="thumbnailUrl" class="form-label">URL Ảnh thumbnail</label>
                                            <input class="form-control" placeholder="Nhập đường dẫn ảnh" type="text" id="thumbnailUrl" name="thumbnailUrl">
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input thumbnailOption" type="radio" name="thumbnailOption" id="thumbnailOption1" value="file" checked>
                                                <label class="form-check-label" for="thumbnailOption1">
                                                    Tải ảnh lên từ máy
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input thumbnailOption" type="radio" name="thumbnailOption" id="thumbnailOption2" value="url">
                                                <label class="form-check-label" for="thumbnailOption2">
                                                    Sử dụng URL ảnh
                                                </label>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mb-4">
                                            <div class="preview-img">
                                                Xem trước ảnh
                                            </div>
                                        </div>
                                        <div class="mb-4 d-flex justify-content-center">
                                            <button class="btn btn-submit-create btn-primary p-3" type="submit">Thêm mới</button>
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
        $('#thumbnail').on('change', function() {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.preview-img').html('<img class="w-100" src="' + e.target.result + '" alt="preview" />');
            }
            reader.readAsDataURL(this.files[0]);
        });
        $('#thumbnailUrl').on('input', function() {
            $('.preview-img').html('<img class="w-100" src="' + this.value + '" alt="preview" />');
        });
        $('.thumbnailOption').on('change', function() {
            $('#thumbnailUrl').val('');
            $('#thumbnail').val('');
            $('.preview-img').html("Xem trước ảnh");
            if ($('#thumbnailOption2').is(':checked')) {
                $('.form-urlThumb').removeClass('d-none');
                $('.form-fileThumb').addClass('d-none');
            } else if ($('#thumbnailOption1').is(':checked')) {
                $('.form-urlThumb').addClass('d-none');
                $('.form-fileThumb').removeClass('d-none');
            }
        });
        $('#form-create').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('content', quill.root.innerHTML);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('categories', JSON.stringify($('#categories').val()));
            formData.append('authors', JSON.stringify($('#authors').val()));
            $.ajax({
                url: '{{ route('admin.comic.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == "success") {
                        alert(response.message);
                        window.location.href = '{{ route('admin.comic.index') }}';
                    }
                }, error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            });
        });
    </script>
@endpush
