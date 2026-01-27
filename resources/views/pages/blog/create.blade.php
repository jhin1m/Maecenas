@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.1/dist/quill.snow.css" rel="stylesheet">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Thêm Mới Bài Viết'])
    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h3>Thêm mới bài viết</h3>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <form id="form-create" class="bg-white p-3 rounded-3">
                                <div class="row">
                                    <div class="col-xxl-9">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control" placeholder="Nhập tiêu đề bài viết" id="title" name="title">
                                        </div>
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Nội dung</label>
                                            <div id="content" style="height: fit-content;">
                                            </div>
                                        </div>
                                        <div class="mb-3 d-flex gap-3">
                                            <div class="w-100">
                                                <label for="status" class="form-label">Trạng thái</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option value="draft">Draft</option>
                                                    <option value="published">Published</option>
                                                    <option value="archived">Archived</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xxl-3">
                                        <div class="mb-3 form-fileThumb">
                                            <label for="image" class="form-label">Ảnh thumbnail</label>
                                            <input class="form-control" type="file" accept="image/*" id="image" name="image">
                                        </div>
                                        <div class="mb-3 form-urlThumb d-none">
                                            <label for="imageUrl" class="form-label">URL Ảnh thumbnail</label>
                                            <input class="form-control" placeholder="Nhập đường dẫn ảnh" type="text" id="imageUrl" name="imageUrl">
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input imageOption" type="radio" name="imageOption" id="imageOption1" value="file" checked>
                                                <label class="form-check-label" for="imageOption1">
                                                    Tải hình ảnh lên từ máy
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input imageOption" type="radio" name="imageOption" id="imageOption2" value="url">
                                                <label class="form-check-label" for="imageOption2">
                                                    Sử dụng URL hình ảnh
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
    <script>
        const quill = new Quill('#content', {
            theme: 'snow'
        });

        $('#image').on('change', function() {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.preview-img').html('<img class="w-100" src="' + e.target.result + '" alt="preview" />');
            }
            reader.readAsDataURL(this.files[0]);
        });
        $('#imageUrl').on('input', function() {
            $('.preview-img').html('<img class="w-100" src="' + this.value + '" alt="preview" />');
        });
        $('.imageOption').on('change', function() {
            $('#imageUrl').val('');
            $('#image').val('');
            $('.preview-img').html("Xem trước ảnh");
            if ($('#imageOption2').is(':checked')) {
                $('.form-urlThumb').removeClass('d-none');
                $('.form-fileThumb').addClass('d-none');
            } else if ($('#imageOption1').is(':checked')) {
                $('.form-urlThumb').addClass('d-none');
                $('.form-fileThumb').removeClass('d-none');
            }
        });
        $('#form-create').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('content', quill.root.innerHTML);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('admin.blog.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == "success") {
                        alert(response.message);
                        window.location.href = '{{ route('admin.blog.index') }}';
                    }
                }, error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            });
        });
    </script>
@endpush
